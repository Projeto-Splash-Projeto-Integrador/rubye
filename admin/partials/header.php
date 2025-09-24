<?php
// Inclui o arquivo de configuração. O caminho usa __DIR__ para garantir que ele sempre funcione.
require_once __DIR__ . '/../../config/db.php';

// VERIFICAÇÃO DE SEGURANÇA FUNDAMENTAL
// Se não houver uma sessão de usuário ou se o usuário não for 'admin',
// ele é redirecionado para a página de login. Isso protege todo o painel.
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    header("Location: login.php");
    exit(); // Encerra o script para garantir que nada mais seja executado.
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - RUBYE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="admin-header">
        <h1>Painel Administrativo RUBYE</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="gerenciar_produtos.php">Produtos</a>
            <a href="gerenciar_categorias.php">Categorias</a>
            <a href="ver_pedidos.php">Pedidos</a>
            <a href="../public/logout.php">Sair</a>
        </nav>
    </header>
    <main class="admin-container">