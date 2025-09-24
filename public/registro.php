<?php include 'partials/header.php'; ?>

<div class="login-container">
    <h2>Crie sua Conta</h2>
    
    <?php
    if (isset($_GET['erro'])) {
        $erro = $_GET['erro'];
        $mensagem = '';
        switch ($erro) {
            case 'campos_vazios': $mensagem = 'Por favor, preencha todos os campos.'; break;
            case 'senhas_diferentes': $mensagem = 'As senhas não coincidem.'; break;
            case 'email_existente': $mensagem = 'Este e-mail já está cadastrado.'; break;
            case 'db_error': $mensagem = 'Ocorreu um erro ao criar sua conta. Tente novamente.'; break;
        }
        echo '<p class="error">' . $mensagem . '</p>';
    }
    ?>

    <form action="acoes_usuario.php" method="POST">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <label for="confirmar_senha">Confirmar Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" required>

        <button type="submit">Registrar</button>
    </form>
    <p>Já tem uma conta? <a href="login.php">Faça login aqui</a>.</p>
</div>

<?php include 'partials/footer.php'; ?>