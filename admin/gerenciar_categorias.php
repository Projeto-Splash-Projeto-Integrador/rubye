<?php include 'partials/header.php'; ?>

<h2>Gerenciamento de Categorias</h2>

<?php
// Exibe mensagens de sucesso ou erro vindas dos scripts de ação
if (isset($_GET['sucesso'])) {
    echo '<p class="success">Ação realizada com sucesso!</p>';
}
if (isset($_GET['erro'])) {
    echo '<p class="error">Ocorreu um erro ao realizar a ação.</p>';
}
?>

<div class="form-container">
    <h3>Adicionar Nova Categoria</h3>
    <form action="acoes_categoria.php?acao=adicionar" method="POST">
        <label for="nome">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome" required>
        <button type="submit">Adicionar</button>
    </form>
</div>

<div class="table-container">
    <h3>Categorias Cadastradas</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM categorias ORDER BY nome ASC";
            $categorias = $conexao->query($sql);
            while ($categoria = $categorias->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $categoria['id']; ?></td>
                    <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                    <td>
                        <a href="editar_categoria.php?id=<?php echo $categoria['id']; ?>">Editar</a>
                        <a href="acoes_categoria.php?acao=excluir&id=<?php echo $categoria['id']; ?>" onclick="return confirm('Atenção! Todos os produtos desta categoria ficarão sem categoria. Deseja continuar?');">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'partials/footer.php'; ?>