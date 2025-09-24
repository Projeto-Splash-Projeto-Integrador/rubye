<?php
// Inicia a sessão para poder acessá-la
session_start();

// Remove todas as variáveis da sessão
$_SESSION = array();

// Destrói a sessão
session_destroy();

// Redireciona o usuário para a página inicial
header("Location: index.php");
exit();
?>