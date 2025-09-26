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
        $titulo_pagina = "Nossos Produtos"; // Título padrão

        // LÓGICA DE FILTRO CORRIGIDA ABAIXO
        
        // SE HOUVER UMA PESQUISA, EXECUTA A LÓGICA DE BUSCA
        if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
            $termo_busca = trim($_GET['busca']);
            $titulo_pagina = "Resultados para: '" . htmlspecialchars($termo_busca) . "'";
            $param_busca = "%" . $termo_busca . "%";
            $stmt = $conexao->prepare("SELECT * FROM produtos WHERE (nome LIKE ? OR descricao LIKE ?) AND status = 'ativo' ORDER BY nome ASC");
            $stmt->bind_param("ss", $param_busca, $param_busca);
        
        // SENÃO, SE HOUVER UM FILTRO DE CATEGORIA
        } elseif (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
            $categoria_id = $_GET['categoria'];
            $stmt_cat_nome = $conexao->prepare("SELECT nome FROM categorias WHERE id = ?");
            $stmt_cat_nome->bind_param("i", $categoria_id);
            $stmt_cat_nome->execute();
            $cat_result = $stmt_cat_nome->get_result()->fetch_assoc();
            if ($cat_result) {
                $titulo_pagina = htmlspecialchars($cat_result['nome']);
            }
            $stmt = $conexao->prepare("SELECT * FROM produtos WHERE categoria_id = ? AND status = 'ativo' ORDER BY nome ASC");
            $stmt->bind_param("i", $categoria_id);
        
        // **AQUI ESTÁ A LÓGICA CORRETA PARA FILTRAR POR COLEÇÃO**
        } elseif (isset($_GET['colecao']) && is_numeric($_GET['colecao'])) {
            $colecao_id = $_GET['colecao'];

            // Busca o nome da coleção para usar como título da página
            $stmt_col_nome = $conexao->prepare("SELECT nome FROM colecoes WHERE id = ?");
            $stmt_col_nome->bind_param("i", $colecao_id);
            $stmt_col_nome->execute();
            $col_result = $stmt_col_nome->get_result()->fetch_assoc();
            if ($col_result) {
                $titulo_pagina = "Coleção: " . htmlspecialchars($col_result['nome']);
            }

            // Prepara a consulta SQL que une as tabelas e filtra pela coleção correta
            $sql = "SELECT p.* FROM produtos p
                    INNER JOIN produto_colecao pc ON p.id = pc.produto_id
                    WHERE pc.colecao_id = ? AND p.status = 'ativo'
                    ORDER BY p.nome ASC";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $colecao_id);
        
        // SENÃO, MOSTRA TODOS OS PRODUTOS
        } else {
            $stmt = $conexao->prepare("SELECT * FROM produtos WHERE status = 'ativo' ORDER BY nome ASC");
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
                                <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            </div>
                            <h4><?php echo htmlspecialchars($produto['nome']); ?></h4>
                            <p class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        </a>
                    </div>
            <?php 
                }
            } else {
                echo "<p>Nenhum produto encontrado nesta coleção.</p>";
            }
            ?>
        </div>
    </main>
</div>

<?php include 'partials/footer.php'; ?>