<?php 
include 'partials/header.php';

// Valida o ID do produto na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='error'>ID do produto inválido.</p>";
    include 'partials/footer.php';
    exit();
}

$id = $_GET['id'];
$stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$produto = $resultado->fetch_assoc();

if (!$produto) {
    echo "<p class='error'>Produto não encontrado.</p>";
    include 'partials/footer.php';
    exit();
}
?>

<h2>Editar Produto</h2>

<div class="form-container">
    <form action="acoes_produto.php?acao=editar" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

        <label for="preco">Preço (R$):</label>
        <input type="text" name="preco" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" required>

        <label for="estoque">Estoque:</label>
        <input type="number" name="estoque" value="<?php echo $produto['estoque']; ?>" required>

        <label for="categoria_id">Categoria:</label>
        <select name="categoria_id" required>
            <?php
            $result_cat = $conexao->query("SELECT * FROM categorias ORDER BY nome");
            while ($cat = $result_cat->fetch_assoc()) {
                // Marca a categoria atual do produto como selecionada
                $selected = ($cat['id'] == $produto['categoria_id']) ? 'selected' : '';
                echo "<option value='{$cat['id']}' {$selected}>" . htmlspecialchars($cat['nome']) . "</option>";
            }
            ?>
        </select>

        <label>Imagem Atual:</label>
        <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem atual" width="100">
        
        <label for="imagem">Trocar Imagem (opcional):</label>
        <input type="file" name="imagem">
        <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($produto['imagem']); ?>">

        <button type="submit">Salvar Alterações</button>
        <a href="gerenciar_produtos.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include 'partials/footer.php'; ?>