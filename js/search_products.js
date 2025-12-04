document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchCount = document.getElementById('search-count');
    const productsContainer = document.getElementById('products-container');
    const originalHTML = productsContainer.innerHTML;
    let searchTimeout;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchProducts(this.value.trim()), 300);
    });
    
    function searchProducts(nombre) {
        if(nombre.length < 3) {
            productsContainer.innerHTML = originalHTML;
            searchCount.textContent = '';
            return;
        }
        
        fetch('/student025/shop/backend/ajax/search_products.php?q=' + encodeURIComponent(nombre))
            .then(response => response.json())
            .then(products => {
                if(products.length === 0) {
                    searchCount.textContent = 'No se encontraron productos';
                    productsContainer.innerHTML = '<p class="no-products-message">No hay productos para mostrar</p>';
                    return;
                }
                
                const isAdmin = originalHTML.includes('producto-acciones');
                let html = '';
                
                products.forEach(product => {
                    html += `
                        <div class="producto-wrapper">
                            <div class="producto-item">
                                <img src="/student025/shop/assets/img/ph.jpg" class="mueble-placeholder">
                                <div class="info-container">
                                    <div class="producto-info">
                                        ID: ${product.id}, Nombre: ${product.name}, Descripcion: ${product.description}
                                    </div>
                                    ${isAdmin ? `
                                        <div class="producto-acciones">
                                            <button><a href="/student025/shop/backend/forms/form_products_update_call.php?id=${product.id}" class="social-icon">Update</a></button>
                                            <button><a href="/student025/shop/backend/forms/form_products_delete_call.php?id=${product.id}" class="social-icon">Delete</a></button>
                                        </div>
                                    ` : ''}
                                    <button onclick="location.href='/student025/shop/backend/db/db_cart_insert.php?id=${product.id}'" type="button">
                                        <a href="/student025/shop/backend/db/db_cart_insert.php?id=${product.id}" class="social-icon">Add to cart</a>
                                    </button>
                                </div>
                            </div>
                            <hr><br>
                        </div>
                    `;
                });
                
                productsContainer.innerHTML = html;
                searchCount.textContent = 'Mostrando ' + products.length + ' producto(s)';
            })
            .catch(error => {
                console.error('Error:', error);
                searchCount.textContent = 'Error al buscar';
            });
    }
});