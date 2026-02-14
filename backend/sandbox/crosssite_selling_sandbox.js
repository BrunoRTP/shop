// js/crosssite_selling_sandbox.js
// Versión SANDBOX para pruebas locales (envía a ti mismo)

// Función para obtener el id_code de un producto
async function obtenerIdCode(productId) {
    try {
        const response = await fetch('/student025/shop/backend/ajax/get_product_id_code.php?product_id=' + productId);
        const data = await response.json();
        
        if (data.success && data.id_code) {
            return data.id_code;
        } else {
            console.error('No se pudo obtener id_code:', data);
            return null;
        }
    } catch (error) {
        console.error('Error obteniendo id_code:', error);
        return null;
    }
}

// Función para enviar pedido en modo SANDBOX (a ti mismo)
async function enviarPedidoExternoSandbox(productId, email, address, quantity) {
    // Primero obtener el id_code del producto
    console.log('🧪 SANDBOX: Obteniendo id_code del producto...');
    const id_code = await obtenerIdCode(productId);
    
    if (!id_code) {
        alert('Error: No se pudo obtener el id_code del producto.\n\nVerifica que el producto tenga un id_code asignado.');
        return false;
    }
    
    console.log('✓ id_code obtenido:', id_code);
    
    // URL que envía directamente a receive (sin pasar por send)
    const urlReceive = '/student025/shop/backend/sandbox/receive_orders_sandbox.php';
    
    try {
        const formData = new FormData();
        formData.append('id_code', id_code);  // ← CAMBIO: enviar id_code en lugar de product_id
        formData.append('email', email);
        formData.append('address', address);
        formData.append('quantity', quantity);
        
        console.log('🧪 SANDBOX: Enviando pedido a ti mismo...');
        console.log('Datos:', {id_code, email, address, quantity});
        
        const response = await fetch(urlReceive, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor (HTTP ' + response.status + ')');
        }
        
        const resultado = await response.json();
        console.log('Respuesta recibida:', resultado);
        
        if (resultado.success) {
            alert('✓ SANDBOX: Pedido enviado exitosamente a ti mismo\n\n' + 
                  resultado.message + '\n\n' +
                  'ID Code: ' + resultado.debug.id_code_enviado);
            console.log('Detalles:', resultado.detalles);
            return true;
        } else {
            alert('✗ SANDBOX ERROR:\n' + resultado.message + '\n\nRevisa la consola para más detalles');
            console.error('Error completo:', resultado);
            return false;
        }
        
    } catch (error) {
        console.error('Error de conexión:', error);
        alert('✗ Error al conectar con el servidor SANDBOX:\n' + error.message);
        return false;
    }
}

// Función para verificar si el producto tiene supplier_id diferente de 1
async function verificarSupplierSandbox(productId) {
    try {
        const response = await fetch('/student025/shop/backend/ajax/verificar_supplier.php?product_id=' + productId);
        
        if (!response.ok) {
            console.error('Error al verificar supplier:', response.status);
            return false;
        }
        
        const data = await response.json();
        
        // Retorna true si el supplier_id es diferente de 1 (es un producto externo)
        return data.success && data.supplier_id && data.supplier_id != 1;
        
    } catch (error) {
        console.error('Error al verificar supplier:', error);
        return false;
    }
}

// Función que se ejecuta al hacer clic en el botón de pedido externo SANDBOX
async function procesarPedidoExternoSandbox(button) {
    const row = button.closest('.order-row'); 
    
    if (!row) {
        console.error("No se encontró el contenedor del pedido (.order-row)");
        alert('Error: No se pudo encontrar la información del pedido');
        return;
    }

    // Obtener datos del pedido desde los atributos data-*
    const productId = row.dataset.productId;
    
    // Buscar elementos con data-* dentro de la fila
    const emailElement = row.querySelector('[data-email]');
    const addressElement = row.querySelector('[data-address]');
    const quantityElement = row.querySelector('[data-quantity]');
    
    if (!emailElement || !addressElement || !quantityElement) {
        console.error('Faltan elementos data- en la fila');
        alert('Error: Faltan datos del pedido');
        return;
    }
    
    const email = emailElement.dataset.email;
    const address = addressElement.dataset.address;
    const quantity = quantityElement.dataset.quantity;
    
    // Validar datos
    if (!productId || !email || !address || !quantity) {
        console.error('Datos incompletos:', {productId, email, address, quantity});
        alert('Error: Datos incompletos del pedido');
        return;
    }
    
    // Confirmar antes de enviar
    if (!confirm('🧪 SANDBOX MODE\n\n¿Enviar pedido a ti mismo para probar?\n\nProducto ID: ' + productId + '\nCantidad: ' + quantity)) {
        return;
    }
    
    // Deshabilitar botón mientras se procesa
    button.disabled = true;
    const textoOriginal = button.textContent;
    button.textContent = '🧪 Enviando...';
    button.style.backgroundColor = '#FFA500'; // Naranja mientras procesa
    
    // Enviar pedido
    const exito = await enviarPedidoExternoSandbox(productId, email, address, quantity);
    
    if (exito) {
        button.textContent = '✓ Enviado (Sandbox)';
        button.style.backgroundColor = '#28a745'; // Verde
        button.style.color = 'white';
        // No habilitar de nuevo el botón para evitar duplicados
    } else {
        button.textContent = textoOriginal;
        button.style.backgroundColor = '#FF6B00'; // Naranja oscuro original
        button.disabled = false; // Permitir reintentar
    }
}

// Inicializar botones al cargar la página (VERSIÓN SANDBOX)
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🧪 Iniciando sistema de crossselling SANDBOX...');
    console.log('Modo: ENVÍO A TI MISMO para pruebas');
    
    const filasOrders = document.querySelectorAll('.order-row');
    console.log('Filas de pedidos encontradas:', filasOrders.length);
    
    for (const fila of filasOrders) {
        const productId = fila.dataset.productId;
        
        if (!productId) {
            console.warn('Fila sin product_id:', fila);
            continue;
        }
        
        // Verificar si es un producto externo (supplier_id != 1)
        const esExterno = await verificarSupplierSandbox(productId);
        
        if (esExterno) {
            console.log('Producto externo detectado:', productId);
            
            // Crear botón para enviar a proveedor externo (SANDBOX)
            const boton = document.createElement('button');
            boton.className = 'btn-proveedor-externo-sandbox';
            boton.textContent = '🧪 Sandbox: Enviar a mí mismo';
            boton.style.cssText = `
                background-color: #FF6B00; 
                color: white; 
                padding: 8px 15px; 
                border: none; 
                border-radius: 5px; 
                cursor: pointer;
                margin-top: 5px;
                font-weight: 600;
                transition: background-color 0.3s;
            `;
            
            boton.onmouseover = function() {
                if (!this.disabled) {
                    this.style.backgroundColor = '#E55D00';
                }
            };
            
            boton.onmouseout = function() {
                if (!this.disabled && this.textContent.includes('Sandbox')) {
                    this.style.backgroundColor = '#FF6B00';
                }
            };
            
            boton.onclick = function() {
                procesarPedidoExternoSandbox(this);
            };
            
            // Añadir botón al contenedor de acciones
            const celdaAcciones = fila.querySelector('.acciones-cell');
            if (celdaAcciones) {
                celdaAcciones.appendChild(boton);
                console.log('Botón SANDBOX añadido para producto:', productId);
            } else {
                console.warn('No se encontró .acciones-cell para producto:', productId);
            }
        }
    }
    
    console.log('🧪 Sistema de crossselling SANDBOX inicializado');
});