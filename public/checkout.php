<?php 
include 'partials/header.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=login_necessario');
    exit();
}


if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}
?>

<div class="checkout-page">
    <h2 class="section-title">Finalizar Compra</h2>

    <div class="checkout-container">
        <div class="order-summary">
            <h3>Resumo do Pedido</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_pedido = 0;
                    $ids_produtos = array_keys($_SESSION['carrinho']);
                    $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
                    $tipos = str_repeat('i', count($ids_produtos));

                    $stmt = $conexao->prepare("SELECT id, nome, preco FROM produtos WHERE id IN ($placeholders)");
                    $stmt->bind_param($tipos, ...$ids_produtos);
                    $stmt->execute();
                    $produtos = $stmt->get_result();
                    
                    while ($produto = $produtos->fetch_assoc()) {
                        $quantidade = $_SESSION['carrinho'][$produto['id']];
                        $subtotal = $produto['preco'] * $quantidade;
                        $total_pedido += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['nome']); ?> (x<?php echo $quantidade; ?>)</td>
                            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td>R$ <?php echo number_format($total_pedido, 2, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="customer-info">
            <h3>Seus Dados</h3>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></p>
            <p><strong>Email:</strong> 
                <?php 
                $id_usuario = $_SESSION['usuario_id'];
                $stmt_user = $conexao->prepare("SELECT email FROM usuarios WHERE id = ?");
                $stmt_user->bind_param("i", $id_usuario);
                $stmt_user->execute();
                $resultado_user = $stmt_user->get_result()->fetch_assoc();
                echo htmlspecialchars($resultado_user['email']);
                ?>
            </p>
            <p><small>Em um site real, aqui haveria um formulário para endereço de entrega.</small></p>
        </div>
    </div>

    <div class="checkout-actions">
        <a href="finalizar_pedido.php" class="btn-primary">Confirmar Pedido</a>
        <a href="carrinho.php">Voltar ao Carrinho</a>
    </div>
</div>

<?php include 'partials/footer.php'; ?>