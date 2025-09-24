<?php
require_once '../config/db.php';

// Verificação de segurança: Apenas administradores podem executar estas ações
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

// Verifica se uma ação foi definida via GET
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
        // NOTA: A constraint 'ON DELETE SET NULL' na tabela 'produtos' fará com que 
        // o campo 'categoria_id' dos produtos associados se torne NULL.
        $stmt = $conexao->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: gerenciar_categorias.php?sucesso=3");
    }
}

exit();
?>