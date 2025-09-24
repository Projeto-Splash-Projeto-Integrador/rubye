<?php include 'partials/header.php'; ?>

<h2>Gerenciar Pedidos</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nº Pedido</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Valor Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query para buscar pedidos e juntar com o nome do cliente
            $sql = "SELECT p.id, p.data_pedido, p.total, p.status, u.nome AS nome_cliente 
                    FROM pedidos p 
                    JOIN usuarios u ON p.usuario_id = u.id 
                    ORDER BY p.data_pedido DESC";
            
            $pedidos = $conexao->query($sql);

            if ($pedidos->num_rows > 0) {
                while ($pedido = $pedidos->fetch_assoc()) {
            ?>
                <tr>
                    <td>#<?php echo $pedido['id']; ?></td>
                    <td><?php echo htmlspecialchars($pedido['nome_cliente']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                    <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($pedido['status']); ?></td>
                    <td>
                        <a href="detalhe_pedido.php?id=<?php echo $pedido['id']; ?>">Ver Detalhes</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum pedido encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'partials/footer.php'; ?>