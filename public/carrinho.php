<?php include 'partials/header.php'; ?>

<div class="cart-page">
    <h2>Meu Carrinho de Compras</h2>

    <?php
    $total_carrinho = 0;
    // Verifica se o carrinho existe e não está vazio
    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) :
    ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th colspan="2">Produto</th>
                    <th>Preço Unit.</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Pega todos os IDs dos produtos do carrinho
                $ids_produtos = array_keys($_SESSION['carrinho']);
                $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
                $tipos = str_repeat('i', count($ids_produtos));

                // Busca os dados de todos os produtos do carrinho de uma só vez
                $stmt = $conexao->prepare("SELECT id, nome, preco, imagem FROM produtos WHERE id IN ($placeholders)");
                $stmt->bind_param($tipos, ...$ids_produtos);
                $stmt->execute();
                $produtos = $stmt->get_result();
                
                while ($produto = $produtos->fetch_assoc()) :
                    $id_produto = $produto['id'];
                    $quantidade = $_SESSION['carrinho'][$id_produto];
                    $subtotal = $produto['preco'] * $quantidade;
                    $total_carrinho += $subtotal;
                ?>
                    <tr>
                        <td>
                            <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" width="80">
                        </td>
                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td>
                            <form action="carrinho_acoes.php?acao=atualizar" method="POST" class="form-update-qty">
                                <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                                <input type="number" name="quantidade" value="<?php echo $quantidade; ?>" min="1" class="qty-input">
                                <button type="submit">Atualizar</button>
                            </form>
                        </td>
                        <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                        <td>
                            <a href="carrinho_acoes.php?acao=remover&id=<?php echo $id_produto; ?>" class="remove-link">Remover</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="cart-total">
                <strong>Total do Pedido: R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></strong>
            </div>
            <div class="cart-actions">
                <a href="carrinho_acoes.php?acao=limpar" class="btn-secondary">Limpar Carrinho</a>
                <a href="checkout.php" class="btn-primary">Finalizar Compra</a>
            </div>
        </div>

    <?php else : ?>
        <div class="cart-empty">
            <p>Seu carrinho de compras está vazio.</p>
            <a href="index.php" class="btn-primary">Ver produtos</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>