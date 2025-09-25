<?php
require_once '../config/db.php';

// Verificação de segurança: Apenas administradores podem executar estas ações
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

// Verifica se uma ação foi definida via GET
if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    // --- AÇÃO: ADICIONAR PRODUTO ---
    if ($acao == 'adicionar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coleta de dados do formulário
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = str_replace(',', '.', $_POST['preco']); // Converte vírgula para ponto
        $estoque = $_POST['estoque'];
        $categoria_id = $_POST['categoria_id'];

        // Lógica de Upload da Imagem
        $imagem_nome = 'default.jpg'; // Imagem padrão
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $diretorio_upload = '../assets/uploads/';
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagem_nome = uniqid() . '.' . $extensao; // Gera um nome único
            
            // Move o arquivo para a pasta de uploads
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio_upload . $imagem_nome)) {
                die("Erro ao fazer upload da imagem.");
            }
        }
        
        // Inserção no banco de dados
        $stmt = $conexao->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $nome, $descricao, $preco, $estoque, $categoria_id, $imagem_nome);
        
        if ($stmt->execute()) {
            header("Location: gerenciar_produtos.php?sucesso=1");
        } else {
            header("Location: gerenciar_produtos.php?erro=1");
        }
    }

    // --- AÇÃO: EDITAR PRODUTO ---
    elseif ($acao == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coleta de dados do formulário
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = str_replace(['.', ','], ['', '.'], $_POST['preco']); // Formato para o DB
        $estoque = $_POST['estoque'];
        $categoria_id = $_POST['categoria_id'];
        $imagem_antiga = $_POST['imagem_antiga'];
        $imagem_nome = $imagem_antiga; // Assume que a imagem não vai mudar

        // Lógica para UPLOAD de NOVA IMAGEM, se houver
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $diretorio_upload = '../assets/uploads/';
            
            // Apaga a imagem antiga (se não for a default)
            if ($imagem_antiga != 'default.jpg' && file_exists($diretorio_upload . $imagem_antiga)) {
                unlink($diretorio_upload . $imagem_antiga);
            }
            
            // Processa a nova imagem
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagem_nome = uniqid() . '.' . $extensao;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio_upload . $imagem_nome);
        }

        // Atualização no banco de dados
        $stmt = $conexao->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ?, imagem = ? WHERE id = ?");
        $stmt->bind_param("ssdiisi", $nome, $descricao, $preco, $estoque, $categoria_id, $imagem_nome, $id);
        
        if ($stmt->execute()) {
            header("Location: gerenciar_produtos.php?sucesso=2"); // Sucesso tipo 2 (edição)
        } else {
            header("Location: gerenciar_produtos.php?erro=2");
        }
    }

    // --- AÇÃO: EXCLUIR PRODUTO ---
    elseif ($acao == 'excluir' && isset($_GET['id'])) {
        $id = $_GET['id'];

        // Em vez de apagar, mudamos o status para 'inativo'
        $stmt = $conexao->prepare("UPDATE produtos SET status = 'inativo' WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("Location: gerenciar_produtos.php?sucesso=3");
        } else {
            header("Location: gerenciar_produtos.php?erro=3");
        }
    }
}

exit();
?>