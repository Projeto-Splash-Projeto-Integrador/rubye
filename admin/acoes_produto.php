<?php
require_once '../config/db.php';


if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}


unset($_SESSION['bulk_alert_message']);


if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];


    if ($acao == 'bulk_update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['produto_ids']) || empty($_POST['produto_ids']) || !isset($_POST['bulk_action']) || empty($_POST['bulk_action'])) {
            header("Location: gerenciar_produtos.php");
            exit();
        }

        $produto_ids = array_map('intval', $_POST['produto_ids']);
        $bulk_action = $_POST['bulk_action'];
        $ids_string = implode(',', $produto_ids);

        $produtos_afetados = 0;
        $produtos_ignorados_nomes = [];


        if ($bulk_action == 'ativar' || $bulk_action == 'desativar') {
            $novo_status = ($bulk_action == 'ativar') ? 'ativo' : 'inativo';
            
            $stmt_check = $conexao->query("SELECT id, nome, status FROM produtos WHERE id IN ($ids_string)");
            $produtos_para_alterar = [];

            while ($produto = $stmt_check->fetch_assoc()) {
                if ($produto['status'] != $novo_status) {
                    $produtos_para_alterar[] = $produto['id'];
                } else {
                    $produtos_ignorados_nomes[] = $produto['nome'];
                }
            }

            if (!empty($produtos_para_alterar)) {
                $ids_para_alterar_string = implode(',', $produtos_para_alterar);
                $stmt_update = $conexao->prepare("UPDATE produtos SET status = ? WHERE id IN ($ids_para_alterar_string)");
                $stmt_update->bind_param("s", $novo_status);
                $stmt_update->execute();
                $produtos_afetados = $stmt_update->affected_rows;
            }
        }
        

        elseif (strpos($bulk_action, 'add_collection_') === 0) {
            $collection_id = (int)str_replace('add_collection_', '', $bulk_action);
            
            $produtos_para_adicionar = [];

            foreach ($produto_ids as $produto_id) {
                $stmt_check = $conexao->prepare("SELECT COUNT(*) as total FROM produto_colecao WHERE produto_id = ? AND colecao_id = ?");
                $stmt_check->bind_param("ii", $produto_id, $collection_id);
                $stmt_check->execute();
                $result = $stmt_check->get_result()->fetch_assoc();

                if ($result['total'] == 0) {
                    $produtos_para_adicionar[] = $produto_id;
                } else {
                    $stmt_nome = $conexao->prepare("SELECT nome FROM produtos WHERE id = ?");
                    $stmt_nome->bind_param("i", $produto_id);
                    $stmt_nome->execute();
                    $produtos_ignorados_nomes[] = $stmt_nome->get_result()->fetch_assoc()['nome'];
                }
            }
            
            if (!empty($produtos_para_adicionar)) {
                $stmt_insert = $conexao->prepare("INSERT INTO produto_colecao (produto_id, colecao_id) VALUES (?, ?)");
                foreach ($produtos_para_adicionar as $id_prod) {
                    $stmt_insert->bind_param("ii", $id_prod, $collection_id);
                    $stmt_insert->execute();
                }
                $produtos_afetados = count($produtos_para_adicionar);
            }
        }


        if (!empty($produtos_ignorados_nomes)) {
            $nomes = implode(', ', $produtos_ignorados_nomes);
            $_SESSION['bulk_alert_message'] = "Ação concluída. Os seguintes produtos foram ignorados pois já estavam no estado desejado: $nomes.";
        }

        header("Location: gerenciar_produtos.php?sucesso=4");
        exit();
    }


elseif ($acao == 'adicionar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_FILES['imagens']) || empty($_FILES['imagens']['name'][0])) {
        header("Location: gerenciar_produtos.php?erro=imagem_obrigatoria");
        exit();
    }

    $conexao->begin_transaction();
    try {
        $diretorio_upload = '../assets/uploads/';
        $extensao = pathinfo($_FILES['imagens']['name'][0], PATHINFO_EXTENSION);
        $imagem_principal_nome = 'prod_' . uniqid() . '.' . $extensao;
        if (!move_uploaded_file($_FILES['imagens']['tmp_name'][0], $diretorio_upload . $imagem_principal_nome)) {
            throw new Exception("Erro ao fazer upload da imagem principal.");
        }

        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = str_replace(',', '.', $_POST['preco']);
        $estoque = $_POST['estoque'];
        $categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
        $colecoes = isset($_POST['colecoes']) ? $_POST['colecoes'] : [];

        $stmt = $conexao->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $nome, $descricao, $preco, $estoque, $categoria_id, $imagem_principal_nome);
        $stmt->execute();
        $produto_id = $conexao->insert_id;

        if (count($_FILES['imagens']['name']) > 1) {
            $stmt_img = $conexao->prepare("INSERT INTO produto_imagens (produto_id, caminho_imagem) VALUES (?, ?)");
            for ($i = 1; $i < count($_FILES['imagens']['name']); $i++) {
                if ($_FILES['imagens']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['imagens']['name'][$i], PATHINFO_EXTENSION);
                    $img_add_nome = 'prod_' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['imagens']['tmp_name'][$i], $diretorio_upload . $img_add_nome);
                    $stmt_img->bind_param("is", $produto_id, $img_add_nome);
                    $stmt_img->execute();
                }
            }
        }

        if (!empty($colecoes)) {
            $stmt_colecao = $conexao->prepare("INSERT INTO produto_colecao (produto_id, colecao_id) VALUES (?, ?)");
            foreach ($colecoes as $colecao_id) {
                $stmt_colecao->bind_param("ii", $produto_id, $colecao_id);
                $stmt_colecao->execute();
            }
        }

        $conexao->commit();
        header("Location: gerenciar_produtos.php?sucesso=1");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: gerenciar_produtos.php?erro=1");
    }
    exit();
}

