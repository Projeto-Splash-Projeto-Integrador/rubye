<?php
require_once '../config/db.php';


if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pedido_id']) && isset($_POST['status'])) {
    
    $pedido_id = (int)$_POST['pedido_id'];
    $novo_status = $_POST['status'];

    $sequencia_status = [
        'Pedido Recebido', 
        'Pagamento em Análise', 
        'Pagamento Confirmado', 
        'Em Separação', 
        'Enviado', 
        'Em rota de entrega', 
        'Entregue'
    ];
    

    $indice_novo = array_search($novo_status, $sequencia_status);
    $indice_portao = array_search('Pagamento Confirmado', $sequencia_status);


    $stmt_atual = $conexao->prepare("SELECT status FROM pedidos WHERE id = ?");
    $stmt_atual->bind_param("i", $pedido_id);
    $stmt_atual->execute();
    $status_atual = $stmt_atual->get_result()->fetch_assoc()['status'];
    $indice_atual = array_search($status_atual, $sequencia_status);


    if ($indice_novo > $indice_portao) {

        if ($indice_atual < $indice_portao) {

            header("Location: ver_pedidos.php?erro=status_sequencia");
            exit();
        }
    }



    $stmt = $conexao->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_status, $pedido_id);
    
    if ($stmt->execute()) {
        header("Location: ver_pedidos.php?sucesso=status_atualizado");
    } else {
        header("Location: ver_pedidos.php?erro=status_falhou");
    }

} else {
    header("Location: ver_pedidos.php");
}

exit();
?>