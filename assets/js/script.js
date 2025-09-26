document.addEventListener("DOMContentLoaded", function() {
    
    // --- LÓGICA PARA ADICIONAR AO CARRINHO (AJAX) ---
    const addToCartForms = document.querySelectorAll('.form-add-to-cart');

    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartCounter = document.getElementById('cart-counter');
                    if (cartCounter) {
                        cartCounter.textContent = data.cart_count;
                    }
                    alert('Produto adicionado ao carrinho!');
                } else {
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
    const homeCarousel = document.getElementById('home-carousel');
    if (homeCarousel) {
        new Splide('#home-carousel', {
            type       : 'loop',
            autoplay   : true,
            interval   : 4000,
            pagination : true,
            arrows     : false,
            height     : '80vh',
            cover      : true,
        }).mount();
    }

    // --- LÓGICA PARA O CARROSSEL DA PÁGINA DE PRODUTO ---
    const productCarousel = document.getElementById('product-carousel');
    if (productCarousel) {
        new Splide('#product-carousel', {
            type       : 'fade',
            rewind     : true,
            pagination : true,
            arrows     : true,
        }).mount();
    }

    // --- LÓGICA PARA EDIÇÃO DE STATUS DO PEDIDO (ADMIN) ---
    const editButtons = document.querySelectorAll('.btn-edit-status');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const statusCell = this.closest('td');
            const viewMode = statusCell.querySelector('.status-view');
            const editModeForm = statusCell.querySelector('.form-status-edit');

            viewMode.classList.add('hidden');
            editModeForm.classList.remove('hidden');
        });
    });
});