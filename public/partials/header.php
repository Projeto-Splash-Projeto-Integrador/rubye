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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="header-social">
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
        <div class="header-logo">
            <a href="index.php">RUBYE</a>
        </div>
        <div class="header-nav">
            <nav class="main-nav">
                <a href="produtos.php">SHOP</a>
                <a href="#">INFO</a>
                <a href="#">CONTACT</a>
            </nav>
            <div class="header-icons">
                <a href="carrinho.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    (<span id="cart-counter"><?php echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?></span>)
                </a>
                <a href="minha_conta.php"><i class="fa-solid fa-user"></i></a>
            </div>
        </div>
    </header>
    <main class="site-main">