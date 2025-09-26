<?php
// Garante que a sessão só é iniciada uma vez.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definições do Banco de Dados
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'P@$$w0rd');
define('DB_NAME', 'rubye_db');

// Criar a conexão
$conexao = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Checar a conexão
if ($conexao->connect_error) {
    error_log("Falha na conexão com o MySQL: " . $conexao->connect_error);
    die("Ocorreu um erro de conexão. Por favor, tente mais tarde.");
}

// Definir o charset para UTF-8
$conexao->set_charset("utf8mb4");