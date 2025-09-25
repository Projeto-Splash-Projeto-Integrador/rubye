<?php 
include 'partials/header.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h1>Produto não encontrado!</h1>";
    include 'partials/footer.php';
    exit();
}

$id = $_GET['id'];
$stmt = $conexao->prepare("SELECT p.*, c.nome AS categoria_nome 
                           FROM produtos p 
                           LEFT JOIN categorias c ON p.categoria_id = c.id 
                           WHERE p.id = ? AND p.status = 'ativo'");
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

<div class="product-page-container">
    <div class="product-gallery">
        <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
    </div>
    <div class="product-details">
        <?php
        if (isset($produto['categoria_nome']) && !empty($produto['categoria_nome'])) {
            echo '<p class="product-category">' . htmlspecialchars($produto['categoria_nome']) . '</p>';
        }
        ?>
        <h1 class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></h1>
        
        <p class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
        
        <div class="product-description">
            <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
        </div>
        
        <form action="carrinho_acoes.php?acao=adicionar" method="POST" class="form-add-to-cart">
            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
            
            <div class="quantity-selector">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" value="1" min="1" max="<?php echo $produto['estoque']; ?>">
            </div>

            <button type="submit" class="btn-add-to-cart">Adicionar ao Carrinho</button>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>