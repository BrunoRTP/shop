<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
?>

<style>
    .manual-download-container {
        max-width: 800px;
        margin: 80px auto;
        padding: 40px;
        text-align: center;
    }
    
    .manual-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 60px 40px;
    }
    
    .manual-icon {
        font-size: 80px;
        margin-bottom: 30px;
    }
    
    .manual-card h1 {
        color: #2c3e50;
        font-size: 2.5em;
        margin-bottom: 20px;
    }
    
    .manual-card p {
        color: #7f8c8d;
        font-size: 1.2em;
        line-height: 1.8;
        margin-bottom: 40px;
    }
    
    .btn-download-pdf {
        display: inline-block;
        padding: 20px 50px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-size: 20px;
        font-weight: bold;
        box-shadow: 0 6px 25px rgba(52, 152, 219, 0.4);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-download-pdf:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 35px rgba(52, 152, 219, 0.6);
        background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
    }
    
    .btn-download-pdf:active {
        transform: translateY(-1px);
    }
    
    .info-text {
        margin-top: 30px;
        color: #95a5a6;
        font-size: 0.95em;
    }
    
    .features-list {
        text-align: left;
        display: inline-block;
        margin: 30px auto;
        padding: 20px 30px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .features-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .features-list li {
        padding: 8px 0;
        color: #555;
        font-size: 1.05em;
    }
    
    .features-list li:before {
        content: "✓ ";
        color: #2ecc71;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .badge {
        display: inline-block;
        background: #3498db;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85em;
        margin-top: 20px;
    }
</style>

<div class="manual-download-container">
    <div class="manual-card">
        <div class="manual-icon">👥</div>
        
        <h1>Manual de Usuario</h1>
        
        <span class="badge">Para Usuarios Finales</span>
        
        <p>
            Descarga la guía completa paso a paso para aprender a utilizar todas las funcionalidades 
            del sistema, desde el registro hasta la gestión de pedidos.
        </p>
        
        <div class="features-list">
            <ul>
                <li>Guía paso a paso con capturas de pantalla</li>
                <li>Instrucciones para administradores y clientes</li>
                <li>Ejemplos prácticos de uso</li>
                <li>Preguntas frecuentes (FAQ)</li>
                <li>Consejos y mejores prácticas</li>
            </ul>
        </div>
        
        <a href="/student025/shop/backend/manuales/Manual_Usuario.pdf" download class="btn-download-pdf">
            📥 Descargar Manual de Usuario (PDF)
        </a>
        
        <p class="info-text">
            El manual se descargará en formato PDF. Puedes guardarlo, imprimirlo o compartirlo con otros usuarios.
        </p>
    </div>
</div>

<?php include($root_dir . 'footer.php'); ?>