document.addEventListener('DOMContentLoaded', function() {
    
    fetch('/student025/shop/backend/ajax/get_products.php')
        .then(response => response.json())
        .then(products => {
            if(products.length > 0) {
                loadHeroProduct(products[0]);
                loadOtherProducts(products.slice(1));
            }
        })
        .catch(error => console.error('Error:', error));
    
    function loadHeroProduct(product) {
        document.querySelector('.imagen-hero').src = product.image;
        document.querySelector('.titulo-hero').textContent = product.name;
        document.querySelector('.subtitulo-hero').textContent = product.description;
        document.querySelector('.precio-hero').textContent = '€' + product.price;
        document.querySelector('.boton-cta-hero a').href = 'views/producto.html?id=' + product.id;
    }
    
    function loadOtherProducts(products) {
        const container = document.querySelector('.productos');
        container.innerHTML = '';
        
        products.forEach(product => {
            container.innerHTML += `
                <article class="zona-producto">
                    <div class="contenedor-imagen-producto">
                        <img src="${product.image}" alt="${product.name}" class="imagen-producto">
                        <button class="boton-favorito">
                            <img src="assets/iconos/corazon.png" alt="Favorito" class="icono">
                        </button>
                    </div>
                    <div class="informacion-producto">
                        <h3 class="titulo-producto">${product.name}</h3>
                        <p class="precio-producto">€${product.price}</p>
                        <button class="anadir-carrito-btn">
                            <a href="views/producto.html?id=${product.id}">Ver detalles</a>
                        </button>
                    </div>
                </article>
            `;
        });
    }
});