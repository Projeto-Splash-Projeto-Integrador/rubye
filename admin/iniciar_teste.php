<?php
// Inclui o mesmo ficheiro de configuração que o sistema de login usa.
require_once '../config/db.php';

echo "<h1>Teste Final - Parte 1</h1>";

// Vamos criar manualmente uma sessão de admin válida.
$_SESSION['usuario_id'] = 99;
$_SESSION['usuario_nome'] = 'Utilizador de Teste';
$_SESSION['usuario_role'] = 'admin';

echo "<p>Foi criada uma sessão de teste com os seguintes dados:</p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<hr>";
echo "<h3>Agora, vamos ver se esta sessão sobrevive até à próxima página.</h3>";
echo "<h2><a href='index.php'>Clique aqui para tentar entrar no Painel</a></h2>";
?>