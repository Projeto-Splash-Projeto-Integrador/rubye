<?php require_once __DIR__ . '/../../config/db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUBYE Store</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Work+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="header-logo">
            <a href="index.php">RUBYE</a>
        </div>
        
        <div class="header-search">
            <form action="produtos.php" method="GET" class="search-form">
                <input type="text" name="busca" placeholder="O que você está procurando?" required>
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="header-nav">
            <div class="header-icons">
                <a href="carrinho.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    (<span id="cart-counter"><?php echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?></span>)
                </a>

                <?php if (isset($_SESSION['usuario_id'])) : ?>
                    <a href="minha_conta.php" title="Minha Conta"><i class="fa-solid fa-user"></i></a>
                    <a href="logout.php" title="Sair"><i class="fa-solid fa-right-from-bracket"></i></a>
                <?php else : ?>
                    <a href="login.php" title="Login"><i class="fa-solid fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <nav class="secondary-nav">
        <a href="produtos.php">PRODUTOS</a>
        <a href="colecoes.php">COLEÇÕES</a>
        <a href="sobre.php">SOBRE NÓS</a>
        <a href="contato.php">CONTATO</a>
    </nav>
    <main class="site-main">