<?php
// 1. Inclui o arquivo de configuração para conectar ao banco de dados
// O caminho '../config/db.php' significa "volte uma pasta e entre na pasta config"
require_once '../config/db.php';

// 2. Verifica se a requisição foi feita usando o método POST (ou seja, se o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 3. Coleta os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // 4. Bloco de Validações Iniciais
    // --------------------------------------------------------------------------

    // Verifica se algum campo essencial está vazio
    if (empty($nome) || empty($email) || empty($senha)) {
        // Se estiver vazio, redireciona de volta para a página de registro com uma mensagem de erro
        header("Location: registro.php?erro=campos_vazios");
        exit(); // Encerra o script para não executar o resto do código
    }

    // Verifica se a senha e a confirmação de senha são diferentes
    if ($senha !== $confirmar_senha) {
        header("Location: registro.php?erro=senhas_diferentes");
        exit();
    }
    
    // 5. Validação no Banco de Dados: Verificar se o e-mail já existe
    // --------------------------------------------------------------------------
    
    // Prepara uma consulta SQL para evitar injeção de SQL (mais seguro)
    $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' significa que a variável é uma string
    $stmt->execute();
    
    // Pega o resultado da consulta
    $resultado = $stmt->get_result();

    // Se o número de linhas encontradas for maior que 0, significa que o e-mail já existe
    if ($resultado->num_rows > 0) {
        header("Location: registro.php?erro=email_existente");
        exit();
    }
    $stmt->close(); // Fecha a consulta anterior

    // 6. Segurança: Criptografar a senha antes de salvar
    // --------------------------------------------------------------------------
    // NUNCA salve senhas em texto puro no banco de dados!
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 7. Inserir o novo usuário no Banco de Dados
    // --------------------------------------------------------------------------

    // Prepara a consulta de inserção
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, role) VALUES (?, ?, ?, 'cliente')");
    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        // 8. Redirecionamento Final: Sucesso
        // Se o usuário foi criado, redireciona para a página de login com uma mensagem de sucesso
        header("Location: login.php?sucesso=registrado");
    } else {
        // 8. Redirecionamento Final: Erro
        // Se deu algum erro no banco, redireciona para o registro com uma mensagem de erro genérica
        header("Location: registro.php?erro=db_error");
    }
    
    $stmt->close(); // Fecha a consulta
    $conexao->close(); // Fecha a conexão com o banco
    exit();

} else {
    // Se alguém tentar acessar este arquivo diretamente sem enviar o formulário, redireciona para a home
    header("Location: index.php");
    exit();
}
?>