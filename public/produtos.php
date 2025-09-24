<?php include 'partials/header.php'; ?>

<div class="product-gallery-page">
    <aside class="sidebar-filters">
        <h3>Categorias</h3>
        <ul>
            <li><a href="produtos.php">Todas</a></li>
            <?php
            // Busca e lista todas as categorias existentes como links de filtro
            $categorias = $conexao->query("SELECT * FROM categorias ORDER BY nome");
            while ($categoria = $categorias->fetch_assoc()) {
                echo "<li><a href='produtos.php?categoria={$categoria['id']}'>" . htmlspecialchars($categoria['nome']) . "</a></li>";
            }
            ?>
        </ul>
    </aside>

    <main class="product-list">
        <h2>Nossos Produtos</h2>
        <div class="product-grid">
            <?php
            // Monta a query SQL base
            $sql = "SELECT * FROM produtos";

            // Se um filtro de categoria foi aplicado via URL, adiciona a condição WHERE
            if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
                $categoria_id = $_GET['categoria'];
                // Usamos prepared statements para segurança
                $stmt = $conexao->prepare($sql . " WHERE categoria_id = ? ORDER BY nome ASC");
                $stmt->bind_param("i", $categoria_id);
            } else {
                $stmt = $conexao->prepare($sql . " ORDER BY nome ASC");
            }

            $stmt->execute();
            $produtos = $stmt->get_result();

            if ($produtos->num_rows > 0) {
                while ($produto = $produtos->fetch_assoc()) {
            ?>
                    <div class="product-card">
                        <a href="produto.php?id=<?php echo $produto['id']; ?>">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <h4><?php echo htmlspecialchars($produto['nome']); ?></h4>
                            <p class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        </a>
                    </div>
            <?php 
                }
            } else {
                echo "<p>Nenhum produto encontrado nesta categoria.</p>";
            }
            ?>
        </div>
    </main>
</div>

<?php include 'partials/footer.php'; ?>