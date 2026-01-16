<?php include 'partials/header.php';

require_once __DIR__ . '/../config/db.php';
?>

<h2>Gerenciar Coleções</h2>

<?php

if (isset($_GET['sucesso'])) {
    $sucesso_msgs = [
        '1' => 'Coleção adicionada com sucesso!',
        '2' => 'Coleção excluída com sucesso!',
        '3' => 'Coleção atualizada com sucesso!'
    ];
    $msg_key = $_GET['sucesso'];
    if(isset($sucesso_msgs[$msg_key])) {
        echo '<p class="success">' . $sucesso_msgs[$msg_key] . '</p>';
    }
}
?>

<div class="form-container">
    <h3>Adicionar Nova Coleção</h3>
    <form action="acoes_colecoes.php?acao=adicionar" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome da Coleção:</label>
        <input type="text" id="nome" name="nome" required>
        
        <label for="imagem">Imagem da Coleção (Capa):</label>
        <input type="file" id="imagem" name="imagem">

        <button type="submit">Salvar Coleção</button>
    </form>
</div>

<div class="table-container">
    <h3>Coleções Cadastradas</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $colecoes = $conexao->query("SELECT * FROM colecoes ORDER BY nome ASC");
            while ($colecao = $colecoes->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $colecao['id']; ?></td>
                    <td><img src="../assets/uploads/<?php echo htmlspecialchars($colecao['imagem'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($colecao['nome']); ?>" width="100"></td>
                    <td><?php echo htmlspecialchars($colecao['nome']); ?></td>
                    <td>
                        <a href="editar_colecao.php?id=<?php echo $colecao['id']; ?>">Editar</a>
                        <a href="acoes_colecoes.php?acao=excluir&id=<?php echo $colecao['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'partials/footer.php'; ?>