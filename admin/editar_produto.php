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

// Busca as coleções já associadas a este produto
$colecoes_atuais_stmt = $conexao->prepare("SELECT colecao_id FROM produto_colecao WHERE produto_id = ?");
$colecoes_atuais_stmt->bind_param("i", $id);
$colecoes_atuais_stmt->execute();
$result_colecoes_atuais = $colecoes_atuais_stmt->get_result();
$colecoes_atuais = [];
// Busca as imagens adicionais
$imagens_stmt = $conexao->prepare("SELECT id, caminho_imagem FROM produto_imagens WHERE produto_id = ?");
$imagens_stmt->bind_param("i", $id);
$imagens_stmt->execute();
$imagens_adicionais = $imagens_stmt->get_result();

while ($row = $result_colecoes_atuais->fetch_assoc()) {
    $colecoes_atuais[] = $row['colecao_id'];
}
?>

<h2>Editar Produto</h2>

<?php if(isset($_GET['img_sucesso'])) echo "<p class='success'>Imagem excluída com sucesso!</p>"; ?>
<?php if(isset($_GET['img_erro'])) echo "<p class='error'>Erro ao excluir imagem.</p>"; ?>

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
        <select name="categoria_id">
            <option value="">Nenhuma</option>
            <?php
            $result_cat = $conexao->query("SELECT * FROM categorias ORDER BY nome");
            while ($cat = $result_cat->fetch_assoc()) {
                $selected = ($cat['id'] == $produto['categoria_id']) ? 'selected' : '';
                echo "<option value='{$cat['id']}' {$selected}>" . htmlspecialchars($cat['nome']) . "</option>";
            }
            ?>
        </select>

        <label for="colecoes">Coleções (segure Ctrl para selecionar várias):</label>
        <select name="colecoes[]" id="colecoes" multiple style="height: 150px;">
            <?php
            $colecoes_result = $conexao->query("SELECT * FROM colecoes ORDER BY nome");
            while ($col = $colecoes_result->fetch_assoc()) {
                // Verifica se a coleção atual está na lista de coleções do produto
                $selected = in_array($col['id'], $colecoes_atuais) ? 'selected' : '';
                echo "<option value='{$col['id']}' {$selected}>" . htmlspecialchars($col['nome']) . "</option>";
            }
            ?>
        </select>

        <label>Imagem de Capa Atual:</label>
<img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem atual" width="100" style="margin-bottom: 20px;">
<input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($produto['imagem']); ?>">

<hr style="grid-column: 1 / -1; border-top: 1px solid #eee; margin: 20px 0;">

<h4 style="grid-column: 1 / -1; margin-top: 0;">Imagens Adicionais</h4>
<div style="grid-column: 1 / -1; display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
    <?php 
    // Reinicia o ponteiro do resultado para o loop
    $imagens_adicionais->data_seek(0); 
    while($img = $imagens_adicionais->fetch_assoc()): 
    ?>
        <div style="position: relative; border: 1px solid #ccc; padding: 5px;">
            <img src="../assets/uploads/<?php echo htmlspecialchars($img['caminho_imagem']); ?>" width="100">
            <a href="acoes_produto.php?acao=excluir_imagem&id_imagem=<?php echo $img['id']; ?>&id_produto=<?php echo $id; ?>"
               onclick="return confirm('Tem certeza que deseja excluir esta imagem?');"
               style="position: absolute; top: 0; right: 0; background: red; color: white; text-decoration: none; padding: 2px 5px; font-weight: bold;">X</a>
        </div>
    <?php endwhile; ?>
</div>

<label for="imagens">Adicionar/Trocar Imagens (opcional):</label>
<input type="file" id="imagens" name="imagens[]" multiple>
<p style="grid-column: 1 / -1; font-size: 0.8em; color: #666;">Se enviar novas imagens, a primeira substituirá a capa atual. As demais serão adicionadas à galeria.</p>

        <button type="submit">Salvar Alterações</button>
        <a href="gerenciar_produtos.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include 'partials/footer.php'; ?>