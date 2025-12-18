document.addEventListener('DOMContentLoaded', function() {
    const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    const baseUrl = isLocal ? '..' : 'https://remotehost.es/student025/shop';
    
    // PRIMERO: Crear/verificar sesión, LUEGO: cargar carrito
    initializeSessionThenLoadCart();
    
    async function initializeSessionThenLoadCart() {
        try {
            // Crear sesión de invitado si no existe
            const sessionResponse = await fetch(`${baseUrl}/backend/ajax/create_guest_session.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            const sessionData = await sessionResponse.json();
            
            if(sessionData.success) {
                // Ahora que tenemos sesión, cargar el carrito
                loadCartPage();
            } else {
                showEmptyCart('Error al iniciar sesión');
            }
        } catch(error) {
            console.error('Error:', error);
            showEmptyCart('Error de conexión');
        }
    }
    
    function loadCartPage() {
        fetch(`${baseUrl}/backend/ajax/get_cart.php`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(data.items.length === 0) {
                        showEmptyCart('Tu carrito está vacío');
                    } else {
                        displayCartItems(data.items, data.total, data.total_items);
                        loadRecommendations();
                    }
                } else {
                    showEmptyCart(data.message || 'Error al cargar el carrito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showEmptyCart('Error al cargar el carrito');
            });
    }
    
    function displayCartItems(items, total, totalItems) {
        const container = document.querySelector('.productos-carrito');
        
        if(!container) {
            return;
        }
        
        container.innerHTML = '';
        
        items.forEach(item => {
            const productDiv = document.createElement('div');
            productDiv.className = 'producto-item';
            productDiv.setAttribute('data-product-id', item.product_id);
            
            const imageSrc = item.image_data || '../assets/img/ph.jpg';
            
            productDiv.innerHTML = `
                <div class="producto-imagen">
                    <img src="${imageSrc}" alt="${item.name}" />
                </div>
                <div class="producto-info">
                    <h3>${item.name}</h3>
                    <p class="producto-descripcion">${item.description}</p>
                    <p class="producto-cantidad">Cantidad: ${item.quantity}</p>
                    <p class="producto-precio">€${item.price} c/u</p>
                    <p class="producto-subtotal">Subtotal: €${item.subtotal}</p>
                </div>
                <div class="producto-controles">
                    <button class="btn-control btn-add" data-product-id="${item.product_id}">+</button>
                    <button class="btn-control btn-remove" data-product-id="${item.product_id}">−</button>
                </div>
            `;
            
            container.appendChild(productDiv);
        });
        
        updateSummary(total, totalItems);
        addButtonListeners();
    }
    
    function updateSummary(total, totalItems) {
        const confirmacionBox = document.querySelector('.confirmacion-box');
        
        if(confirmacionBox) {
            confirmacionBox.innerHTML = `
                <h3>Resumen de compra</h3>
                <div class="resumen-detalle">
                    <p>Productos: ${totalItems}</p>
                    <p class="total-precio">Total: €${total}</p>
                </div>
                <button class="btn-checkout btn-checkout-disabled" disabled>Proceder al pago</button>
            `;
        }
    }
    
    function showEmptyCart(message) {
        const container = document.querySelector('.productos-carrito');
        if(container) {
            container.innerHTML = `
                <div class="carrito-vacio">
                    <p>${message}</p>
                    <a href="${baseUrl}/index.html" class="btn-volver">Ver productos</a>
                </div>
            `;
        }
        
        const confirmacionBox = document.querySelector('.confirmacion-box');
        if(confirmacionBox) {
            confirmacionBox.innerHTML = '<h3>Tu carrito está vacío</h3>';
        }
        
        const cartCount = document.querySelector('.items-carrito');
        if(cartCount) {
            cartCount.textContent = '0';
        }
    }
    
    function addButtonListeners() {
        document.querySelectorAll('.btn-add').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                updateCartQuantity(productId, 'add');
            });
        });
        
        document.querySelectorAll('.btn-remove').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                updateCartQuantity(productId, 'remove');
            });
        });
    }
    
    function updateCartQuantity(productId, action) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('action', action);
        
        fetch(`${baseUrl}/backend/ajax/update_cart.php`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    loadCartPage();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    function loadRecommendations() {
        fetch(`${baseUrl}/backend/ajax/get_products.php`)
            .then(response => response.json())
            .then(products => {
                displayRecommendations(products.slice(0, 4));
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    function displayRecommendations(products) {
        const recomendacionesBox = document.querySelector('.recomendaciones-box');
        
        if(!recomendacionesBox || products.length === 0) {
            return;
        }
        
        let html = '<h3>Recomendaciones</h3><div class="recomendaciones-grid">';
        
        products.forEach(product => {
            const imageSrc = product.image_data || '../assets/img/ph.jpg';
            
            html += `
                <div class="recomendacion-item">
                    <a href="producto.html?id=${product.id}">
                        <img src="${imageSrc}" alt="${product.name}" />
                        <p class="rec-nombre">${product.name}</p>
                        <p class="rec-precio">€${product.price}</p>
                    </a>
                </div>
            `;
        });
        
        html += '</div>';
        recomendacionesBox.innerHTML = html;
    }
});