document.addEventListener('DOMContentLoaded', function() {
    // Determinar la URL base según donde estemos
    const isRemote = window.location.hostname.includes('remotehost.es');
    const baseUrl = isRemote ? 'https://remotehost.es/student025/shop' : '..';
    
    console.log('Usando base URL:', baseUrl);
    
    // Obtener el ID del producto de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if(!productId) {
        console.error('No se especificó ID de producto');
        document.querySelector('.nombreProducto').textContent = 'Error: Producto no encontrado';
        return;
    }
    
    // Cargar datos del producto
    fetch(`${baseUrl}/backend/ajax/get_product_detail.php?id=${productId}`)
        .then(response => {
            if(!response.ok) {
                throw new Error('Error al cargar el producto');
            }
            return response.json();
        })
        .then(product => {
            if(product.error) {
                throw new Error(product.error);
            }
            
            // Actualizar información del producto
            document.querySelector('.nombreProducto').textContent = product.name;
            document.querySelector('.descripcion').textContent = product.description;
            
            const precioValor = document.querySelector('.compra .precio-valor');
            if(precioValor) {
                precioValor.textContent = '€' + product.price;
            }
            
            const stockValor = document.querySelector('.compra .stock-valor');
            if(stockValor) {
                const stockText = product.stock > 0 ? 'Disponible' : 'Agotado';
                stockValor.textContent = stockText;
            }
            
            // Actualizar imagen principal
            const mainImage = document.querySelector('.imgPrincipal');
            if(mainImage && product.image) {
                mainImage.src = product.image;
                mainImage.alt = product.name;
            }
            
            // Actualizar imágenes secundarias
            const secondaryImages = document.querySelectorAll('.imgSecundaria');
            if(product.images && product.images.length > 0) {
                secondaryImages.forEach((img, index) => {
                    if(product.images[index]) {
                        img.src = product.images[index];
                        img.alt = `${product.name} - vista ${index + 1}`;
                    }
                });
            }
            
            // Configurar botón de compra para añadir sin redirección
            const buyButton = document.querySelector('.comprar');
            if(buyButton) {
                // Deshabilitar si no hay stock
                if(product.stock <= 0) {
                    buyButton.disabled = true;
                    buyButton.textContent = 'Sin stock';
                    buyButton.style.opacity = '0.5';
                    buyButton.style.cursor = 'not-allowed';
                } else {
                    buyButton.onclick = function(e) {
                        e.preventDefault();
                        addToCart(product.id, buyButton);
                    };
                }
            }
            
            // Cargar productos similares
            if(product.similar_products && product.similar_products.length > 0) {
                loadSimilarProducts(product.similar_products);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.nombreProducto').textContent = 'Error al cargar el producto';
            document.querySelector('.descripcion').textContent = error.message;
        });
});

// Función para añadir producto al carrito sin redirección
function addToCart(productId, button) {
    console.log('Iniciando addToCart con productId:', productId);
    
    // Determinar la URL base según donde estemos
    const isRemote = window.location.hostname.includes('remotehost.es');
    const baseUrl = isRemote ? 'https://remotehost.es/student025/shop' : '..';
    
    // Guardar texto original del botón
    const originalText = button.textContent;
    
    // Mostrar feedback visual
    button.disabled = true;
    button.textContent = 'Añadiendo...';
    
    // Crear FormData para enviar datos
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('action', 'add');
    
    console.log('Enviando petición a update_cart.php');
    
    // Hacer petición para añadir al carrito usando el endpoint AJAX
    fetch(`${baseUrl}/backend/ajax/update_cart.php`, {
        method: 'POST',
        body: formData
    })
        .then(response => {
            console.log('Respuesta recibida:', response.status, response.statusText);
            if(!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.text();
        })
        .then(text => {
            console.log('Texto de respuesta:', text);
            try {
                const data = JSON.parse(text);
                console.log('JSON parseado:', data);
                
                if(data.success) {
                    // Mostrar mensaje de éxito
                    button.textContent = '✓ Añadido';
                    button.style.backgroundColor = '#4CAF50';
                    
                    // Actualizar contador del carrito con el valor del servidor
                    updateCartCount(data.total_items);
                    
                    console.log('Producto añadido exitosamente');
                    
                    // Restaurar botón después de 2 segundos
                    setTimeout(() => {
                        button.disabled = false;
                        button.textContent = originalText;
                        button.style.backgroundColor = '';
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Error al añadir al carrito');
                }
            } catch(e) {
                console.error('Error parseando JSON:', e);
                throw new Error('Respuesta inválida del servidor: ' + text.substring(0, 100));
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            
            // Mostrar mensaje de error
            button.textContent = '✗ Error';
            button.style.backgroundColor = '#f44336';
            
            // Restaurar botón después de 2 segundos
            setTimeout(() => {
                button.disabled = false;
                button.textContent = originalText;
                button.style.backgroundColor = '';
            }, 2000);
        });
}

// Función para actualizar contador del carrito
function updateCartCount(totalItems) {
    const cartCountElement = document.querySelector('.items-carrito');
    if(cartCountElement && totalItems !== undefined) {
        cartCountElement.textContent = totalItems;
    }
}

function loadSimilarProducts(products) {
    const container = document.querySelector('.productosSimilares-wrapper .grid');
    if(!container) return;
    
    // Limpiar contenido actual
    container.innerHTML = '';
    
    // Agregar productos similares
    products.forEach((product, index) => {
        const productDiv = document.createElement('div');
        productDiv.className = 'bg-white rounded-lg shadow p-4 border border-gray-200';
        
        // Usar imagen de la base de datos o placeholder
        const productImage = product.image_data ? product.image_data : '../assets/img/ph.jpg';
        
        productDiv.innerHTML = `
            <a href="producto.html?id=${product.id}">
                <img
                    src="${productImage}"
                    alt="${product.name}"
                    class="w-full h-32 object-cover rounded mb-2"
                />
                <p class="text-sm font-semibold">${product.name}</p>
                <p class="text-sm text-gray-600">€${product.price}</p>
            </a>
        `;
        
        container.appendChild(productDiv);
    });
}