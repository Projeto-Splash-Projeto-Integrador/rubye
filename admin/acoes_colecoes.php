<?php
require_once '/../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    // --- AÇÃO: ADICIONAR COLEÇÃO ---
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
        exit();
    }

    // --- NOVA AÇÃO: EDITAR COLEÇÃO ---
    elseif ($acao == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = (int)$_POST['id'];
        $nome = $_POST['nome'];
        $imagem_antiga = $_POST['imagem_antiga'];
        $imagem_nome = $imagem_antiga;


        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $diretorio_upload = '../assets/uploads/';
            

            if (!empty($imagem_antiga) && file_exists($diretorio_upload . $imagem_antiga)) {
                unlink($diretorio_upload . $imagem_antiga);
            }

            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagem_nome = 'col_' . uniqid() . '.' . $extensao;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio_upload . $imagem_nome);
        }

        $stmt = $conexao->prepare("UPDATE colecoes SET nome = ?, imagem = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $imagem_nome, $id);
        $stmt->execute();
        header("Location: gerenciar_colecoes.php?sucesso=3");
        exit();
    }

    // --- AÇÃO: EXCLUIR COLEÇÃO ---
    elseif ($acao == 'excluir' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt_get = $conexao->prepare("SELECT imagem FROM colecoes WHERE id = ?");
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $resultado = $stmt_get->get_result()->fetch_assoc();
        if ($resultado && !empty($resultado['imagem'])) {
            $caminho_arquivo = '../assets/uploads/' . $resultado['imagem'];
            if (file_exists($caminho_arquivo)) {
                unlink($caminho_arquivo);
            }
        }
        
        $stmt = $conexao->prepare("DELETE FROM colecoes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: gerenciar_colecoes.php?sucesso=2");
        exit();
    }
}
exit();
?>