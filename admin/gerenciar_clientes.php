<?php 
include 'partials/header.php'; 
require_once __DIR__ . '/../config/db.php';

// Lógica de Pesquisa e SQL (Mantida igual)
$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_SPECIAL_CHARS);
$filtro_sql = "";
$params = [];
$types = "";

if ($busca) {
    $filtro_sql = "AND (u.nome LIKE ? OR u.email LIKE ?)";
    $termo = "%{$busca}%";
    $params[] = $termo; $params[] = $termo;
    $types = "ss";
}

$sql = "SELECT u.id, u.nome, u.email, u.data_cadastro, COUNT(p.id) as qtd_pedidos, COALESCE(SUM(p.total), 0) as total_gasto
        FROM usuarios u LEFT JOIN pedidos p ON u.id = p.usuario_id
        WHERE u.role = 'cliente' $filtro_sql GROUP BY u.id ORDER BY u.data_cadastro DESC";

$stmt = $conexao->prepare($sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$clientes = $stmt->get_result();
?>

<div class="dashboard-page">
    
    <div class="page-header">
        <h2>Gerenciar Clientes</h2>
    </div>

    <?php
    if (isset($_GET['sucesso'])) echo '<div style="background:#e8f5e9; color:#2e7d32; padding:15px; border-radius:8px; margin-bottom:20px;">Cliente excluído com sucesso!</div>';
    if (isset($_GET['erro'])) echo '<div style="background:#ffebee; color:#c62828; padding:15px; border-radius:8px; margin-bottom:20px;">Erro ao excluir cliente.</div>';
    ?>

    <div class="filter-card">
        <form method="GET" class="filter-form" style="align-items: center;">
            <div class="filter-group" style="flex: 1;">
                <label>Buscar Cliente</label>
                <input type="text" name="busca" placeholder="Digite o nome ou e-mail..." value="<?php echo $busca; ?>" style="width: 100%;">
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn-primary">Pesquisar</button>
            </div>
            <?php if($busca): ?>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="gerenciar_clientes.php" class="btn-secondary">Limpar</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="modern-table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Cliente</th>
                    <th>Data Cadastro</th>
                    <th style="text-align: center;">Pedidos</th>
                    <th style="text-align: right;">LTV (Gasto Total)</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($clientes->num_rows > 0) {
                    while ($cliente = $clientes->fetch_assoc()) {
                        $dias_cadastro = (time() - strtotime($cliente['data_cadastro'])) / (60 * 60 * 24);
                        $eh_novo = $dias_cadastro <= 30;
                ?>
                    <tr>
                        <td>#<?php echo $cliente['id']; ?></td>
                        <td>
                            <div style="font-weight: 600; color: #333;"><?php echo htmlspecialchars($cliente['nome']); ?></div>
                            <div style="font-size: 0.85rem; color: #888;"><?php echo htmlspecialchars($cliente['email']); ?></div>
                            <?php if($eh_novo): ?>
                                <span class="status-badge status-novo" style="margin-top:5px; display:inline-block;">Novo Cliente</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($cliente['data_cadastro'])); ?></td>
                        
                        <td style="text-align: center;">
                            <?php if($cliente['qtd_pedidos'] > 0): ?>
                                <span style="background:#e3f2fd; color:#1565c0; padding:4px 8px; border-radius:4px; font-weight:bold;">
                                    <?php echo $cliente['qtd_pedidos']; ?>
                                </span>
                            <?php else: ?>
                                <span style="color:#ccc;">0</span>
                            <?php endif; ?>
                        </td>
                        
                        <td style="text-align: right; font-weight: 600; color: #555;">
                            R$ <?php echo number_format($cliente['total_gasto'], 2, ',', '.'); ?>
                        </td>
                        
                        <td style="text-align: right;">
                            <a href="ver_pedidos.php?cliente_id=<?php echo $cliente['id']; ?>" class="btn-action btn-view" title="Ver Histórico">Ver Pedidos</a>
                            <a href="acoes_cliente.php?acao=excluir&id=<?php echo $cliente['id']; ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('Tem certeza? Isso apagará todo o histórico deste cliente!');"
                               title="Excluir">Excluir</a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding: 40px; color: #999;'>Nenhum cliente encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'partials/footer.php'; ?>