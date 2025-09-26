<?php include 'partials/header.php'; ?>

<div class="page-container">
    <h2 class="section-title">Entre em Contato</h2>
    
    <div class="contact-layout">
        <div class="contact-info">
            <h3>Informações</h3>
            <p>Tem alguma dúvida, sugestão ou proposta comercial? Use os canais abaixo ou preencha o formulário ao lado.</p>
            <ul>
                <li><i class="fa-solid fa-envelope"></i> email@rubye.com</li>
                <li><i class="fa-solid fa-phone"></i> +55 (11) 99999-9999</li>
                <li><i class="fa-brands fa-whatsapp"></i> WhatsApp Business</li>
            </ul>
            <div class="contact-social">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>
        
        <div class="contact-form">
            <h3>Envie uma Mensagem</h3>
            <form action="#"> <label for="nome">Seu Nome</label>
                <input type="text" id="nome" name="nome" required>
                
                <label for="email">Seu Email</label>
                <input type="email" id="email" name="email" required>
                
                <label for="mensagem">Mensagem</label>
                <textarea id="mensagem" name="mensagem" rows="6" required></textarea>
                
                <button type="submit" class="btn-primary">Enviar</button>
                <p><small>*A lógica de envio de email para este formulário não está implementada.</small></p>
            </form>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>