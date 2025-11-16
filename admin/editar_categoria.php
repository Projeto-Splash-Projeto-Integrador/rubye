<?php 
include 'partials/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='error'>ID da categoria inválido.</p>";
    include 'partials/footer.php';
    exit();
}

$id = $_GET['id'];
$stmt = $conexao->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$categoria = $resultado->fetch_assoc();

if (!$categoria) {
    echo "<p class='error'>Categoria não encontrada.</p>";
    include 'partials/footer.php';
    exit();
}
?>

<h2>Editar Categoria</h2>

<div class="form-container">
    <form action="acoes_categoria.php?acao=editar" method="POST">
        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
        
        <label for="nome">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
        
        <button type="submit">Salvar Alterações</button>
        <a href="gerenciar_categorias.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>


<?php include 'partials/footer.php'; ?>