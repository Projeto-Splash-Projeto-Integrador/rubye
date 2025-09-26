<?php 
include 'partials/header.php';

// Valida o ID da coleção na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='error'>ID da coleção inválido.</p>";
    include 'partials/footer.php';
    exit();
}

$id = $_GET['id'];
$stmt = $conexao->prepare("SELECT * FROM colecoes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$colecao = $resultado->fetch_assoc();

if (!$colecao) {
    echo "<p class='error'>Coleção não encontrada.</p>";
    include 'partials/footer.php';
    exit();
}
?>

<h2>Editar Coleção</h2>

<div class="form-container">
    <form action="acoes_colecoes.php?acao=editar" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $colecao['id']; ?>">
        
        <label for="nome">Nome da Coleção:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($colecao['nome']); ?>" required>
        
        <label>Imagem Atual:</label>
        <?php if (!empty($colecao['imagem'])): ?>
            <img src="../assets/uploads/<?php echo htmlspecialchars($colecao['imagem']); ?>" alt="Imagem atual" width="150">
        <?php else: ?>
            <p>Nenhuma imagem cadastrada.</p>
        <?php endif; ?>

        <label for="imagem">Trocar Imagem (opcional):</label>
        <input type="file" id="imagem" name="imagem">
        <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($colecao['imagem'] ?? ''); ?>">

        <button type="submit">Salvar Alterações</button>
        <a href="gerenciar_colecoes.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include 'partials/footer.php'; ?>