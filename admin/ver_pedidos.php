<?php include 'partials/header.php'; ?>

<h2>Gerenciar Pedidos</h2>

<?php
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'status_atualizado') {
    echo '<p class="success">Status do pedido atualizado com sucesso!</p>';
}
if (isset($_GET['erro'])) {
    echo '<p class="error">Ocorreu um erro ao atualizar o status.</p>';
}
if (isset($_GET['erro']) && $_GET['erro'] == 'status_sequencia') {
    echo '<p class="error">Ação bloqueada: O pedido precisa ter o status "Pagamento Confirmado" antes de avançar para a separação ou envio.</p>';
}
?>

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
            $status_options = [
                'Pedido Recebido', 'Pagamento em Análise', 'Pagamento Confirmado', 'Em Separação',
                'Enviado', 'Em rota de entrega', 'Entregue', 'Pedido Cancelado'
            ];
            
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
                    <td>
                        <div class="status-view">
                            <span><?php echo htmlspecialchars($pedido['status']); ?></span>
                            <button type="button" class="btn-edit-status">Alterar</button>
                        </div>

                        <form action="acoes_pedido.php" method="POST" class="form-status-edit hidden">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                            <select name="status">
                                <?php foreach ($status_options as $status) : ?>
                                    <option value="<?php echo $status; ?>" <?php if ($pedido['status'] == $status) echo 'selected'; ?>>
                                        <?php echo $status; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn-save-status">Salvar</button>
                        </form>
                    </td>
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