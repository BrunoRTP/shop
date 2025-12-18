// Sistema de carrito para el frontend (sin redirección)
class CartFrontend {
    constructor() {
        this.cartCount = 0;
        this.baseUrl = this.getBaseUrl();
        this.init();
    }
    
    getBaseUrl() {
        const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
        const isInViews = window.location.pathname.includes('/views/');
        
        if (isLocal) {
            // Si estamos en local, usar rutas relativas
            return isInViews ? '..' : '.';
        } else {
            // Por defecto (GitHub Pages, remotehost, etc.) usar servidor remoto
            return 'https://remotehost.es/student025/shop';
        }
    }
    
    init() {
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
                this.updateCartCount();
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
        const cartCountElements = document.querySelectorAll('#cart-count, .items-carrito');
        cartCountElements.forEach(element => {
            element.textContent = this.cartCount;
        });
    }
    
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification-${type}`;
        
        if (type === 'success') {
            notification.innerHTML = `
                <span class="notification-icon">✓</span>
                <div class="notification-content">
                    <strong>${message}</strong><br>
                    <small><a href="${this.baseUrl}/backend/cart.php" class="notification-link">Ver carrito</a></small>
                </div>
            `;
        } else {
            notification.innerHTML = `
                <span class="notification-icon">✕</span>
                <strong>${message}</strong>
            `;
        }
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('notification-fade-out');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }
}

// Inicializar automáticamente
const cartFrontend = new CartFrontend();
window.cartFrontend = cartFrontend;