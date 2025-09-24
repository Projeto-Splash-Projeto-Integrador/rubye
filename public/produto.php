<?php 
include 'partials/header.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h1>Produto não encontrado!</h1>";
    include 'partials/footer.php';
    exit();
}

$id = $_GET['id'];
$stmt = $conexao->prepare("SELECT p.*, c.nome AS categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$produto = $resultado->fetch_assoc();

if (!$produto) {
    echo "<h1>Produto não encontrado!</h1>";
    include 'partials/footer.php';
    exit();
}
?>

<div class="product-detail-container">
    <div class="product-image-gallery">
        <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
    </div>
    <div class="product-info-details">
        <span class="category-tag"><?php echo htmlspecialchars($produto['categoria_nome']); ?></span>
        <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
        <p class="price-tag"><?php echo number_format($produto['preco'], 2, ',', '.'); ?> €</p>
        
        <div class="description-text">
            <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?>
        </div>
        
        <form action="carrinho_acoes.php?acao=adicionar" method="POST" class="form-add-to-cart">
            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
            <label for="quantidade">Quantidade</label>
            <input type="number" name="quantidade" value="1" min="1" max="<?php echo $produto['estoque']; ?>">
            <button type="submit" class="btn-primary">Adicionar ao Carrinho</button>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>