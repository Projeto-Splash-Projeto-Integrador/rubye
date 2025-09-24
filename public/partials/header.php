<?php
// Inclui o arquivo de configuração e conexão com o banco de dados.
// session_start() já é chamado dentro de db.php.
require_once __DIR__ . '/../../config/db.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - RUBYE</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="public-header">
        <a href="index.php" class="logo">RUBYE</a>
        <nav>
            <a href="index.php">Home</a>
            <a href="produtos.php">Produtos</a>
            <a href="carrinho.php">
                Carrinho (<span id="cart-counter"><?php echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?></span>)
            </a>
            
            <?php // Navegação condicional: muda se o usuário está logado ou não.
            if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_role'] == 'cliente') : ?>
                <a href="minha_conta.php">Minha Conta</a>
                <a href="logout.php">Sair</a>
            <?php else : ?>
                <a href="login.php">Login</a>
                <a href="registro.php">Registrar</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="public-container">