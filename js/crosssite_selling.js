// Función para enviar pedido al proveedor externo
async function enviarPedidoExterno(productId, email, address, quantity) {
    // URL del servidor remoto
    const urlProveedorRemoto = 'https://remotehost.es/student025/shop/backend/api/receive_bruno_orders.php'; 
    
    try {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('email', email);
        formData.append('address', address);
        formData.append('quantity', quantity);
        
        const response = await fetch(urlProveedorRemoto, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Error al enviar el pedido al proveedor');
        }
        
        const resultado = await response.json();
        
        if (resultado.success) {
            alert('Pedido enviado exitosamente al proveedor externo');
            return true;
        } else {
            alert('Error: ' + resultado.message);
            return false;
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al conectar con el proveedor externo: ' + error.message);
        return false;
    }
}

// Función para verificar si el producto tiene supplier_id diferente de 1
async function verificarSupplier(productId) {
    try {
        const response = await fetch('/student025/shop/backend/ajax/verificar_supplier.php?product_id=' + productId);
        const data = await response.json();
        return data.supplier_id && data.supplier_id != 1;
    } catch (error) {
        console.error('Error al verificar supplier:', error);
        return false;
    }
}

// Función que se ejecuta al hacer clic en el botón de pedido externo
async function procesarPedidoExterno(button) {
    const row = button.closest('.order-row'); 
    
    if (!row) {
        console.error("No se encontró el contenedor del pedido (.order-row)");
        return;
    }

    const productId = row.dataset.productId; // Simplificado: ya está en el dataset del row
    
    // Buscamos los datos dentro de la fila actual
    const email = row.querySelector('[data-email]').dataset.email;
    const address = row.querySelector('[data-address]').dataset.address;
    const quantity = row.querySelector('[data-quantity]').dataset.quantity;
    
    if (confirm('¿Enviar este pedido al proveedor externo?')) {
        button.disabled = true;
        button.textContent = 'Enviando...';
        
        const exito = await enviarPedidoExterno(productId, email, address, quantity);
        
        if (exito) {
            button.textContent = 'Enviado ✓';
            button.style.backgroundColor = '#28a745';
        } else {
            button.disabled = false;
            button.textContent = 'Enviar a Proveedor';
        }
    }
}

// Inicializar botones al cargar la página
document.addEventListener('DOMContentLoaded', async function() {
    const filasOrders = document.querySelectorAll('.order-row');
    
    for (const fila of filasOrders) {
        const productId = fila.dataset.productId;
        
        if (productId) {
            const esExterno = await verificarSupplier(productId);
            
            if (esExterno) {
                // Crear botón para enviar a proveedor externo
                const boton = document.createElement('button');
                boton.className = 'btn-proveedor-externo';
                boton.textContent = 'Enviar a Proveedor';
                boton.style.cssText = 'background-color: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;';
                
                boton.onclick = function() {
                    procesarPedidoExterno(this);
                };
                
                const celdaAcciones = fila.querySelector('.acciones-cell');
                if (celdaAcciones) {
                    celdaAcciones.appendChild(boton);
                }
            }
        }
    }
});