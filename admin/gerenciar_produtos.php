<?php include 'partials/header.php'; ?>

<h2>Gerenciamento de Produtos</h2>

<div class="form-container">
    <h3>Adicionar Novo Produto</h3>
    <form action="acoes_produto.php?acao=adicionar" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao"></textarea>

        <label for="preco">Preço (R$):</label>
        <input type="text" name="preco" required>

        <label for="estoque">Estoque:</label>
        <input type="number" name="estoque" required>

        <label for="categoria_id">Categoria:</label>
        <select name="categoria_id" required>
            <?php
            $result = $conexao->query("SELECT * FROM categorias ORDER BY nome");
            while ($cat = $result->fetch_assoc()) {
                echo "<option value='{$cat['id']}'>{$cat['nome']}</option>";
            }
            ?>
        </select>

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" required>

        <button type="submit">Salvar Produto</button>
    </form>
</div>

<div class="table-container">
    <h3>Produtos Cadastrados (Ativos)</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // AQUI ESTÁ A CORREÇÃO: Adicionamos "WHERE status = 'ativo'" à consulta
            $sql = "SELECT * FROM produtos WHERE status = 'ativo' ORDER BY id DESC";
            $produtos = $conexao->query($sql);
            while ($produto = $produtos->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $produto['id']; ?></td>
                    <td><img src="../assets/uploads/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" width="50"></td>
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $produto['estoque']; ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a>
                        <a href="acoes_produto.php?acao=excluir&id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza que deseja desativar este produto?');">Desativar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'partials/footer.php'; ?>