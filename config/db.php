<?php
// Garante que a sessão só é iniciada uma vez.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =========================================================
// == NOVAS CONFIGURAÇÕES APONTANDO PARA O AIVEN ==
// =========================================================

// Substitua pelos seus dados do painel Aiven
define('DB_SERVER', 'mysql-rubye-theylorantunescruz-060f.f.aivencloud.com');         // ex: 'mysql-seu-projeto.aivencloud.com'
define('DB_USERNAME', 'avnadmin');     // ex: 'avnadmin'
define('DB_PASSWORD', 'AVNS_79w5GkozAwoMd8tDDq0');       // ex: 'a1b2c3d4e5'
define('DB_NAME', 'rubye_db');       // ex: 'defaultdb'
define('DB_PORT', '19977');           // ex: 13306

// Caminho para o certificado SSL que você baixou do Aiven
define('SSL_CA_PATH', __DIR__ . '/ca.pem');

// =========================================================
// == NOVA LÓGICA DE CONEXÃO COM SSL (OBRIGATÓRIO) ==
// =========================================================

// Criar a conexão usando SSL
$conexao = mysqli_init();
if (!$conexao) {
    die("Falha ao inicializar o MySQLi.");
}

// Define as opções de SSL
mysqli_ssl_set($conexao, NULL, NULL, SSL_CA_PATH, NULL, NULL);

// Tenta a conexão real
if (!mysqli_real_connect($conexao, DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT)) {
    error_log("Falha na conexão com o MySQL (Aiven): " . mysqli_connect_error());
    die("Ocorreu um erro de conexão. Por favor, tente mais tarde.");
}

// Definir o charset para UTF-8
$conexao->set_charset("utf8mb4");

// O resto do seu arquivo (se houver algo) continuaria aqui...
// O '}' extra no seu arquivo original parecia ser um erro de digitação, removi.

