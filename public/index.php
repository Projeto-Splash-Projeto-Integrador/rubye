<?php include 'partials/header.php'; ?>

<section class="banner">
    <h2>Bem-vindo à RUBYE</h2>
    <p>A moda que define você.</p>
</section>

<section class="destaques">
    <h3>Produtos em Destaque</h3>
    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM produtos ORDER BY id DESC LIMIT 4";
        $produtos = $conexao->query($sql);
        while ($produto = $produtos->fetch_assoc()) {
        ?>
            <div class="product-card">
                <a href="produto.php?id=<?php echo $produto['id']; ?>">
                    <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    <h4><?php echo htmlspecialchars($produto['nome']); ?></h4>
                    <p class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                </a>
            </div>
        <?php } ?>
    </div>
</section>

<?php include 'partials/footer.php'; ?>