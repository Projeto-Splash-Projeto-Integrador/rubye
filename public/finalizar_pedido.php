<?php
require_once '../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=login_necessario');
    exit();
}

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}

// LÓGICA DE TRANSAÇÃO
$conexao->begin_transaction();

try {
    
    $ids_produtos = array_keys($_SESSION['carrinho']);
    if (empty($ids_produtos)) {
        throw new Exception("Carrinho vazio após verificação inicial.");
    }
    
    $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
    $tipos = str_repeat('i', count($ids_produtos));

    $stmt_produtos = $conexao->prepare("SELECT id, preco, estoque FROM produtos WHERE id IN ($placeholders) AND status = 'ativo'");
    $stmt_produtos->bind_param($tipos, ...$ids_produtos);
    $stmt_produtos->execute();
    $produtos_db_result = $stmt_produtos->get_result();

    if ($produtos_db_result->num_rows != count($ids_produtos)) {
        throw new Exception("Um ou mais produtos no carrinho não estão disponíveis.");
    }

    $produtos_db = $produtos_db_result->fetch_all(MYSQLI_ASSOC);

    $total_pedido = 0;
    foreach ($produtos_db as $produto) {
        $id_produto = $produto['id'];
        $quantidade_carrinho = $_SESSION['carrinho'][$id_produto];

        if ($produto['estoque'] < $quantidade_carrinho) {
            throw new Exception("Estoque insuficiente para o produto ID: " . $id_produto);
        }

        $total_pedido += $produto['preco'] * $quantidade_carrinho;
    }

    $usuario_id = $_SESSION['usuario_id'];
    
    $stmt_pedido = $conexao->prepare("INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'Pedido Recebido')");
    
    $stmt_pedido->bind_param("id", $usuario_id, $total_pedido);
    $stmt_pedido->execute();
    $pedido_id = $conexao->insert_id;

    $stmt_item = $conexao->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    $stmt_estoque = $conexao->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");

    foreach ($produtos_db as $produto) {
        $id_produto = $produto['id'];
        $quantidade = $_SESSION['carrinho'][$id_produto];
        $preco_unitario = $produto['preco'];

        $stmt_item->bind_param("iiid", $pedido_id, $id_produto, $quantidade, $preco_unitario);
        $stmt_item->execute();

        $stmt_estoque->bind_param("ii", $quantidade, $id_produto);
        $stmt_estoque->execute();
    }

    $conexao->commit();
    unset($_SESSION['carrinho']);
    header('Location: minha_conta.php?sucesso=pedido_realizado');
    exit();

} catch (Exception $e) {

    $conexao->rollback();
    header('Location: carrinho.php?erro=processamento');
    exit();
}
?>