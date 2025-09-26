<?php include 'partials/header.php'; ?>

<div class="page-container">
    <h2 class="section-title">Nossas Coleções</h2>
    
    <div class="collection-grid">
        <?php
        $colecoes = $conexao->query("SELECT * FROM colecoes ORDER BY nome ASC");
        if ($colecoes->num_rows > 0) {
            while ($colecao = $colecoes->fetch_assoc()) {
        ?>
            <div class="collection-card">
                <a href="produtos.php?colecao=<?php echo $colecao['id']; ?>">
                    <img src="../assets/uploads/<?php echo htmlspecialchars($colecao['imagem'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($colecao['nome']); ?>">
                    <div class="collection-card-overlay">
                        <h3><?php echo htmlspecialchars($colecao['nome']); ?></h3>
                    </div>
                </a>
            </div>
        <?php
            }
        } else {
            echo "<p>Nenhuma coleção encontrada.</p>";
        }
        ?>
    </div>
</div>

<?php include 'partials/footer.php'; ?>