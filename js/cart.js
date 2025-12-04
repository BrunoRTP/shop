document.addEventListener('DOMContentLoaded', function() {
    
    function updateCart(productId, action) {
        const row = document.querySelector(`tr[data-product-id="${productId}"]`);
        if (!row) return;
        
        const quantityCell = row.querySelector('.quantity-cell');
        const subtotalCell = row.querySelector('.subtotal-cell');
        const buttons = row.querySelectorAll('button');
        

        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('action', action);
        
        fetch('/student025/shop/backend/ajax/update_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if(!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                if(data.quantity === 0) {
                    row.remove();
                    
                    const tbody = document.querySelector('tbody');
                    const remainingRows = tbody.querySelectorAll('tr:not(.empty-cart-row)');
                    
                    if(remainingRows.length === 0) {
                        tbody.innerHTML = '<tr class="empty-cart-row"><td colspan="6">Tu carrito está vacío</td></tr>';
                        document.getElementById('checkout-btn').style.display = 'none';
                    }
                } else {
                    quantityCell.textContent = data.quantity;
                    subtotalCell.textContent = '€' + data.subtotal;
                }
                
                document.getElementById('cart-total').textContent = '€' + data.total;
            } 
        })
        .finally(() => {
            buttons.forEach(btn => btn.disabled = false);
        });
    }
    
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            updateCart(productId, 'add');
        });
    });
    
    document.querySelectorAll('.btn-remove-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            updateCart(productId, 'remove');
        });
    });
});