elseif ($acao == 'editar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexao->begin_transaction();
    try {
        $id = (int)$_POST['id'];
        $imagem_antiga = $_POST['imagem_antiga'];
        $imagem_nome_principal = $imagem_antiga;


        if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
            $diretorio_upload = '../assets/uploads/';

            $ext_principal = pathinfo($_FILES['imagens']['name'][0], PATHINFO_EXTENSION);
            $imagem_nome_principal = 'prod_' . uniqid() . '.' . $ext_principal;
            if (!move_uploaded_file($_FILES['imagens']['tmp_name'][0], $diretorio_upload . $imagem_nome_principal)) {
                throw new Exception("Erro ao atualizar imagem principal.");
            }

            if (!empty($imagem_antiga) && file_exists($diretorio_upload . $imagem_antiga)) {
                unlink($diretorio_upload . $imagem_antiga);
            }


            if (count($_FILES['imagens']['name']) > 1) {
                $stmt_img = $conexao->prepare("INSERT INTO produto_imagens (produto_id, caminho_imagem) VALUES (?, ?)");
                for ($i = 1; $i < count($_FILES['imagens']['name']); $i++) {
                    if ($_FILES['imagens']['error'][$i] === UPLOAD_ERR_OK) {
                        $ext_add = pathinfo($_FILES['imagens']['name'][$i], PATHINFO_EXTENSION);
                        $img_add_nome = 'prod_' . uniqid() . '.' . $ext_add;
                        move_uploaded_file($_FILES['imagens']['tmp_name'][$i], $diretorio_upload . $img_add_nome);
                        $stmt_img->bind_param("is", $id, $img_add_nome);
                        $stmt_img->execute();
                    }
                }
            }
        }

 
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = str_replace(['.', ','], ['', '.'], $_POST['preco']);
        $estoque = $_POST['estoque'];
        $categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
        $colecoes = isset($_POST['colecoes']) ? $_POST['colecoes'] : [];

 
        $stmt_produto = $conexao->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ?, imagem = ? WHERE id = ?");
        $stmt_produto->bind_param("ssdiisi", $nome, $descricao, $preco, $estoque, $categoria_id, $imagem_nome_principal, $id);
        $stmt_produto->execute();


        $stmt_delete_colecao = $conexao->prepare("DELETE FROM produto_colecao WHERE produto_id = ?");
        $stmt_delete_colecao->bind_param("i", $id);
        $stmt_delete_colecao->execute();

        if (!empty($colecoes)) {
            $stmt_insert_colecao = $conexao->prepare("INSERT INTO produto_colecao (produto_id, colecao_id) VALUES (?, ?)");
            foreach ($colecoes as $colecao_id) {
                $stmt_insert_colecao->bind_param("ii", $id, $colecao_id);
                $stmt_insert_colecao->execute();
            }
        }

        $conexao->commit();
        header("Location: gerenciar_produtos.php?sucesso=2");
    } catch (Exception $e) {
        $conexao->rollback();

        header("Location: editar_produto.php?id=$id&erro=2");
    }
    exit();
}

    

    elseif ($acao == 'excluir_imagem' && isset($_GET['id_imagem'])) {
        $id_imagem = (int)$_GET['id_imagem'];
        $id_produto = (int)$_GET['id_produto']; 
        $stmt_get = $conexao->prepare("SELECT caminho_imagem FROM produto_imagens WHERE id = ?");
        $stmt_get->bind_param("i", $id_imagem);
        $stmt_get->execute();
        $resultado = $stmt_get->get_result()->fetch_assoc();

        if ($resultado) {
            $caminho_arquivo = '../assets/uploads/' . $resultado['caminho_imagem'];
            if (file_exists($caminho_arquivo)) {
                unlink($caminho_arquivo); 
            }
        }

        $stmt_delete = $conexao->prepare("DELETE FROM produto_imagens WHERE id = ?");
        $stmt_delete->bind_param("i", $id_imagem);
        if ($stmt_delete->execute()) {
            header("Location: editar_produto.php?id=$id_produto&img_sucesso=1");
        } else {
            header("Location: editar_produto.php?id=$id_produto&img_erro=1");
        }
        exit();
    }

    elseif ($acao == 'desativar' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conexao->prepare("UPDATE produtos SET status = 'inativo' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: gerenciar_produtos.php?sucesso=3");
        } else {
            header("Location: gerenciar_produtos.php?erro=3");
        }
        exit();
    }


    elseif ($acao == 'reativar' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conexao->prepare("UPDATE produtos SET status = 'ativo' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: gerenciar_produtos.php?sucesso=3");
        } else {
            header("Location: gerenciar_produtos.php?erro=3");
        }
        exit();
    }
}


header("Location: gerenciar_produtos.php");
exit();
?>