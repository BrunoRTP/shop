document.addEventListener('DOMContentLoaded', function() {
    // Obtener el ID del producto de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if(!productId) {
        console.error('No se especificó ID de producto');
        document.querySelector('.nombreProducto').textContent = 'Error: Producto no encontrado';
        return;
    }
    
    // Cargar datos del producto
    fetch(`/student025/shop/backend/ajax/get_product_detail.php?id=${productId}`)
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
            
            // Actualizar botón de compra con ID del producto
            const buyButton = document.querySelector('.comprar');
            if(buyButton) {
                buyButton.onclick = function() {
                    window.location.href = `/student025/shop/backend/db/db_cart_insert.php?id=${product.id}`;
                };
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

function loadSimilarProducts(products) {
    const container = document.querySelector('.productosSimilares-wrapper .grid');
    if(!container) return;
    
    // Limpiar contenido actual
    container.innerHTML = '';
    
    // Mapeo de imágenes para productos similares
    const imageMap = {
        'Mesa de Comedor Roble': '../assets/img/lamparaModerna.png',
        'Lámpara minimalista': '../assets/img/lamparaMinimalista.png',
        'Lámpara de techo': '../assets/img/lamparaTecho.png',
        'Foco ajustable': '../assets/img/lamparaReajustable.png'
    };
    
    // Agregar productos similares
    products.forEach((product, index) => {
        const productDiv = document.createElement('div');
        productDiv.className = 'bg-white rounded-lg shadow p-4 border border-gray-200';
        
        // Determinar imagen del producto
        let productImage = '../assets/img/ph.jpg';
        if(imageMap[product.name]) {
            productImage = imageMap[product.name];
        }
        
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