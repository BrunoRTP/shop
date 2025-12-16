// Sistema de carrito para el frontend (sin redirección)
class CartFrontend {
    constructor() {
        this.cartCount = 0;
        this.baseUrl = this.getBaseUrl();
        this.init();
    }
    
    getBaseUrl() {
        const isRemote = window.location.hostname.includes('remotehost.es');
        // Detectar si estamos en la carpeta views o en la raíz
        const isInViews = window.location.pathname.includes('/views/');
        
        if (isRemote) {
            return 'https://remotehost.es/student025/shop';
        } else {
            return isInViews ? '..' : '.';
        }
    }
    
    init() {
        // Cargar cantidad actual del carrito al iniciar
        // Esperar un momento para asegurar que la sesión esté lista
        setTimeout(() => {
            this.updateCartCount();
        }, 100);
    }
    
    async addToCart(productId) {
        console.log('Añadiendo producto al carrito:', productId);
        
        try {
            const response = await fetch(`${this.baseUrl}/backend/ajax/cart_insert.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${productId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                console.log('Producto añadido al carrito');
                
                // Actualizar contador
                this.updateCartCount();
                
                // Mostrar notificación
                this.showNotification('Producto añadido al carrito', 'success');
                
                return true;
            } else {
                console.warn('Error:', data.message);
                this.showNotification(data.message || 'Error al añadir al carrito', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error añadiendo al carrito:', error);
            this.showNotification('Error de conexión', 'error');
            return false;
        }
    }
    
    async updateCartCount() {
        try {
            console.log('Cargando contador del carrito desde:', `${this.baseUrl}/backend/ajax/get_cart_count.php`);
            
            const response = await fetch(`${this.baseUrl}/backend/ajax/get_cart_count.php`);
            
            console.log('Respuesta recibida:', response.status);
            
            const data = await response.json();
            
            console.log('Datos del contador:', data);
            
            if (data.success) {
                this.cartCount = data.count;
                this.updateCartUI();
                console.log('Contador actualizado a:', this.cartCount);
            } else {
                // Si no hay sesión o error, mostrar 0
                console.warn('No hay sesión activa o error:', data.message);
                this.cartCount = 0;
                this.updateCartUI();
            }
        } catch (error) {
            console.error('Error obteniendo cantidad del carrito:', error);
            this.cartCount = 0;
            this.updateCartUI();
        }
    }
    
    updateCartUI() {
        // Actualizar todos los contadores de carrito en la página
        const cartCountElements = document.querySelectorAll('#cart-count, .items-carrito');
        cartCountElements.forEach(element => {
            element.textContent = this.cartCount;
        });
    }
    
    showNotification(message, type = 'success') {
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 300px;
        `;
        
        if (type === 'success') {
            notification.style.background = '#4CAF50';
            notification.style.color = 'white';
            notification.innerHTML = `
                <span style="font-size: 24px;">✓</span>
                <div>
                    <strong>${message}</strong><br>
                    <small><a href="${this.baseUrl}/backend/cart.php" style="color: white; text-decoration: underline;">Ver carrito</a></small>
                </div>
            `;
        } else {
            notification.style.background = '#f44336';
            notification.style.color = 'white';
            notification.innerHTML = `
                <span style="font-size: 24px;">✕</span>
                <strong>${message}</strong>
            `;
        }
        
        document.body.appendChild(notification);
        
        // Eliminar después de 4 segundos
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }
}

// Añadir estilos de animación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Inicializar automáticamente
const cartFrontend = new CartFrontend();
window.cartFrontend = cartFrontend;