<?php include 'partials/header.php'; ?>

<div class="contact-page">
    <div class="contact-header">
        <h2>Fale Conosco</h2>
        <p>Estamos aqui para ajudar você com qualquer dúvida sobre seu estilo.</p>
    </div>

    <div class="contact-container">
        <div class="contact-info">
            <h3>Canais de Atendimento</h3>
            <p>Precisa de suporte rápido? Chame a gente nas redes ou mande um e-mail.</p>
            
            <div class="info-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="details">
                    <span>Email</span>
                    <a href="mailto:email@rubye.com">email@rubye.com</a>
                </div>
            </div>

            <div class="info-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.05 12.05 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.03 12.03 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <div class="details">
                    <span>Telefone</span>
                    <a href="tel:+5511999999999">+55 (11) 99999-9999</a>
                </div>
            </div>

            <div class="info-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                </div>
                <div class="details">
                    <span>WhatsApp</span>
                    <a href="#">Iniciar conversa</a>
                </div>
            </div>

            <div class="social-links">
                <p>Siga a RUBYE:</p>
                <a href="#" class="social-btn">Instagram</a>
                <a href="#" class="social-btn">TikTok</a>
            </div>
        </div>

        <div class="contact-form-wrapper">
            <h3>Envie uma Mensagem</h3>
            <form action="#" method="POST"> <div class="form-group">
                    <label for="nome">Seu Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Como devemos te chamar?" required>
                </div>

                <div class="form-group">
                    <label for="email">Seu Email</label>
                    <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                </div>

                <div class="form-group">
                    <label for="mensagem">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="5" placeholder="Escreva sua dúvida ou sugestão aqui..." required></textarea>
                </div>

                <button type="submit" class="btn-primary btn-full">Enviar Mensagem</button>
            </form>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>