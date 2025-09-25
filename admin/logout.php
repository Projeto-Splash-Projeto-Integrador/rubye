<?php
// Inclui a configuração que já inicia a sessão de forma segura
require_once '../config/db.php';

// Remove todas as variáveis da sessão
$_SESSION = array();

// Destrói a sessão
session_destroy();

// Redireciona o utilizador para a página inicial
header("Location: index.php");
exit();
?>