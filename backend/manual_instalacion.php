<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
?>


<div class="installation-manual">
    <h1>Manual de Instalación - Sistema de Tienda Online</h1>
    
    <h2>1. Requisitos del Servidor</h2>
    
    <h3>1.1 Requisitos de Software</h3>
    <table class="requirements-table">
        <tr>
            <th>Componente</th>
            <th>Versión Mínima</th>
            <th>Versión Recomendada</th>
        </tr>
        <tr>
            <td>Sistema Operativo</td>
            <td>Linux (Ubuntu 18.04+)</td>
            <td>Ubuntu 22.04 LTS</td>
        </tr>
        <tr>
            <td>Servidor Web</td>
            <td>Apache 2.4+</td>
            <td>Apache 2.4.52+</td>
        </tr>
        <tr>
            <td>PHP</td>
            <td>PHP 8.4+</td>
            <td>PHP 8.4+ (Desarrollado en 8.3.14)</td>
        </tr>
        <tr>
            <td>Base de Datos</td>
            <td>MariaDB 10.3+ / MySQL 5.7+</td>
            <td>MariaDB 11.5+ (Desarrollado en 11.5.2)</td>
        </tr>
    </table>
    
    <h3>1.2 Requisitos de Hardware</h3>
    <ul>
        <li><strong>CPU:</strong> Mínimo 1 núcleo, recomendado 2+ núcleos</li>
        <li><strong>RAM:</strong> Mínimo 1GB, recomendado 2GB+</li>
        <li><strong>Disco:</strong> Mínimo 10GB de espacio libre</li>
    </ul>
    
    <h2>2. Instalación de Dependencias</h2>
    
    <h3>2.1 Instalar Apache</h3>
    <pre><code>sudo apt update
sudo apt install apache2
sudo systemctl start apache2
sudo systemctl enable apache2</code></pre>
    
    <h3>2.2 Instalar PHP y Extensiones</h3>
    <pre><code>sudo apt install php libapache2-mod-php php-mysql php-curl php-json php-mbstring php-xml
sudo systemctl restart apache2</code></pre>
    
    <h3>2.3 Instalar MariaDB</h3>
    <pre><code>sudo apt install mariadb-server mariadb-client
