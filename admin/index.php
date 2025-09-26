<?php 
include 'partials/header.php'; 

// Consultas SQL para obter as estatísticas
// 1. Número de pedidos pendentes (LÓGICA ATUALIZADA)
$result_pendentes = $conexao->query("SELECT COUNT(id) AS total FROM pedidos WHERE status IN ('Pedido Recebido', 'Pagamento em Análise')");
$pedidos_pendentes = $result_pendentes->fetch_assoc()['total'];

// 2. Faturamento (considerando pedidos com pagamento confirmado em diante)
$status_faturamento = ['Pagamento Confirmado', 'Em Separação', 'Enviado', 'Em rota de entrega', 'Entregue', 'Concluído'];
$placeholders = implode("','", $status_faturamento);
$result_faturamento = $conexao->query("SELECT SUM(total) AS faturamento FROM pedidos WHERE status IN ('$placeholders')");
$faturamento = $result_faturamento->fetch_assoc()['faturamento'] ?? 0;

// 3. Número total de clientes cadastrados
$result_clientes = $conexao->query("SELECT COUNT(id) AS total FROM usuarios WHERE role = 'cliente'");
$total_clientes = $result_clientes->fetch_assoc()['total'];

// 4. Número total de produtos
$result_produtos = $conexao->query("SELECT COUNT(id) AS total FROM produtos");
$total_produtos = $result_produtos->fetch_assoc()['total'];

?>

<div class="dashboard-page">
    <h2>Dashboard</h2>
    <p>Bem-vindo ao painel administrativo da RUBYE, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</p>

    <div class="dashboard-grid">
        <div class="card">
            <h3>Pedidos Pendentes</h3>
            <p class="stat"><?php echo $pedidos_pendentes; ?></p>
            <a href="ver_pedidos.php">Ver Pedidos</a>
        </div>
        <div class="card">
            <h3>Faturamento (Pagamentos Confirmados)</h3>
            <p class="stat">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></p>
            <a href="ver_pedidos.php">Ver Relatórios</a>
        </div>
        <div class="card">
            <h3>Clientes Cadastrados</h3>
            <p class="stat"><?php echo $total_clientes; ?></p>
            <a href="#">Gerenciar Clientes</a>
        </div>
        <div class="card">
            <h3>Produtos na Loja</h3>
            <p class="stat"><?php echo $total_produtos; ?></p>
            <a href="gerenciar_produtos.php">Ver Produtos</a>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>