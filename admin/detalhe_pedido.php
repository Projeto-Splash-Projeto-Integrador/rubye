<?php 
include 'partials/header.php';

// Valida o ID do pedido na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de pedido inválido.");
}
$id_pedido = $_GET['id'];

// Query para buscar os dados do pedido e do cliente
$stmt_pedido = $conexao->prepare("SELECT p.*, u.nome, u.email FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?");
$stmt_pedido->bind_param("i", $id_pedido);
$stmt_pedido->execute();
$pedido = $stmt_pedido->get_result()->fetch_assoc();

if (!$pedido) {
    die("Pedido não encontrado.");
}

// Query para buscar os itens do pedido
$stmt_itens = $conexao->prepare("SELECT oi.quantidade, oi.preco_unitario, pr.nome AS nome_produto FROM pedido_itens oi JOIN produtos pr ON oi.produto_id = pr.id WHERE oi.pedido_id = ?");
$stmt_itens->bind_param("i", $id_pedido);
$stmt_itens->execute();
$itens = $stmt_itens->get_result();
?>

<div class="order-detail-page">
    <a href="ver_pedidos.php" class="btn-secondary">&larr; Voltar para todos os pedidos</a>
    
    <h2>Detalhes do Pedido #<?php echo $pedido['id']; ?></h2>

    <div class="order-detail-grid">
        <div class="customer-info-box">
            <h4>Informações do Cliente</h4>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['nome']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
        </div>
        <div class="order-info-box">
            <h4>Dados do Pedido</h4>
            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
            <p><strong>Valor Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($pedido['status']); ?></p>
            </div>
    </div>

    <div class="order-items-box">
        <h4>Itens do Pedido</h4>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $itens->fetch_assoc()) : 
                    $subtotal = $item['quantidade'] * $item['preco_unitario'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nome_produto']); ?></td>
                    <td><?php echo $item['quantidade']; ?></td>
                    <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'partials/footer.php'; ?>