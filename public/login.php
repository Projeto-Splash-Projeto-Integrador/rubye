<?php include 'partials/header.php'; ?>

<div class="login-container">
    <h2>Acesse sua Conta</h2>
    
    <?php
    if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'registrado') {
        echo '<p class="success">Cadastro realizado com sucesso! Faça seu login.</p>';
    }
    if (isset($_GET['erro'])) {
        $erro = $_GET['erro'];
        $mensagem = '';
        if ($erro == 'invalido') $mensagem = 'E-mail ou senha inválidos.';
        if ($erro == 'login_necessario') $mensagem = 'Você precisa fazer login para continuar.';
        echo '<p class="error">' . $mensagem . '</p>';
    }
    ?>

    <form action="auth_cliente.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="registro.php">Registre-se aqui</a>.</p>
</div>

<?php include 'partials/footer.php'; ?>