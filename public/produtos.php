<?php include 'partials/header.php'; ?>

<div class="product-gallery-page">
    <aside class="sidebar-filters">
        <h3>Categorias</h3>
        <ul>
            <li><a href="produtos.php">Todas</a></li>
            <?php
            $categorias = $conexao->query("SELECT * FROM categorias ORDER BY nome");
            while ($categoria = $categorias->fetch_assoc()) {
                echo "<li><a href='produtos.php?categoria={$categoria['id']}'>" . htmlspecialchars($categoria['nome']) . "</a></li>";
            }
            ?>
        </ul>
    </aside>

    <main class="product-list">
        <?php
        $titulo_pagina = "Nossos Produtos";


        $sql_select_part = "SELECT p.*, (SELECT pi.caminho_imagem FROM produto_imagens pi WHERE pi.produto_id = p.id ORDER BY pi.id ASC LIMIT 1) AS imagem_hover FROM produtos p";

        // Filtro por BUSCA
        if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
            $termo_busca = trim($_GET['busca']);
            $titulo_pagina = "Resultados para: '" . htmlspecialchars($termo_busca) . "'";
            $param_busca = "%" . $termo_busca . "%";
            
            $sql = $sql_select_part . " WHERE (p.nome LIKE ? OR p.descricao LIKE ?) AND p.status = 'ativo' ORDER BY p.nome ASC";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ss", $param_busca, $param_busca);
        
        // Filtro por CATEGORIA
        } elseif (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
            $categoria_id = (int)$_GET['categoria'];
            $stmt_cat_nome = $conexao->prepare("SELECT nome FROM categorias WHERE id = ?");
            $stmt_cat_nome->bind_param("i", $categoria_id);
            $stmt_cat_nome->execute();
            $cat_result = $stmt_cat_nome->get_result()->fetch_assoc();
            if ($cat_result) {
                $titulo_pagina = htmlspecialchars($cat_result['nome']);
            }

            $sql = $sql_select_part . " WHERE p.categoria_id = ? AND p.status = 'ativo' ORDER BY p.nome ASC";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $categoria_id);
        
        // Filtro por COLEÇÃO
        } elseif (isset($_GET['colecao']) && is_numeric($_GET['colecao'])) {
            $colecao_id = (int)$_GET['colecao'];

            $stmt_col_nome = $conexao->prepare("SELECT nome FROM colecoes WHERE id = ?");
            $stmt_col_nome->bind_param("i", $colecao_id);
            $stmt_col_nome->execute();
            $col_result = $stmt_col_nome->get_result()->fetch_assoc();
            if ($col_result) {
                $titulo_pagina = "Coleção: " . htmlspecialchars($col_result['nome']);
            }
            
            $sql = $sql_select_part . " INNER JOIN produto_colecao pc ON p.id = pc.produto_id WHERE pc.colecao_id = ? AND p.status = 'ativo' ORDER BY p.nome ASC";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $colecao_id);
        
        // Sem filtro, mostra TODOS os produtos
        } else {
            $sql = $sql_select_part . " WHERE p.status = 'ativo' ORDER BY p.nome ASC";
            $stmt = $conexao->prepare($sql);
        }
        ?>

        <h2><?php echo $titulo_pagina; ?></h2>

        <div class="product-grid">
            <?php
            $stmt->execute();
            $produtos = $stmt->get_result();

            if ($produtos->num_rows > 0) {
                while ($produto = $produtos->fetch_assoc()) {
            ?>
                    <div class="product-card">
                        <a href="produto.php?id=<?php echo $produto['id']; ?>">
                            <div class="product-image-container">
                                <img class="img-main" src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php if (!empty($produto['imagem_hover'])): ?>
                                    <img class="img-hover" src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem_hover']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php endif; ?>
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