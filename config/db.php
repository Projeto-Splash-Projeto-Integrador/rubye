<?php

if (session_status() == PHP_SESSION_NONE) {
    if (getenv('VERCEL') || getenv('AWS_LAMBDA_RUNTIME_API')) {
        
        session_save_path('/tmp');
    }

    session_start();
}


$db_server   = getenv('DB_SERVER');
$db_username = getenv('DB_USERNAME');
$db_password = getenv('DB_PASSWORD');
$db_name     = getenv('DB_NAME');
$db_port     = getenv('DB_PORT');


$ssl_ca_path = __DIR__ . '/ca.pem';


if (!$db_server) {
    $db_server = '127.0.0.1';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'rubye_db';
    $db_port = 3306;
}


$conexao = mysqli_init();
if (!$conexao) {
    die("Falha ao inicializar MySQLi");
}


if (file_exists($ssl_ca_path) && getenv('DB_SERVER')) {
    mysqli_ssl_set($conexao, NULL, NULL, $ssl_ca_path, NULL, NULL);
}


if (!mysqli_real_connect($conexao, $db_server, $db_username, $db_password, $db_name, (int)$db_port)) {
    
    die("Erro de conexão com o banco de dados.");
}

$conexao->set_charset("utf8mb4");
