<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
?>

<style>

</style>

<div class="manual-container">
    <h1>Manual Técnico - Sistema de Tienda Online</h1>
    
    <h2>1. Introducción</h2>
    <p>Este manual está destinado a desarrolladores web que necesiten mantener, ampliar o modificar la aplicación. Se describen las funcionalidades implementadas, la arquitectura del sistema y las funcionalidades pendientes de desarrollo.</p>
    
    <h2>2. Arquitectura del Sistema</h2>
    
    <h3>2.1 Stack Tecnológico</h3>
    <div class="table-container">
        <table class="tech-table">
            <tr>
                <th>Componente</th>
                <th>Tecnología</th>
                <th>Versión Mínima</th>
            </tr>
            <tr>
                <td>Servidor Web</td>
                <td>Apache</td>
                <td>2.4+</td>
            </tr>
            <tr>
                <td>Base de Datos</td>
                <td>MariaDB/MySQL</td>
                <td>10.3+ / 5.7+</td>
            </tr>
            <tr>
                <td>Backend</td>
                <td>PHP</td>
                <td>8.4+</td>
            </tr>
            <tr>
                <td>Frontend</td>
                <td>HTML5, CSS3, JavaScript (Vanilla)</td>
                <td>ES6+</td>
            </tr>
        </table>
    </div>
    
    <h3>2.2 Estructura de Directorios</h3>
    <pre><code>/student025/shop/
