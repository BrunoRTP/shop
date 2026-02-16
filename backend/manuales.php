<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'header.php'); 
?>


<div class="manuales-container">
    <div class="manuales-header">
        <h1>📚 Centro de Documentación</h1>
        <p>Accede a todos los manuales y documentación del sistema</p>
    </div>
    
    
    <div class="manuales-grid">
        <a href="/student025/shop/backend/manual_tecnico.php" class="manual-card technical-card">
            <div class="manual-icon">🔧</div>
            <h2>Manual Técnico</h2>
            <p>Documentación técnica completa destinada a desarrolladores. Incluye arquitectura del sistema, base de datos, funcionalidades implementadas y pendientes.</p>
            <div style="text-align: center;">
                <span class="manual-badge">Para Desarrolladores</span>
            </div>
        </a>
        
        <a href="/student025/shop/backend/manual_usuario.php" class="manual-card user-card">
            <div class="manual-icon">👥</div>
            <h2>Manual de Usuario</h2>
            <p>Guía paso a paso para usuarios finales. Aprende a utilizar todas las funcionalidades del sistema, desde el registro hasta la gestión de pedidos.</p>
            <div style="text-align: center;">
                <span class="manual-badge">Para Usuarios Finales</span>
            </div>
        </a>
        
        <a href="/student025/shop/backend/manual_instalacion.php" class="manual-card installation-card">
            <div class="manual-icon">⚙️</div>
            <h2>Manual de Instalación</h2>
            <p>Requisitos del servidor y pasos detallados para instalar la aplicación web. Incluye configuración de Apache, MariaDB y PHP.</p>
            <div style="text-align: center;">
                <span class="manual-badge">Para Administradores</span>
            </div>
        </a>
    </div>
    
    <div style="margin-top: 50px; text-align: center; color: #7f8c8d;">
        <p><strong>Nota:</strong> Si necesitas ayuda adicional o tienes alguna pregunta, contacta con el equipo de soporte.</p>
        <p style="margin-top: 20px;">Última actualización: <?php echo date('d/m/Y'); ?></p>
    </div>
</div>

<?php include($root_dir . 'footer.php'); ?>