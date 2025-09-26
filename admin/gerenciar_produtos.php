<?php include 'partials/header.php'; ?>

<style>
/* Adiciona um destaque visual para o filtro ativo */
.filters-container a.active {
    font-weight: bold;
    text-decoration: underline;
    color: #000;
}

/* Estilos para o formulário de ações em massa */
.bulk-actions-container {
    padding: 15px;
    background-color: #f8f8f8;
    border: 1px solid var(--border-color);
    border-top: none;
    display: flex;
    gap: 10px;
    align-items: center;
}
.bulk-actions-container select, .bulk-actions-container button {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Estilo para a caixa de alerta de produtos ignorados */
.alert-box {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #faebcc;
}
</style>

<h2>Gerenciamento de Produtos</h2>

<?php
// Exibe mensagens de sucesso ou erro
if (isset($_GET['sucesso'])) {
    $sucesso_msgs = [
        '1' => 'Produto adicionado com sucesso!',
        '2' => 'Produto atualizado com sucesso!',
        '3' => 'Status do produto alterado com sucesso!',
        '4' => 'Ação em massa realizada com sucesso!'
    ];
    $msg_key = $_GET['sucesso'];
    if(isset($sucesso_msgs[$msg_key])) {
        echo '<p class="success">' . $sucesso_msgs[$msg_key] . '</p>';
    }
}
if (isset($_GET['erro'])) {
    echo '<p class="error">Ocorreu um erro ao realizar a ação.</p>';
}

// Exibe a mensagem de ALERTA com os produtos ignorados, se houver
if (isset($_SESSION['bulk_alert_message'])) {
    echo '<div class="alert-box">' . htmlspecialchars($_SESSION['bulk_alert_message']) . '</div>';
    unset($_SESSION['bulk_alert_message']); // Limpa a mensagem após exibi-la
}
?>

<div class="form-container">
    <h3>Adicionar Novo Produto</h3>
    <form action="acoes_produto.php?acao=adicionar" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        
        <label for="descricao">Descrição:</label>
        <textarea name="descricao"></textarea>
        
        <label for="preco">Preço (R$):</label>
        <input type="text" name="preco" required>
        
        <label for="estoque">Estoque:</label>
        <input type="number" name="estoque" required>
        
        <label for="categoria_id">Categoria:</label>
        <select name="categoria_id">
            <option value="">Nenhuma</option>
            <?php
            $result = $conexao->query("SELECT * FROM categorias ORDER BY nome");
            while ($cat = $result->fetch_assoc()) {
                echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['nome']) . "</option>";
            }
            ?>
        </select>
        
        <label for="colecoes">Coleções (segure Ctrl para selecionar várias):</label>
        <select name="colecoes[]" id="colecoes" multiple style="height: 150px;">
            <?php
            $colecoes_result = $conexao->query("SELECT * FROM colecoes ORDER BY nome");
            while ($col = $colecoes_result->fetch_assoc()) {
                echo "<option value='{$col['id']}'>" . htmlspecialchars($col['nome']) . "</option>";
            }
            ?>
        </select>
        
        <label for="imagens_adicionais">Imagens Adicionais (segure Ctrl para selecionar várias):</label>
        <input type="file" id="imagens_adicionais" name="imagens_adicionais[]" multiple>

        <button type="submit">Salvar Produto</button>
    </form>
</div>

<div class="table-container">
    <h3>Produtos Cadastrados</h3>

    <?php
    $filtro_status = isset($_GET['status']) ? $_GET['status'] : 'todos';
    ?>

    <div class="filters-container" style="margin-bottom: 20px; padding: 10px; text-align: center; background-color: #f8f8f8;">
        <strong>Filtrar por:</strong>
        <a href="gerenciar_produtos.php?status=todos" class="<?php echo ($filtro_status == 'todos' ? 'active' : ''); ?>">Todos</a> |
        <a href="gerenciar_produtos.php?status=ativo" class="<?php echo ($filtro_status == 'ativo' ? 'active' : ''); ?>">Ativos</a> |
        <a href="gerenciar_produtos.php?status=inativo" class="<?php echo ($filtro_status == 'inativo' ? 'active' : ''); ?>">Inativos</a>
    </div>

    <form action="acoes_produto.php?acao=bulk_update" method="POST">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Coleção</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_where = "";
                if ($filtro_status == 'ativo') {
                    $sql_where = "WHERE p.status = 'ativo'";
                } elseif ($filtro_status == 'inativo') {
                    $sql_where = "WHERE p.status = 'inativo'";
                }

                $sql = "SELECT p.*, GROUP_CONCAT(c.nome SEPARATOR ', ') AS colecoes_nomes
                        FROM produtos p
                        LEFT JOIN produto_colecao pc ON p.id = pc.produto_id
                        LEFT JOIN colecoes c ON pc.colecao_id = c.id
                        $sql_where
                        GROUP BY p.id
                        ORDER BY p.id DESC";
                
                $produtos = $conexao->query($sql);

                if ($produtos->num_rows > 0) {
                    while ($produto = $produtos->fetch_assoc()) {
                ?>
                    <tr>
                        <td><input type="checkbox" name="produto_ids[]" class="produto-checkbox" value="<?php echo $produto['id']; ?>"></td>
                        <td><img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" width="60"></td>
                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                        <td><?php echo htmlspecialchars($produto['colecoes_nomes'] ?? 'Nenhuma'); ?></td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $produto['estoque']; ?></td>
                        <td><?php echo ucfirst($produto['status']); ?></td>
                        <td>
                            <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a>
                            <?php if ($produto['status'] == 'ativo'): ?>
                                <a href="acoes_produto.php?acao=desativar&id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza que deseja desativar este produto?');">Desativar</a>
                            <?php else: ?>
                                <a href="acoes_produto.php?acao=reativar&id=<?php echo $produto['id']; ?>">Reativar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhum produto encontrado para este filtro.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="bulk-actions-container">
            <label for="bulk_action">Ações em massa:</label>
            <select name="bulk_action" id="bulk_action" required>
                <option value="">-- Selecione uma ação --</option>
                <optgroup label="Alterar Status">
                    <option value="ativar">Ativar selecionados</option>
                    <option value="desativar">Desativar selecionados</option>
                </optgroup>
                <optgroup label="Adicionar à Coleção">
                    <?php
                    // Busca as coleções para popular o menu dinamicamente
                    $colecoes = $conexao->query("SELECT id, nome FROM colecoes ORDER BY nome");
                    while ($colecao = $colecoes->fetch_assoc()) {
                        echo "<option value='add_collection_{$colecao['id']}'>" . htmlspecialchars($colecao['nome']) . "</option>";
                    }
                    ?>
                </optgroup>
            </select>
            <button type="submit">Aplicar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const produtoCheckboxes = document.querySelectorAll('.produto-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        produtoCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });
});
</script>

<?php include 'partials/footer.php'; ?>