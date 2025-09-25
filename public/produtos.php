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
            // Se um filtro de categoria foi aplicado via URL
            if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
                $categoria_id = $_GET['categoria'];
                
                // AQUI ESTÁ A CORREÇÃO: Adicionamos "AND status = 'ativo'"
                $stmt = $conexao->prepare("SELECT * FROM produtos WHERE categoria_id = ? AND status = 'ativo' ORDER BY nome ASC");
                $stmt->bind_param("i", $categoria_id);
            } else {
                // Se não houver filtro, busca todos os produtos ATIVOS
                // AQUI ESTÁ A CORREÇÃO: Adicionamos "WHERE status = 'ativo'"
                $stmt = $conexao->prepare("SELECT * FROM produtos WHERE status = 'ativo' ORDER BY nome ASC");
            }

            $stmt->execute();
            $produtos = $stmt->get_result();

            if ($produtos->num_rows > 0) {
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
            <?php 
                }
            } else {
                echo "<p>Nenhum produto encontrado.</p>";
            }
            ?>
        </div>
    </main>
</div>

<?php include 'partials/footer.php'; ?>