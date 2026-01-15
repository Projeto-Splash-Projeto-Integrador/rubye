<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$db_server   = getenv('DB_SERVER');
$db_username = getenv('DB_USERNAME');
$db_password = getenv('DB_PASSWORD');
$db_name     = getenv('DB_NAME');
$db_port     = getenv('DB_PORT');
$ssl_ca_path = __DIR__ . '/ca.pem'; 

if (!$db_server) {
    die("Erro: Variáveis de ambiente do banco de dados não configuradas.");
}

$conexao = mysqli_init();
if (!$conexao) {
    die("Falha ao inicializar o MySQLi.");
}

mysqli_ssl_set($conexao, NULL, NULL, $ssl_ca_path, NULL, NULL);

if (!mysqli_real_connect($conexao, $db_server, $db_username, $db_password, $db_name, (int)$db_port)) {
    error_log("Falha na conexão com o MySQL (Aiven): " . mysqli_connect_error());
    die("Ocorreu um erro de conexão com o banco de dados.");
}

$conexao->set_charset("utf8mb4");
?>