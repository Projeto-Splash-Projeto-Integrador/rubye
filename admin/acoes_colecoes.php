<?php
require_once '../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    if ($acao == 'adicionar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $imagem_nome = null;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $diretorio_upload = '../assets/uploads/';
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagem_nome = 'col_' . uniqid() . '.' . $extensao;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio_upload . $imagem_nome);
        }

        $stmt = $conexao->prepare("INSERT INTO colecoes (nome, imagem) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $imagem_nome);
        $stmt->execute();
        header("Location: gerenciar_colecoes.php?sucesso=1");
    }

    elseif ($acao == 'excluir' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conexao->prepare("DELETE FROM colecoes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: gerenciar_colecoes.php?sucesso=2");
    }
}
exit();
?>