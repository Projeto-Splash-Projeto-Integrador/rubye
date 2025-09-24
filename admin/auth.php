<?php
require_once '../config/db.php';

// Limpa qualquer sessão antiga para começar do zero.
session_unset();
session_destroy();
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Acesso inválido.");
}

$email = $_POST['email'];
$senha_digitada = $_POST['senha'];

// 1. Tenta encontrar o utilizador
$stmt = $conexao->prepare("SELECT id, nome, senha, role FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    die("FALHA CRÍTICA: Nenhum utilizador encontrado com o email '" . htmlspecialchars($email) . "'. Verifique o email no phpMyAdmin.");
}

$usuario = $resultado->fetch_assoc();
$senha_hash_db = $usuario['senha'];

// 2. Verifica o 'role'
if ($usuario['role'] !== 'admin') {
    die("FALHA CRÍTICA: O utilizador com o email '" . htmlspecialchars($email) . "' foi encontrado, mas o seu 'role' é '" . $usuario['role'] . "', e não 'admin'. Corrija no phpMyAdmin.");
}

// 3. Verifica a senha
if (password_verify($senha_digitada, $senha_hash_db)) {
    // SUCESSO!
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_role'] = $usuario['role'];
    header("Location: index.php"); // Redireciona para o painel
    exit();
} else {
    // FALHA
    die("FALHA CRÍTICA: A senha digitada não corresponde à senha guardada no banco de dados. Use o 'gerar_hash.php' para criar uma nova senha e atualize no phpMyAdmin.");
}