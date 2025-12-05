<?php 
include 'partials/header.php'; 

// --- LÓGICA PHP (MANTIDA IGUAL, APENAS VISUAL MUDOU) ---
$where = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($_GET['data_inicio'])) {
    $where .= " AND DATE(data_pedido) >= ?";
    $params[] = $_GET['data_inicio'];
    $types .= "s";
}
if (!empty($_GET['data_fim'])) {
    $where .= " AND DATE(data_pedido) <= ?";
    $params[] = $_GET['data_fim'];
    $types .= "s";
}
if (!empty($_GET['status'])) {
    $where .= " AND status = ?";
    $params[] = $_GET['status'];
    $types .= "s";
}

// KPIs
$sql_kpi = "SELECT SUM(total) as total_faturamento, COUNT(id) as total_pedidos FROM pedidos $where";
$stmt = $conexao->prepare($sql_kpi);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$kpi = $stmt->get_result()->fetch_assoc();

$faturamento_total = $kpi['total_faturamento'] ?? 0;
$qtd_pedidos = $kpi['total_pedidos'] ?? 0;
$ticket_medio = ($qtd_pedidos > 0) ? $faturamento_total / $qtd_pedidos : 0;

// Gráfico
$sql_grafico = "SELECT DATE_FORMAT(data_pedido, '%Y-%m') as mes, SUM(total) as total FROM pedidos $where GROUP BY mes ORDER BY mes ASC";
$stmt = $conexao->prepare($sql_grafico);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res_grafico = $stmt->get_result();
$labels_grafico = []; $data_grafico = [];
while ($row = $res_grafico->fetch_assoc()) {
    $labels_grafico[] = date('m/Y', strtotime($row['mes'] . '-01'));
    $data_grafico[] = $row['total'];
}

// Rankings
$sql_produtos = "SELECT p.nome, SUM(pi.quantidade) as qtd_vendida, SUM(pi.preco_unitario * pi.quantidade) as total_gerado 
                 FROM pedido_itens pi JOIN produtos p ON pi.produto_id = p.id JOIN pedidos ped ON pi.pedido_id = ped.id 
                 $where GROUP BY p.id ORDER BY qtd_vendida DESC LIMIT 5";
$stmt = $conexao->prepare($sql_produtos);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$ranking_produtos = $stmt->get_result();

$sql_clientes = "SELECT u.nome, COUNT(ped.id) as qtd_pedidos, SUM(ped.total) as total_gasto 
                 FROM pedidos ped JOIN usuarios u ON ped.usuario_id = u.id 
                 $where GROUP BY u.id ORDER BY total_gasto DESC LIMIT 5";
$stmt = $conexao->prepare($sql_clientes);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$ranking_clientes = $stmt->get_result();
?>

<div class="dashboard-page">
    
    <div class="page-header">
        <h2>Relatórios Gerenciais</h2>
        <form action="exportar_relatorio.php" method="GET" target="_blank">
            <input type="hidden" name="data_inicio" value="<?php echo $_GET['data_inicio'] ?? ''; ?>">
            <input type="hidden" name="data_fim" value="<?php echo $_GET['data_fim'] ?? ''; ?>">
            <input type="hidden" name="status" value="<?php echo $_GET['status'] ?? ''; ?>">
            <button type="submit" class="btn-primary">Baixar Excel</button>
        </form>
    </div>

    <div class="filter-card">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>Data Início</label>
                <input type="date" name="data_inicio" value="<?php echo $_GET['data_inicio'] ?? ''; ?>">
            </div>
            <div class="filter-group">
                <label>Data Fim</label>
                <input type="date" name="data_fim" value="<?php echo $_GET['data_fim'] ?? ''; ?>">
            </div>
            <div class="filter-group">
                <label>Status do Pedido</label>
                <select name="status">
                    <option value="">Todos</option>
                    <option value="Concluído" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Concluído') ? 'selected' : ''; ?>>Concluído</option>
                    <option value="Pendente" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                    <option value="Enviado" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Enviado') ? 'selected' : ''; ?>>Enviado</option>
                </select>
            </div>
            <div class="filter-group">
                <label>&nbsp;</label> <button type="submit" class="btn-secondary">Filtrar Resultados</button>
            </div>
            <?php if(!empty($_GET)): ?>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="relatorios.php" style="color: #c62828; font-size: 0.9rem; text-decoration: underline;">Limpar Filtros</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="kpi-grid">
        <div class="kpi-card">
            <h3>Faturamento Total</h3>
            <p class="stat" style="color: #2e7d32;">R$ <?php echo number_format($faturamento_total, 2, ',', '.'); ?></p>
        </div>
        <div class="kpi-card">
            <h3>Total de Pedidos</h3>
            <p class="stat"><?php echo $qtd_pedidos; ?></p>
        </div>
        <div class="kpi-card">
            <h3>Ticket Médio</h3>
            <p class="stat" style="color: #1565c0;">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></p>
        </div>
    </div>

    <div class="modern-table-container" style="padding: 20px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 20px;">Evolução de Vendas</h3>
        <div style="height: 350px;">
            <canvas id="graficoVendas"></canvas>
        </div>
    </div>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div class="modern-table-container" style="flex: 1; min-width: 300px;">
            <div class="modern-table-header">
                <h3>Produtos Mais Vendidos</h3>
            </div>
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th style="text-align: center;">Qtd.</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($prod = $ranking_produtos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prod['nome']); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $prod['qtd_vendida']; ?></td>
                        <td style="text-align: right;">R$ <?php echo number_format($prod['total_gerado'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="modern-table-container" style="flex: 1; min-width: 300px;">
            <div class="modern-table-header">
                <h3>Melhores Clientes</h3>
            </div>
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th style="text-align: center;">Pedidos</th>
                        <th style="text-align: right;">Gasto Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cli = $ranking_clientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cli['nome']); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $cli['qtd_pedidos']; ?></td>
                        <td style="text-align: right; color: #2e7d32;">R$ <?php echo number_format($cli['total_gasto'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoVendas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels_grafico); ?>,
            datasets: [{
                label: 'Faturamento (R$)',
                data: <?php echo json_encode($data_grafico); ?>,
                backgroundColor: 'rgba(209, 56, 91, 0.1)',
                borderColor: '#d1385b',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#d1385b',
                pointRadius: 5,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash: [5, 5] } }, x: { grid: { display: false } } }
        }
    });
</script>
<?php include 'partials/footer.php'; ?>