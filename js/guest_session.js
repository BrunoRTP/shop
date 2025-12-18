// Sistema de sesión de invitado para el frontend
class GuestSession {
    constructor() {
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
        // Verificar si ya existe una sesión
        let session = this.getSession();
        
        if (!session) {
            // Crear nueva sesión de invitado
            session = {
                user_id: 'guest_' + Date.now(),
                username: 'invitado',
                type_client: 'guest',
                is_guest: true,
                created_at: new Date().toISOString()
            };
            
            // Guardar en memoria del navegador (durante la sesión actual)
            this.saveSession(session);
            
            // Crear sesión en el backend
            this.createBackendSession();
        }
    }
    
    getSession() {
        const sessionData = sessionStorage.getItem('guest_session');
        return sessionData ? JSON.parse(sessionData) : null;
    }
    
    saveSession(session) {
        sessionStorage.setItem('guest_session', JSON.stringify(session));
    }
    
    async createBackendSession() {
        try {
            const response = await fetch(`${this.baseUrl}/backend/ajax/create_guest_session.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include', 
                body: JSON.stringify({
                    action: 'create_guest_session'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                console.log('Sesión de invitado creada en el backend:', data);
                // Actualizar la sesión local con los datos del servidor
                if(data.user) {
                    const session = {
                        user_id: data.user.id,
                        username: data.user.username,
                        type_client: data.user.type_client,
                        is_guest: data.user.is_guest,
                        created_at: new Date().toISOString()
                    };
                    this.saveSession(session);
                }
            } else {
                console.error('Error creando sesión:', data.message);
            }
        } catch (error) {
            console.error('Error creando sesión de invitado:', error);
        }
    }
    
    isGuest() {
        const session = this.getSession();
        return session && session.is_guest === true;
    }
    
    getUsername() {
        const session = this.getSession();
        return session ? session.username : 'invitado';
    }
    
    logout() {
        sessionStorage.removeItem('guest_session');
        // Redirigir al backend para cerrar sesión PHP también
        window.location.href = `${this.baseUrl}/backend/logout.php`;
    }
}

// Inicializar automáticamente al cargar la página
const guestSession = new GuestSession();

// Hacer disponible globalmente
window.guestSession = guestSession;