<?php 
include 'partials/header.php'; 

// Segurança: Apenas utilizadores logados podem aceder a esta página
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
?>

<div class="account-page">
    <h2>Minha Conta</h2>
    
    <h3>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h3>
    <p>Bem-vindo à sua área de cliente. Aqui pode visualizar o seu histórico de pedidos.</p>

    <?php
    // Exibe uma mensagem de sucesso se o cliente acabou de fazer um pedido
    if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'pedido_realizado') {
        echo '<p class="success">O seu pedido foi realizado com sucesso! Obrigado por comprar na RUBYE.</p>';
    }
    ?>

    <div class="order-history">
        <h4>Seu Histórico de Pedidos</h4>
        <?php
        $stmt = $conexao->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $pedidos = $stmt->get_result();

        if ($pedidos->num_rows > 0) :
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Nº do Pedido</th>
                        <th>Data</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = $pedidos->fetch_assoc()) : ?>
                        <tr>
                            <td>#<?php echo $pedido['id']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                            <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Você ainda não fez nenhum pedido.</p>
        <?php endif; ?>
    </div>

    <div class="account-actions">
        <a href="logout.php" class="btn-secondary">Terminar Sessão</a>
    </div>
</div>

<?php include 'partials/footer.php'; ?>