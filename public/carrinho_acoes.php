<?php
// Silencia todos os erros, avisos e notificações para garantir uma resposta JSON limpa.
error_reporting(0);
ini_set('display_errors', 0);

require_once '../config/db.php';

// Inicializa o carrinho na sessão se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Função para enviar resposta JSON e terminar o script
function json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    // --- AÇÃO: ADICIONAR ITEM AO CARRINHO ---
    if ($acao == 'adicionar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['id_produto'])) {
            json_response(['success' => false, 'message' => 'ID do produto não recebido.']);
        }

        $id_produto = (int)$_POST['id_produto'];
        $quantidade = (int)$_POST['quantidade'];

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
    
    // --- AÇÃO: REMOVER ITEM DO CARRINHO (CÓDIGO CORRIGIDO) ---
    elseif ($acao == 'remover' && isset($_GET['id'])) {
        $id_produto = (int)$_GET['id'];
        if (isset($_SESSION['carrinho'][$id_produto])) {
            unset($_SESSION['carrinho'][$id_produto]);
        }
        header('Location: carrinho.php');
        exit();
    }

    // --- AÇÃO: LIMPAR CARRINHO (CÓDIGO CORRIGIDO) ---
    elseif ($acao == 'limpar') {
        $_SESSION['carrinho'] = [];
        header('Location: carrinho.php');
        exit();
    }
}

// Se nenhuma ação válida for encontrada, redireciona para o carrinho para evitar a tela branca.
header('Location: carrinho.php');
exit();
?>