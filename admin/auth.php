<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$email = $_POST['email'];
$senha_digitada = $_POST['senha'];

$stmt = $conexao->prepare("SELECT id, nome, senha, role FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    header("Location: login.php?erro=1");
    exit();
}

$usuario = $resultado->fetch_assoc();

if ($usuario['role'] !== 'admin') {
    header("Location: login.php?erro=1"); 
    exit();
}

if (password_verify($senha_digitada, $usuario['senha'])) {
    
  
    session_regenerate_id(true); 
    
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_role'] = $usuario['role'];

    session_write_close(); 
    
    header("Location: index.php"); 
    exit();
} else {
    header("Location: login.php?erro=1");
    exit();
}