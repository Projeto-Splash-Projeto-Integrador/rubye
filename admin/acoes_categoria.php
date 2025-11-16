<?php
require_once '../config/db.php';


if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    // --- AÇÃO: ADICIONAR CATEGORIA ---
    if ($acao == 'adicionar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        if (!empty($nome)) {
            $stmt = $conexao->prepare("INSERT INTO categorias (nome) VALUES (?)");
            $stmt->bind_param("s", $nome);
            $stmt->execute();
            header("Location: gerenciar_categorias.php?sucesso=1");
        } else {
            header("Location: gerenciar_categorias.php?erro=1");
        }
    }

    // --- AÇÃO: EDITAR CATEGORIA ---
    elseif ($acao == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        if (!empty($nome) && !empty($id)) {
            $stmt = $conexao->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
            $stmt->bind_param("si", $nome, $id);
            $stmt->execute();
            header("Location: gerenciar_categorias.php?sucesso=2");
        } else {
            header("Location: gerenciar_categorias.php?erro=2");
        }
    }

    // --- AÇÃO: EXCLUIR CATEGORIA ---
    elseif ($acao == 'excluir' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conexao->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: gerenciar_categorias.php?sucesso=3");
    }
}

exit();
?>