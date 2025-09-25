<?php include 'partials/header.php'; ?>

<div class="cart-page">
    <h2 class="section-title">Meu Carrinho de Compras</h2>

    <?php if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) : ?>
        <div class="cart-layout">
            
            <div class="cart-items-list">
                <?php
                $total_carrinho = 0;
                $ids_produtos = array_keys($_SESSION['carrinho']);
                $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
                $tipos = str_repeat('i', count($ids_produtos));

                $stmt = $conexao->prepare("SELECT id, nome, preco, imagem, estoque FROM produtos WHERE id IN ($placeholders)");
                $stmt->bind_param($tipos, ...$ids_produtos);
                $stmt->execute();
                $produtos = $stmt->get_result();
                
                while ($produto = $produtos->fetch_assoc()) :
                    $id_produto = $produto['id'];
                    $quantidade = $_SESSION['carrinho'][$id_produto];
                    $subtotal = $produto['preco'] * $quantidade;
                    $total_carrinho += $subtotal;
                ?>
                    <div class="cart-item-card">
                        <div class="cart-item-image">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        </div>
                        <div class="cart-item-details">
                            <h4 class="item-title"><?php echo htmlspecialchars($produto['nome']); ?></h4>
                            <p class="item-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                            <a href="carrinho_acoes.php?acao=remover&id=<?php echo $id_produto; ?>" class="remove-link">Remover</a>
                        </div>
                        <div class="cart-item-quantity">
                             <form action="carrinho_acoes.php?acao=atualizar" method="POST" class="form-update-qty">
                                <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                                <input type="number" name="quantidade" value="<?php echo $quantidade; ?>" min="1" max="<?php echo $produto['estoque']; ?>" class="qty-input">
                                <button type="submit">Atualizar</button>
                            </form>
                        </div>
                        <div class="cart-item-subtotal">
                            <p>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="cart-summary-box">
                <h3>Resumo da Compra</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span>R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></span>
                </div>
                <div class="summary-actions">
                    <a href="checkout.php" class="btn-primary">Finalizar Compra</a>
                    <a href="produtos.php" class="back-to-shop">Continuar a comprar</a>
                </div>
            </div>

        </div>
    <?php else : ?>
        <div class="cart-empty">
            <p>Seu carrinho de compras está vazio.</p>
            <a href="produtos.php" class="btn-primary">Ver produtos</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>