<?php
require_once '../config/db.php';

// Verificação de segurança: Apenas administradores podem executar esta ação.
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pedido_id']) && isset($_POST['status'])) {
    
    $pedido_id = (int)$_POST['pedido_id'];
    $novo_status = $_POST['status'];

    // --- NOVA LÓGICA DE VALIDAÇÃO DE SEQUÊNCIA ---
    
    // Define a ordem hierárquica dos status de processamento
    $sequencia_status = [
        'Pedido Recebido', 
        'Pagamento em Análise', 
        'Pagamento Confirmado', // Este é o "portão"
        'Em Separação', 
        'Enviado', 
        'Em rota de entrega', 
        'Entregue'
    ];
    
    // Encontra a posição (índice) do novo status e do status de "portão"
    $indice_novo = array_search($novo_status, $sequencia_status);
    $indice_portao = array_search('Pagamento Confirmado', $sequencia_status);

    // Busca o status atual do pedido no banco de dados
    $stmt_atual = $conexao->prepare("SELECT status FROM pedidos WHERE id = ?");
    $stmt_atual->bind_param("i", $pedido_id);
    $stmt_atual->execute();
    $status_atual = $stmt_atual->get_result()->fetch_assoc()['status'];
    $indice_atual = array_search($status_atual, $sequencia_status);

    // REGRA: Se o novo status está DEPOIS de "Pagamento Confirmado" na sequência...
    if ($indice_novo > $indice_portao) {
        // ...então o status atual DEVE SER "Pagamento Confirmado" ou um posterior.
        if ($indice_atual < $indice_portao) {
            // Se a regra for violada, redireciona com um erro específico.
            header("Location: ver_pedidos.php?erro=status_sequencia");
            exit();
        }
    }
    // --- FIM DA NOVA LÓGICA ---

    // Prepara e executa a atualização no banco de dados (código original)
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