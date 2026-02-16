document.addEventListener('DOMContentLoaded', function() {
    // Determinar la URL base: localhost usa rutas relativas, todo lo demás usa remotehost
    const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    const baseUrl = isLocal ? '.' : 'https://remotehost.es/student025/shop';
    
    fetch(`${baseUrl}/backend/ajax/get_products.php`)
        .then(response => response.json())
        .then(products => {
            if(products.length > 0) {
                loadHeroProduct(products[0]);
                loadOtherProducts(products.slice(1));
            }
        })
        .catch(error => console.error('Error:', error));
    
    function loadHeroProduct(product) {
        const heroImage = document.querySelector('.imagen-hero');
        const heroTitle = document.querySelector('.titulo-hero');
        const heroSubtitle = document.querySelector('.subtitulo-hero');
        const heroPrice = document.querySelector('.precio-hero');
        const heroButton = document.querySelector('.boton-cta-hero');
        
        // Cargar imagen de la base de datos o placeholder
        if(product.image_data) {
            heroImage.src = product.image_data;
        } else {
            heroImage.src = 'assets/img/ph.jpg';
        }
        
        heroTitle.textContent = product.name;
        heroSubtitle.textContent = product.description;
        heroPrice.textContent = '€' + product.price;
        heroButton.onclick = function() {
            window.location.href = 'views/producto.html?id=' + product.id;
        };
        heroButton.onkeypress = function(event) {
            if(event.key === 'Enter') {
                window.location.href = 'views/producto.html?id=' + product.id;
            }
        };
    }
    
    function loadOtherProducts(products) {
        const container = document.querySelector('.productos');
        container.innerHTML = '';
        
        products.forEach(product => {
            // Determinar la imagen a usar
            const imageSrc = product.image_data ? product.image_data : 'assets/img/ph.jpg';
            
            container.innerHTML += `
                <article class="zona-producto">
                    <div class="contenedor-imagen-producto">
                        <img src="${imageSrc}" alt="${product.name}" class="imagen-producto">
                        <button class="boton-favorito">
                            <img src="assets/iconos/corazon.png" alt="Favorito" class="icono">
                        </button>
                    </div>
                    <div class="informacion-producto">
                        <h3 class="titulo-producto">${product.name}</h3>
                        <p class="precio-producto">€${product.price}</p>
                        <button class="anadir-carrito-btn" onclick="window.location.href='views/producto.html?id=${product.id}'" onkeypress="if(event.key === 'Enter') window.location.href='views/producto.html?id=${product.id}'">
                            <p>Ver Detalles</p>
                        </button>
                    </div>
                </article>
            `;
        });
    }
});