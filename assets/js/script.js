document.addEventListener("DOMContentLoaded", function() {
    
    // Seleciona todos os formulários de adicionar ao carrinho
    const addToCartForms = document.querySelectorAll('.form-add-to-cart');

    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Previne o comportamento padrão do formulário (recarregar a página)
            event.preventDefault();

            // Pega os dados do formulário
            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');

            // Envia os dados para o servidor usando a API Fetch (AJAX)
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Converte a resposta do servidor para JSON
            .then(data => {
                if (data.success) {
                    // Se a operação foi bem-sucedida, atualiza o contador do carrinho no header
                    const cartCounter = document.getElementById('cart-counter');
                    if (cartCounter) {
                        cartCounter.textContent = data.cart_count;
                    }
                    alert('Produto adicionado ao carrinho!'); // Feedback para o usuário
                } else {
                    alert('Erro ao adicionar o produto.');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Ocorreu um erro de comunicação.');
            });
        });
    });
});

// Dentro de /assets/js/script.js
document.addEventListener("DOMContentLoaded", function() {
    
    // --- CÓDIGO EXISTENTE DE ADICIONAR AO CARRINHO ---
    const addToCartForms = document.querySelectorAll('.form-add-to-cart');
    // ... (mantenha todo o código do carrinho aqui)


    // --- ADICIONE O CÓDIGO NOVO DO CARROSSEL AQUI ---
    // Verifica se existe um elemento com a classe .splide na página
    if (document.querySelector('.splide')) {
        new Splide('.splide', {
            type       : 'loop',      // Faz o carrossel voltar ao início
            autoplay   : true,        // Inicia o carrossel automaticamente
            interval   : 4000,        // Muda de imagem a cada 4 segundos
            pagination : true,        // Mostra os pontinhos de navegação
            arrows     : false,       // Esconde as setas laterais
            height     : '80vh',      // Define a altura do carrossel
            cover      : true,        // Faz as imagens cobrirem todo o espaço do slide
        }).mount();
    }
});