├── index.html              # Página principal del frontend
├── assets/                 # Recursos estáticos (imágenes, iconos)
├── css/                    # Hojas de estilo
│   ├── style.css          # Estilos del frontend
│   └── styleBack.css      # Estilos del backend
├── js/                     # Scripts JavaScript
│   ├── scrypt.js          # Funciones generales
│   ├── cart_frontend.js   # Gestión del carrito (frontend)
│   ├── guest_session.js   # Sesiones de invitado
│   ├── crosssite_selling.js # Cross-selling entre proveedores
│   └── weather.js         # Widget del clima
├── views/                  # Vistas adicionales del frontend
└── backend/                # Aplicación de administración
    ├── index.php          # Dashboard principal
    ├── header.php         # Header común
    ├── footer.php         # Footer común
    ├── auth_functions.php # Funciones de autenticación
    ├── db_connection.php  # Conexión a BD
    ├── products.php       # CRUD de productos
    ├── orders.php         # CRUD de pedidos
    ├── customers.php      # CRUD de clientes
    ├── cart.php           # Gestión del carrito
    ├── reviews.php        # Gestión de reseñas
    ├── forms/             # Formularios de inserción/edición
    ├── db/                # Scripts de procesamiento de BD
    ├── ajax/              # Endpoints AJAX
    └── api/               # API para integraciones externas</code></pre>
    
    
    <h2>3. Funcionalidades Implementadas <span class="status-badge implemented">✓</span></h2>
    
    <h3>3.1 Sistema de Autenticación</h3>
    <p><strong>Archivo:</strong> <code>backend/auth_functions.php</code>, <code>backend/db/bd_login.php</code></p>

    <h3>3.2 Gestión de Productos</h3>
    <p><strong>Archivo:</strong> <code>backend/products.php</code></p>
    <p>CRUD completo de productos. Los formularios de cliente y pedidos funcionan de manera similar.</p>

    <div class="note">
        <strong>Nota:</strong> La gestión de pedidos y clientes sigue exactamente el mismo patrón de archivos y flujo de trabajo que los productos.
    </div>
    
    <h3>3.3 Gestión de Pedidos</h3>
    <p><strong>Archivo:</strong> <code>backend/orders.php</code></p>
    <p>Funciona igual que productos pero con lógica adicional:</p>
    
    <h3>3.4 Gestión de Clientes</h3>
    <p><strong>Archivo:</strong> <code>backend/customers.php</code></p>
    <p>Similar a productos. Permite a los admins gestionar usuarios:</p>
    
    <h3>3.5 Carrito de Compras</h3>
    <p><strong>Archivos:</strong> <code>backend/cart.php</code>, <code>js/cart_frontend.js</code></p>
    <ul>
        <li><strong>Añadir productos:</strong> AJAX endpoint <code>backend/ajax/cart_insert.php</code></li>
        <li><strong>Ver carrito:</strong> Lista productos con cantidad y precio total</li>
        <li><strong>Actualizar cantidad:</strong> <code>backend/ajax/cart_update.php</code></li>
        <li><strong>Eliminar productos:</strong> <code>backend/db/bd_cart_delete.php</code></li>
        <li><strong>Checkout:</strong> <code>backend/db/db_cart_checkout.php</code></li>
    </ul>
    
    <h3>3.6 Sistema de Reviews</h3>
    <p><strong>Archivo:</strong> <code>backend/reviews.php</code></p>
    <ul>
        <li>Los clientes pueden dejar una reseña por producto comprado</li>
        <li>Rating de 1 a 5 estrellas + comentario opcional</li>
        <li>Validación: un cliente solo puede dejar una review por producto</li>
        <li>Los admins pueden ver y eliminar reviews</li>
    </ul>
    
    <h3>3.7 Cross-Selling con Proveedores Externos</h3>
    <p><strong>Archivos:</strong> <code>js/crosssite_selling.js</code>, <code>backend/api/receive_bruno_orders.php</code></p>
    <p>Sistema para enviar pedidos a proveedores externos y recibir pedidos de otros sistemas.</p>
    
    <h4>Envío de pedidos (outbound):</h4>
    <ul>
        <li>Detecta automáticamente productos de proveedores externos (supplier_id ≠ 1)</li>
        <li>Añade botón "Pedir a Proveedor Externo" en la vista de pedidos</li>
        <li>Función <code>enviarPedidoExterno()</code> envía datos al endpoint del proveedor</li>
    </ul>
    
    <h4>Recepción de pedidos (inbound):</h4>
    <ul>
        <li>Endpoint público: <code>backend/api/receive_bruno_orders.php</code></li>
        <li>Acepta pedidos vía POST con: product_id, email, address, quantity</li>
        <li>Inserta directamente en la tabla de pedidos</li>
        <li>Retorna JSON con confirmación o error</li>
    </ul>
    
    <h3>3.8 Widget del Clima</h3>
    <p><strong>Archivos:</strong> <code>js/weather.js</code>, <code>backend/api/get_weather.php</code></p>
    <ul>
        <li>Muestra información meteorológica almacenada en BD</li>
        <li>NO hace llamadas directas al API externo desde el frontend</li>
        <li>Los datos se actualizan mediante script cron del servidor</li>
        <li>Muestra temperatura actual, condiciones y velocidad del viento</li>
        <li>Historial de consultas previas</li>
    </ul>
    
    <h3>3.9 Frontend Público</h3>
    <p><strong>Archivo:</strong> <code>index.html</code>, <code>js/index_products.js</code></p>
    <ul>
        <li>Catálogo de productos con diseño responsive</li>
        <li>Sesiones de invitado automáticas para usuarios no logueados</li>
        <li>Añadir al carrito sin necesidad de login</li>
        <li>Navegación inferior (footer nav) para móviles</li>
        <li>Integración con carrito del backend</li>
    </ul>
    
    <h3>3.10 API Externa</h3>
    <p><strong>Archivos:</strong> <code>backend/api/get_Bruno_products.php</code>, <code>backend/api/importar_productos.php</code></p>
    <ul>
        <li>Consumo de API de productos de compañeros (GET con api_key)</li>
        <li>Importación masiva de productos externos a la BD local</li>
        <li>Actualización de supplier_id para identificar origen</li>
    </ul>
    
    <h2>4. Funcionalidades Pendientes <span class="status-badge pending">⏳</span></h2>
    
    <h3>4.1 Sistema de Favoritos</h3>
    <p>Botón "Favoritos" en el footer de navegación sin funcionalidad implementada.</p>
    <p><strong>Tareas pendientes:</strong></p>
    <ul>
        <li>Crear tabla <code>025_favorites</code> en BD</li>
        <li>Implementar endpoints AJAX para añadir/eliminar favoritos</li>
        <li>Crear página de visualización de favoritos</li>
        <li>Actualizar <code>cart_frontend.js</code> con funciones de favoritos</li>
    </ul>
    
    <h3>4.2 Recuperación de Contraseña</h3>
    <p>No existe sistema de "Olvidé mi contraseña".</p>
    <p><strong>Tareas pendientes:</strong></p>
    <ul>
        <li>Formulario de recuperación por email</li>
        <li>Generación de tokens temporales</li>
        <li>Envío de email con enlace de recuperación</li>
        <li>Página de cambio de contraseña con token</li>
    </ul>
    
    <h3>4.3 Notificaciones en Tiempo Real</h3>
    <p>Los cambios de estado de pedidos no notifican automáticamente.</p>
    <p><strong>Tareas pendientes:</strong></p>
    <ul>
        <li>Sistema de notificaciones push o WebSockets</li>
        <li>Emails automáticos al cambiar estado del pedido</li>
        <li>Notificaciones de nuevo pedido para admins</li>
    </ul>
    
    <h3>4.4 Historial de Pedidos Detallado</h3>
    <p>Falta visualización del estado del pedido (pendiente, enviado, entregado).</p>
    <p><strong>Tareas pendientes:</strong></p>
    <ul>
        <li>Añadir campo <code>status</code> a tabla 025_order</li>
        <li>Sistema de seguimiento con timestamps</li>
        <li>Interfaz para actualizar estado (solo admins)</li>
        <li>Vista de tracking para clientes</li>
    </ul>
    
 
    <h2>5. Testing</h2>
    
    <h3>5.1 Casos de Prueba Básicos</h3>
    <ul>
        <li><strong>Login:</strong> Credenciales válidas/inválidas, sesión persistente</li>
        <li><strong>Carrito:</strong> Añadir/eliminar productos, actualizar cantidades, checkout completo</li>
        <li><strong>Pedidos:</strong> Crear, visualizar, cancelar, añadir review</li>
        <li><strong>Productos:</strong> CRUD completo, validación de campos</li>
        <li><strong>Cross-selling:</strong> Envío a proveedor externo, recepción de pedidos</li>
        <li><strong>Roles:</strong> Verificar permisos de admin vs client</li>
    </ul>
    
    <h2>6. Contacto y Soporte</h2>
    <p>Para consultas técnicas o reportar bugs, contactar con el equipo de desarrollo.</p>
    
    <hr style="margin: 40px 0;">
    <p style="text-align: center; color: #7f8c8d;">
        <strong>Manual Técnico v1.0</strong> | Última actualización: <?php echo date('d/m/Y'); ?>
    </p>
</div>

<?php include($root_dir . 'footer.php'); ?>