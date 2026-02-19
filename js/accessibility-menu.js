$(document).ready(function() {
    
    const STORAGE_KEY = 'accessibility_preferences';

    
    // Valores por defecto
    const defaultSettings = {
        contrast: 'none',
        fontSize: 1,
        lineHeight: 1.5,
        wordSpacing: 0,
        letterSpacing: 0
    };
    
    // Cargar preferencias guardadas o usar defaults
    let settings = loadSettings();
    
    
    // Almacenamiento en localStorage
    
    function loadSettings() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            return JSON.parse(saved);
        }
        return { ...defaultSettings };
    }
    
    function saveSettings() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
    }
    
    
    // Aplicar las configuraciones
    
    function applyAllSettings() {
        applyContrast(settings.contrast);
        applyFontSize(settings.fontSize);
        applyLineHeight(settings.lineHeight);
        applyWordSpacing(settings.wordSpacing);
        applyLetterSpacing(settings.letterSpacing);
        updateUI();
    }
    
    // Aplicar al cargar la página
    applyAllSettings();
    
    
    // Menu lateral
    
    $('#btn-accessibility').on('click', function() {
        $('#accessibility-panel').addClass('active');
        $('#accessibility-overlay').addClass('active');
        $('body').css('overflow', 'hidden');
    });
    
    $('#close-accessibility, #accessibility-overlay').on('click', function() {
        $('#accessibility-panel').removeClass('active');
        $('#accessibility-overlay').removeClass('active');
        $('body').css('overflow', '');
    });
    
    // Cerrar con ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#accessibility-panel').hasClass('active')) {
            $('#accessibility-panel').removeClass('active');
            $('#accessibility-overlay').removeClass('active');
            $('body').css('overflow', '');
        }
    });
    
    
    // Contraste
    
    $('.contrast-btn').on('click', function() {
        const contrastType = $(this).data('contrast');
        settings.contrast = contrastType;
        applyContrast(contrastType);
        saveSettings();
        updateUI();
    });
    
    function applyContrast(type) {
        // Remover todas las clases de contraste
        $('body').removeClass('grayscale high-contrast-dark high-contrast-light high-saturation low-saturation');
        
        // Aplicar la clase correspondiente
        if (type !== 'none') {
            $('body').addClass(type);
        }
    }
    
    
    // Tamaño de fuente
    
    $('#decrease-font').on('click', function() {
        if (settings.fontSize > 0.7) {
            settings.fontSize -= 0.1;
            settings.fontSize = Math.round(settings.fontSize * 10) / 10;
            applyFontSize(settings.fontSize);
            saveSettings();
            updateUI();
        }
    });
    
    $('#increase-font').on('click', function() {
        if (settings.fontSize < 2) {
            settings.fontSize += 0.1;
            settings.fontSize = Math.round(settings.fontSize * 10) / 10;
            applyFontSize(settings.fontSize);
            saveSettings();
            updateUI();
        }
    });
    
    function applyFontSize(size) {
        // Si ya guardamos los tamaños originales, restaurarlos primero
        if (window.originalFontSizes && size === 1) {
            // Restaurar tamaños originales
            for (let selector in window.originalFontSizes) {
                $(selector).css('font-size', window.originalFontSizes[selector]);
            }
            delete window.originalFontSizes;
            return;
        }
        
        // Si es la primera vez, guardar los tamaños originales
        if (!window.originalFontSizes) {
            window.originalFontSizes = {};
            
            // Guardar tamaños de elementos principales
            const selectores = [
                'body', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'a', 'span', 'button', 'input', 'label', 'li',
                '.titulo-hero', '.precio-hero', '.subtitulo-hero',
                '.producto-nombre', '.producto-precio'
            ];
            
            selectores.forEach(sel => {
                const elem = $(sel).first();
                if (elem.length) {
                    const computedSize = window.getComputedStyle(elem[0]).fontSize;
                    window.originalFontSizes[sel] = computedSize;
                }
            });
        }
        
        // Aplicar la escala multiplicando los tamaños originales
        for (let selector in window.originalFontSizes) {
            const originalSize = parseFloat(window.originalFontSizes[selector]);
            const newSize = originalSize * size;
            
            // Aplicar solo a elementos fuera de los menús laterales
            $(selector).not('.accessibility-panel, .accessibility-panel *, .menu-lateral, .menu-lateral *')
                       .css('font-size', newSize + 'px');
        }
    }
    
    
    // Interlineado
    
    $('#decrease-line-height').on('click', function() {
        if (settings.lineHeight > 1) {
            settings.lineHeight -= 0.1;
            settings.lineHeight = Math.round(settings.lineHeight * 10) / 10;
            applyLineHeight(settings.lineHeight);
            saveSettings();
            updateUI();
        }
    });
    
    $('#increase-line-height').on('click', function() {
        if (settings.lineHeight < 3) {
            settings.lineHeight += 0.1;
            settings.lineHeight = Math.round(settings.lineHeight * 10) / 10;
            applyLineHeight(settings.lineHeight);
            saveSettings();
            updateUI();
        }
    });
    
    function applyLineHeight(height) {
        $('body').css('line-height', height);
    }
    
    
    // Espacio entre palabras
    
    $('#decrease-word-spacing').on('click', function() {
        if (settings.wordSpacing > -0.2) {
            settings.wordSpacing -= 0.1;
            settings.wordSpacing = Math.round(settings.wordSpacing * 10) / 10;
            applyWordSpacing(settings.wordSpacing);
            saveSettings();
            updateUI();
        }
    });
    
    $('#increase-word-spacing').on('click', function() {
        if (settings.wordSpacing < 1) {
            settings.wordSpacing += 0.1;
            settings.wordSpacing = Math.round(settings.wordSpacing * 10) / 10;
            applyWordSpacing(settings.wordSpacing);
            saveSettings();
            updateUI();
        }
    });
    
    function applyWordSpacing(spacing) {
        $('body').css('word-spacing', spacing + 'em');
    }
    
    
    // Espacio entre letras
    
    $('#decrease-letter-spacing').on('click', function() {
        if (settings.letterSpacing > -0.1) {
            settings.letterSpacing -= 0.05;
            settings.letterSpacing = Math.round(settings.letterSpacing * 100) / 100;
            applyLetterSpacing(settings.letterSpacing);
            saveSettings();
            updateUI();
        }
    });
    
    $('#increase-letter-spacing').on('click', function() {
        if (settings.letterSpacing < 0.5) {
            settings.letterSpacing += 0.05;
            settings.letterSpacing = Math.round(settings.letterSpacing * 100) / 100;
            applyLetterSpacing(settings.letterSpacing);
            saveSettings();
            updateUI();
        }
    });
    
    function applyLetterSpacing(spacing) {
        $('body').css('letter-spacing', spacing + 'em');
    }
    
    
    // Restaurar configuración por defecto
    
    $('#reset-accessibility').on('click', function() {
        settings = { ...defaultSettings };
        saveSettings();
        applyAllSettings();
        
        // Feedback visual
        $(this).text('✓ Restaurado');
        setTimeout(() => {
            $(this).text('Restaurar configuración');
        }, 1500);
    });
    
    
    // Actalizar UI
    
    function updateUI() {
        // Actualizar botones de contraste
        $('.contrast-btn').removeClass('active');
        $(`.contrast-btn[data-contrast="${settings.contrast}"]`).addClass('active');
        
        // Actualizar valores mostrados (formato compacto)
        $('#font-size-value').text(settings.fontSize.toFixed(1));
        $('#line-height-value').text(settings.lineHeight.toFixed(1));
        $('#word-spacing-value').text(settings.wordSpacing.toFixed(1));
        $('#letter-spacing-value').text(settings.letterSpacing.toFixed(1));
    }
    
});