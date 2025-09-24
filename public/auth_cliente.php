<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Usar prepared statements para segurança
    $stmt = $conexao->prepare("SELECT id, nome, senha, role FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar o hash da senha
        if (password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido, criar sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_role'] = $usuario['role'];

            // Redireciona para a página da conta ou para o checkout se ele estava a caminho
            header("Location: minha_conta.php");
            exit();
        }
    }
    
    // Se chegou aqui, o login falhou
    header("Location: login.php?erro=invalido");
    exit();
}
?>