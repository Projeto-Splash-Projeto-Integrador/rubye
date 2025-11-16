<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];


    if (empty($nome) || empty($email) || empty($senha)) {
        header("Location: registro.php?erro=campos_vazios");
        exit();
    }

    if ($senha !== $confirmar_senha) {
        header("Location: registro.php?erro=senhas_diferentes");
        exit();
    }
    

    $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        header("Location: registro.php?erro=email_existente");
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);


    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt->execute()) {

        header("Location: login.php?sucesso=registrado");
    } else {
        header("Location: registro.php?erro=db_error");
    }

    exit();
}
?>