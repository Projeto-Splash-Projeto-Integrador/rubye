<?php
// ===================================================================
// FERRAMENTA PARA GERAR HASH DE SENHA SEGURO
// ===================================================================

// PASSO 1: Defina a senha que você quer usar aqui dentro das aspas.
// Use algo seguro que você se vai lembrar.
$minhaSenha = 'zQr@tt9_';

// PASSO 2: Não precisa de alterar mais nada.
// Este código vai gerar o hash criptografado para a senha acima.
$hash = password_hash($minhaSenha, PASSWORD_DEFAULT);

// PASSO 3: O resultado será exibido na tela para você copiar.
echo "<!DOCTYPE html>";
echo "<html lang='pt-br'>";
echo "<head><title>Gerador de Hash</title><style>body{font-family: sans-serif; padding: 20px;} textarea{width: 100%; height: 80px; font-size: 1.2em; padding: 10px; box-sizing: border-box; margin-top: 10px; border: 2px solid #007bff;}</style></head>";
echo "<body>";
echo "<h1>Gerador de Hash de Senha</h1>";
echo "<p>A senha definida no ficheiro foi: <strong>" . htmlspecialchars($minhaSenha) . "</strong></p>";
echo "<hr>";
echo "<h3>Copie o hash completo abaixo e cole no campo 'senha' do seu utilizador admin no phpMyAdmin:</h3>";
echo "<textarea readonly onclick='this.select()'>" . $hash . "</textarea>";
echo "</body>";
echo "</html>";

?>