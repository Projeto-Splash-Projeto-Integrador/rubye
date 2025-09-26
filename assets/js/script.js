document.addEventListener("DOMContentLoaded", function() {
    
    // --- LÓGICA PARA ADICIONAR AO CARRINHO (AJAX) ---
    const addToCartForms = document.querySelectorAll('.form-add-to-cart');

    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Previne o comportamento padrão do formulário (recarregar a página)
            event.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Primeiro, verifica se a resposta é um JSON válido
                if (!response.ok) {
                    throw new Error('A resposta do servidor não foi OK');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const cartCounter = document.getElementById('cart-counter');
                    if (cartCounter) {
                        cartCounter.textContent = data.cart_count;
                    }
                    alert('Produto adicionado ao carrinho!');
                } else {
                    // Mostra uma mensagem de erro mais específica, se o servidor a enviar
                    alert(data.message || 'Erro ao adicionar o produto.');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Ocorreu um erro de comunicação.');
            });
        });
    });

    // --- LÓGICA PARA O CARROSSEL DA PÁGINA INICIAL ---
    // Verifica se existe um elemento com a classe .splide na página
    if (document.querySelector('.splide')) {
        new Splide('.splide', {
            type       : 'loop',
            autoplay   : true,
            interval   : 4000,
            pagination : true,
            arrows     : false,
            height     : '80vh',
            cover      : true,
        }).mount();
    }
});

// --- LÓGICA PARA EDIÇÃO DE STATUS DO PEDIDO ---
document.addEventListener("DOMContentLoaded", function() {
    // Encontra todos os botões "Alterar"
    const editButtons = document.querySelectorAll('.btn-edit-status');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const statusCell = this.closest('td'); // A célula da tabela que contém tudo
            const viewMode = statusCell.querySelector('.status-view');
            const editModeForm = statusCell.querySelector('.form-status-edit');

            // Esconde o modo de visualização e mostra o formulário de edição
            viewMode.classList.add('hidden');
            editModeForm.classList.remove('hidden');
        });
    });
});