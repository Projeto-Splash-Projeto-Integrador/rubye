<?php
require_once '../config/db.php';

// Apenas inicia a sessão se não houver uma ativa.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inicializa o carrinho na sessão se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Função para enviar uma resposta JSON limpa e terminar o script
function json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Verifica se uma ação foi passada pela URL
if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    // --- AÇÃO: ADICIONAR ITEM AO CARRINHO (Via AJAX) ---
    if ($acao == 'adicionar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['id_produto'])) {
            json_response(['success' => false, 'message' => 'ID do produto ausente.']);
        }

        $id_produto = (int)$_POST['id_produto'];
        $quantidade = (int)$_POST['quantidade'];

        // Verificação de segurança para garantir que o produto está ativo
        $stmt = $conexao->prepare("SELECT status FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id_produto);
        $stmt->execute();
        $produto_status = $stmt->get_result()->fetch_assoc();

        if (!$produto_status || $produto_status['status'] !== 'ativo') {
            json_response(['success' => false, 'message' => 'Este produto não está disponível.']);
        }

        if ($id_produto > 0 && $quantidade > 0) {
            if (isset($_SESSION['carrinho'][$id_produto])) {
                $_SESSION['carrinho'][$id_produto] += $quantidade;
            } else {
                $_SESSION['carrinho'][$id_produto] = $quantidade;
            }
        }
        
        json_response([
            'success' => true,
            'cart_count' => count($_SESSION['carrinho'])
        ]);
    }

    // --- AÇÃO: ATUALIZAR QUANTIDADE ---
    elseif ($acao == 'atualizar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_produto = (int)$_POST['id_produto'];
        $quantidade = (int)$_POST['quantidade'];

        if (isset($_SESSION['carrinho'][$id_produto])) {
            if ($quantidade <= 0) {
                unset($_SESSION['carrinho'][$id_produto]);
            } else {
                $_SESSION['carrinho'][$id_produto] = $quantidade;
            }
        }
        header('Location: carrinho.php');
        exit();
    }
    
    // --- AÇÃO: REMOVER ITEM DO CARRINHO ---
    elseif ($acao == 'remover' && isset($_GET['id'])) {
        $id_produto = (int)$_GET['id'];
        if (isset($_SESSION['carrinho'][$id_produto])) {
            unset($_SESSION['carrinho'][$id_produto]);
        }
        header('Location: carrinho.php');
        exit();
    }

    // --- AÇÃO: LIMPAR CARRINHO ---
    elseif ($acao == 'limpar') {
        $_SESSION['carrinho'] = [];
        header('Location: carrinho.php');
        exit();
    }
}

// Se nenhuma ação válida for encontrada, redireciona para o carrinho para evitar erros.
header('Location: carrinho.php');
exit();
?>