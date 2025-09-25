<?php include 'partials/header.php'; ?>

<section class="splide" aria-label="Carrossel de Imagens">
  <div class="splide__track">
        <ul class="splide__list">
            <li class="splide__slide">
                <img src="../assets/img/2.webp" alt="Coleção de Inverno">
                <div class="slide-content">
                    <h2>Nova Coleção</h2>
                    <a href="produtos.php" class="btn-slide">Comprar Agora</a>
                </div>
            </li>
            <li class="splide__slide">
                <img src="../assets/img/3.webp" alt="Acessórios Exclusivos">
                <div class="slide-content">
                    <h2>Acessórios</h2>
                    <a href="produtos.php" class="btn-slide">Descobrir</a>
                </div>
            </li>
            <li class="splide__slide">
                <img src="../assets/img/1.jpg" alt="Estilo Urbano">
                <div class="slide-content">
                    <h2>Estilo Urbano</h2>
                    <a href="produtos.php" class="btn-slide">Ver Coleção</a>
                </div>
            </li>
        </ul>
  </div>
</section>

<section class="destaques">
    <h3 class="section-title">NEW IN</h3>
    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM produtos WHERE status = 'ativo' ORDER BY id DESC LIMIT 4";
        $produtos = $conexao->query($sql);
        while ($produto = $produtos->fetch_assoc()) {
        ?>
            <div class="product-card">
                <a href="produto.php?id=<?php echo $produto['id']; ?>">
                    <div class="product-image-container">
                        <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    </div>
                    <h4><?php echo htmlspecialchars($produto['nome']); ?></h4>
                    <p class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                </a>
            </div>
        <?php } ?>
    </div>
    <div class="see-all-container">
        <a href="produtos.php" class="btn-primary">Ver Tudo</a>
    </div>
</section>

<?php include 'partials/footer.php'; ?>