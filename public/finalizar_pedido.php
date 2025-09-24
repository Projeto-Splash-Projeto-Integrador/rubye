<?php
require_once '../config/db.php';

// 1. VERIFICAÇÕES DE SEGURANÇA
// -----------------------------------------------------------------------------

// O usuário deve estar logado para finalizar um pedido
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=login_necessario');
    exit();
}

// O carrinho não pode estar vazio
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}

// 2. LÓGICA DE TRANSAÇÃO NO BANCO DE DADOS
// -----------------------------------------------------------------------------
// Usar uma transação garante que todas as operações (criar pedido, inserir
// itens, atualizar estoque) ocorram com sucesso. Se uma falhar, todas
// são desfeitas (rollback), mantendo a integridade dos dados.

$conexao->begin_transaction();

try {
    // Passo A: Buscar preços e estoque atuais no DB e calcular o total
    $ids_produtos = array_keys($_SESSION['carrinho']);
    $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
    $tipos = str_repeat('i', count($ids_produtos));

    $stmt_produtos = $conexao->prepare("SELECT id, preco, estoque FROM produtos WHERE id IN ($placeholders)");
    $stmt_produtos->bind_param($tipos, ...$ids_produtos);
    $stmt_produtos->execute();
    $produtos_db = $stmt_produtos->get_result()->fetch_all(MYSQLI_ASSOC);

    $total_pedido = 0;
    foreach ($produtos_db as $produto) {
        $id_produto = $produto['id'];
        $quantidade_carrinho = $_SESSION['carrinho'][$id_produto];

        // Validação de estoque
        if ($produto['estoque'] < $quantidade_carrinho) {
            throw new Exception("Estoque insuficiente para o produto ID: " . $id_produto);
        }

        $total_pedido += $produto['preco'] * $quantidade_carrinho;
    }

    // Passo B: Inserir o registro principal na tabela 'pedidos'
    $usuario_id = $_SESSION['usuario_id'];
    $stmt_pedido = $conexao->prepare("INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'Pendente')");
    $stmt_pedido->bind_param("id", $usuario_id, $total_pedido);
    $stmt_pedido->execute();
    $pedido_id = $conexao->insert_id; // Pega o ID do pedido recém-criado

    // Passo C: Inserir cada item do carrinho na tabela 'pedido_itens' e atualizar o estoque
    $stmt_item = $conexao->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    $stmt_estoque = $conexao->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");

    foreach ($produtos_db as $produto) {
        $id_produto = $produto['id'];
        $quantidade = $_SESSION['carrinho'][$id_produto];
        $preco_unitario = $produto['preco'];

        // Insere o item
        $stmt_item->bind_param("iiid", $pedido_id, $id_produto, $quantidade, $preco_unitario);
        $stmt_item->execute();

        // Atualiza o estoque
        $stmt_estoque->bind_param("ii", $quantidade, $id_produto);
        $stmt_estoque->execute();
    }

    // Se tudo deu certo até aqui, confirma as alterações no banco
    $conexao->commit();

    // Limpa o carrinho da sessão
    unset($_SESSION['carrinho']);

    // Redireciona para uma página de sucesso
    header('Location: minha_conta.php?sucesso=pedido_realizado');
    exit();

} catch (Exception $e) {
    // Se qualquer etapa falhou, desfaz todas as alterações
    $conexao->rollback();
    
    // Redireciona de volta ao carrinho com uma mensagem de erro
    // Em um sistema real, você poderia logar o erro: error_log($e->getMessage());
    header('Location: carrinho.php?erro=processamento');
    exit();
}
?>