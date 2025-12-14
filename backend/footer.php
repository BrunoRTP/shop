<?php
// Verificar si se ha enviado un cambio de idioma
if(isset($_POST['change_language'])) {
    $language = $_POST['language'];
    // Guardar cookie por 30 días
    setcookie('user_language', $language, time() + (30 * 24 * 60 * 60), '/');
    $_COOKIE['user_language'] = $language; // Actualizar inmediatamente
}

// Obtener idioma actual
$current_language = isset($_COOKIE['user_language']) ? $_COOKIE['user_language'] : 'es';
?>

<footer class="footer">
    <div class="footer-content">
        
        <!-- Selector de Idioma -->
        <div class="language-selector">
            <form method="POST" action="" id="languageForm">
                <label for="language">Idioma:</label>
                <select name="language" id="language" onchange="this.form.submit()">
                    <option value="es" <?= $current_language == 'es' ? 'selected' : '' ?>>Español</option>
                    <option value="en" <?= $current_language == 'en' ? 'selected' : '' ?>>English</option>
                    <option value="fr" <?= $current_language == 'fr' ? 'selected' : '' ?>>Frances</option>
                    <option value="de" <?= $current_language == 'de' ? 'selected' : '' ?>>Aleman</option>
                    <option value="it" <?= $current_language == 'it' ? 'selected' : '' ?>>Italiano</option>
                    <option value="pt" <?= $current_language == 'pt' ? 'selected' : '' ?>>Portugues</option>
                </select>
                <input type="hidden" name="change_language" value="1">
            </form>
            <small style="display: block; margin-top: 5px; color: #666;">
                Idioma actual: <strong><?= strtoupper($current_language) ?></strong>
            </small>
        </div>

        <p></p>
        
        <div class="social-links">
            <a href="#" class="social-icon">boton1</a>
            <a href="#" class="social-icon">boton2</a>
            <a href="#" class="social-icon">boton3</a>
        </div>
    </div>
</footer>

</body>
</html>