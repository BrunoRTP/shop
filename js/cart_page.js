// js/cart_page.js
document.addEventListener('DOMContentLoaded', function() {
    // Determinar la URL base según donde estemos
    const isRemote = window.location.hostname.includes('remotehost.es');
    const baseUrl = isRemote ? 'https://remotehost.es/student025/shop' : '..';
    
    console.log('Cargando carrito desde:', baseUrl);
    
    loadCartPage();
    
    // Función para cargar la página del carrito
    function loadCartPage() {
        console.log('Intentando cargar carrito desde:', `${baseUrl}/backend/ajax/get_cart.php`);
        
        fetch(`${baseUrl}/backend/ajax/get_cart.php`)
            .then(response => {
                console.log('Respuesta recibida:', response.status, response.statusText);
                if(!response.ok) {
                    throw new Error('Error al cargar el carrito: ' + response.status);
                }
                return response.text();
            })
            .then(text => {
                console.log('Texto de respuesta:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Datos del carrito parseados:', data);
                    
                    if(data.success) {
                        displayCartItems(data.items, data.total, data.total_items);
                        loadRecommendations();
                    } else {
                        console.warn('Carrito vacío o error:', data.message);
                        showEmptyCart(data.message);
                    }
                } catch(e) {
                    console.error('Error parseando JSON:', e);
                    console.error('Texto recibido:', text.substring(0, 500));
                    showEmptyCart('Error al procesar datos del carrito');
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                showEmptyCart('Error al cargar el carrito: ' + error.message);
            });
    }
    
    // Función para mostrar los productos del carrito
    function displayCartItems(items, total, totalItems) {
        const container = document.querySelector('.productos-carrito');
        
        if(!container) {
            console.error('No se encontró el contenedor de productos');
            return;
        }
        
        // Si no hay items, mostrar mensaje
        if(items.length === 0) {
            showEmptyCart('Tu carrito está vacío');
            return;
        }
        
        // Limpiar contenedor
        container.innerHTML = '';
        
        // Agregar cada producto
        items.forEach(item => {
            const productDiv = document.createElement('div');
            productDiv.className = 'producto-item';
            productDiv.setAttribute('data-product-id', item.product_id);
            
            // Imagen (placeholder si no hay)
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
        
        // Actualizar total en el resumen
        updateSummary(total, totalItems);
        
        // Agregar event listeners a los botones usando la funcionalidad existente
        addButtonListeners();
    }
    
    // Función para actualizar el resumen de compra
    function updateSummary(total, totalItems) {
        const confirmacionBox = document.querySelector('.confirmacion-box');
        
        if(confirmacionBox) {
            confirmacionBox.innerHTML = `
                <h3>Resumen de compra</h3>
                <div class="resumen-detalle">
                    <p>Productos: ${totalItems}</p>
                    <p class="total-precio">Total: €${total}</p>
                </div>
                <button class="btn-checkout">Proceder al pago</button>
            `;
            
            // Agregar listener al botón de checkout
            const checkoutBtn = confirmacionBox.querySelector('.btn-checkout');
            if(checkoutBtn) {
                checkoutBtn.addEventListener('click', function() {
                    window.location.href = `${baseUrl}/backend/db/db_cart_checkout.php`;
                });
            }
        }
    }
    
    // Función para mostrar carrito vacío
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
        
        // Limpiar resumen
        const confirmacionBox = document.querySelector('.confirmacion-box');
        if(confirmacionBox) {
            confirmacionBox.innerHTML = '<h3>Tu carrito está vacío</h3>';
        }
        
        // Actualizar contador del header a 0
        const cartCount = document.querySelector('.items-carrito');
        if(cartCount) {
            cartCount.textContent = '0';
        }
    }
    
    // Función para agregar listeners a los botones
    // Usa el endpoint que ya existe en update_cart.php
    function addButtonListeners() {
        // Botones de añadir
        document.querySelectorAll('.btn-add').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                updateCartQuantity(productId, 'add');
            });
        });
        
        // Botones de eliminar
        document.querySelectorAll('.btn-remove').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                updateCartQuantity(productId, 'remove');
            });
        });
    }
    
    // Función para actualizar cantidad en el carrito
    // Reutiliza el endpoint existente
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
                    // Recargar toda la página del carrito para reflejar cambios
                    loadCartPage();
                } else {
                    console.error('Error al actualizar:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    // Función para cargar recomendaciones
    function loadRecommendations() {
        fetch(`${baseUrl}/backend/ajax/get_products.php`)
            .then(response => response.json())
            .then(products => {
                // Mostrar solo los primeros 4 productos como recomendaciones
                displayRecommendations(products.slice(0, 4));
            })
            .catch(error => {
                console.error('Error cargando recomendaciones:', error);
            });
    }
    
    // Función para mostrar recomendaciones
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