sudo systemctl start mariadb
sudo systemctl enable mariadb
sudo mysql_secure_installation</code></pre>
    
    <h2>3. Configuración de la Base de Datos</h2>
    
    <h3>3.1 Descargar Archivo SQL</h3>
    <p>Descarga el archivo SQL que contiene toda la estructura de la base de datos:</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="/student025/shop/backend/sql/student025_13-2-26.sql" download class="btn-download">
            📥 Descargar base de datos
        </a>
    </div>
    
    <style>
        .btn-download {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
    </style>
    
    <div class="note-box">
        <strong>📦 El archivo SQL incluye:</strong><br>
        • Todas las tablas necesarias (customers, products, orders, cart, reviews, etc.)<br>
        • Datos de ejemplo para pruebas<br>
        • Foreign keys y relaciones configuradas<br>
        • Usuarios de prueba incluyendo admin
    </div>
    
    <h3>3.2 Importar Base de Datos</h3>
    
    <h4>Opción 1: Desde phpMyAdmin (Más Fácil)</h4>
    <div class="step-box">
        <strong>Paso 1:</strong> Accede a phpMyAdmin
    </div>
    <div class="step-box">
        <strong>Paso 2:</strong> Crea una nueva base de datos llamada <code>shop</code>
    </div>
    <div class="step-box">
        <strong>Paso 3:</strong> Selecciona la base de datos <code>shop</code>
    </div>
    <div class="step-box">
        <strong>Paso 4:</strong> Ve a la pestaña "Importar"
    </div>
    <div class="step-box">
        <strong>Paso 5:</strong> Selecciona el archivo descargado <code>student025_13-2-26.sql</code>
    </div>
    <div class="step-box">
        <strong>Paso 6:</strong> Haz clic en "Continuar" y espera a que termine
    </div>
    
    <h2>4. Instalación de la Aplicación</h2>
    
    <h3>4.1 Copiar Archivos</h3>
    <pre><code>cd /var/www/html
sudo mkdir -p student025/shop
# Copiar todos los archivos del proyecto a esta carpeta</code></pre>
    
    <h3>4.2 Configurar Permisos</h3>
    <pre><code>sudo chown -R www-data:www-data /var/www/html/student025/shop
sudo chmod -R 755 /var/www/html/student025/shop</code></pre>
    
    <h2>5. Configuración de la Aplicación</h2>
    
    <h3>5.1 Configurar Conexión a Base de Datos</h3>
    <p>Editar el archivo <code>backend/db_connection.php</code>:</p>
    <pre><code>&lt;?php
$server = "localhost";
$user = "root";              // Tu usuario MySQL
$pass = "tu_contraseña";     // Tu contraseña MySQL
$bd = "shop";

$conn = mysqli_connect($server, $user, $pass, $bd);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?&gt;</code></pre>
    
    <div class="note-box">
        <strong>💡 Consejo:</strong> Si usaste el instalador automático, copia las credenciales que te mostró al final de la instalación.
    </div>
    
    <h3>5.2 Instalación de PHPMailer</h3>
    <p>PHPMailer es necesario para enviar emails de confirmación de pedidos.</p>
    
    <h4>Opción 1: Con Composer (Recomendado)</h4>
    <pre><code>cd /var/www/html
composer require phpmailer/phpmailer</code></pre>
    
    <h4>Opción 2: Instalación Manual</h4>
    <pre><code>cd /var/www/html
wget https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
unzip master.zip
mv PHPMailer-master PHPMailer</code></pre>
    
    <h4>Configurar SMTP</h4>
    <p>Editar <code>backend/db/db_cart_checkout.php</code> y buscar la sección de PHPMailer (líneas 50-60 aprox.):</p>
    <pre><code>$mail = new PHPMailer(true);

// Configuración del servidor SMTP
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'tu_email@gmail.com';
$mail->Password = 'tu_app_password';  // Contraseña de aplicación
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// Remitente
$mail->setFrom('tu_email@gmail.com', 'Tu Tienda');</code></pre>
    
    <div class="warning-box">
        <strong>⚠️ Gmail:</strong> Para usar Gmail necesitas:<br>
        1. Habilitar verificación en 2 pasos en tu cuenta de Google<br>
        2. Generar una "Contraseña de aplicación" (no usar tu contraseña normal)<br>
        3. Ir a: Cuenta de Google → Seguridad → Contraseñas de aplicaciones
    </div>
    
    <h3>5.3 Verificar Instalación</h3>
    <div class="step-box">
        <strong>Paso 1:</strong> Accede a <code>http://tu-servidor/student025/shop/backend/forms/form_login.php</code>
    </div>
    <div class="step-box">
        <strong>Paso 2:</strong> Intenta iniciar sesión con:<br>
        • Usuario: <code>admin_main</code><br>
        • Contraseña: <em>Deberás resetearla (ver siguiente sección)</em>
    </div>
    
    <h3>5.4 Crear/Resetear Usuario Administrador</h3>
    <p>Para crear un nuevo hash de contraseña, crea un archivo temporal <code>generar_hash.php</code>:</p>
    <pre><code>&lt;?php
echo password_hash('tu_contraseña_segura', PASSWORD_BCRYPT);
?&gt;</code></pre>
    
    <p>Ejecuta ese archivo en el navegador, copia el hash generado y actualiza la base de datos:</p>
    <pre><code>mysql -u root -p shop

UPDATE 025_customers 
SET password_hash = '$2y$12$tu_hash_generado_aqui' 
WHERE username = 'admin_main';</code></pre>
    
    <hr style="margin: 40px 0;">
    
    <h2>🎉 ¡Instalación Completada!</h2>
    <p>Si llegaste hasta aquí sin errores, tu sistema está listo para usar.</p>
    
    <div class="note-box">
        <strong>Próximos Pasos:</strong><br>
        1. Inicia sesión con tu usuario administrador<br>
        2. Explora el panel de administración<br>
        3. Prueba crear productos, pedidos y el sistema de carrito<br>
        4. Verifica que los emails de confirmación se envíen correctamente<br>
        5. Consulta el Manual Técnico y Manual de Usuario para más información
    </div>
    
    <div class="warning-box">
        <strong>🔒 Seguridad:</strong> Una vez completada la instalación, elimina el archivo <code>instalador_bd.php</code> del servidor para evitar reinstalaciones accidentales.
    </div>
    
    <hr style="margin: 40px 0;">
    <p style="text-align: center; color: #7f8c8d;">
        <strong>Manual de Instalación v1.0</strong> | Última actualización: <?php echo date('d/m/Y'); ?>
    </p>
</div>

<?php include($root_dir . 'footer.php'); ?>