<?php
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - RUBYE</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="admin-header">
        <h1>Painel Administrativo RUBYE</h1>
        <nav>
            <a href="index.php">Minha Conta</a>
            <a href="gerenciar_produtos.php">Produtos</a>
            <a href="gerenciar_categorias.php">Categorias</a>
            <a href="gerenciar_colecoes.php">Coleções</a>
             <a href="ver_pedidos.php">Pedidos</a>
            <a href="../public/logout.php">Sair</a>
        </nav>
    </header>
    <main class="admin-container">