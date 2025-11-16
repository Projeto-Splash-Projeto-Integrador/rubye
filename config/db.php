<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('DB_SERVER', 'mysql-rubye-theylorantunescruz-060f.f.aivencloud.com');        
define('DB_USERNAME', 'avnadmin');   
define('DB_PASSWORD', 'AVNS_79w5GkozAwoMd8tDDq0'); 
define('DB_NAME', 'rubye_db');    
define('DB_PORT', '19977');          


define('SSL_CA_PATH', __DIR__ . '/ca.pem');


$conexao = mysqli_init();
if (!$conexao) {
    die("Falha ao inicializar o MySQLi.");
}


mysqli_ssl_set($conexao, NULL, NULL, SSL_CA_PATH, NULL, NULL);

if (!mysqli_real_connect($conexao, DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT)) {
    error_log("Falha na conexão com o MySQL (Aiven): " . mysqli_connect_error());
    die("Ocorreu um erro de conexão. Por favor, tente mais tarde.");
}


$conexao->set_charset("utf8mb4");

