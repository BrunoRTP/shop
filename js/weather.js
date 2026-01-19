// js/weather.js - JavaScript para el widget del clima

// Determinar la URL base (detecta automáticamente la ruta del proyecto)
function getBaseUrl() {
    const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    
    if (isLocal) {
        // Detectar si estamos en /student025/shop/
        const path = window.location.pathname;
        if (path.includes('/student025/shop/')) {
            return '/student025/shop';
        }
        return '';
    }
    
    return 'https://remotehost.es/student025/shop';
}

// Mostrar datos del clima guardados en la BD
function displayWeather() {
    const baseUrl = getBaseUrl();
    
    // Solo obtener datos YA GUARDADOS en la BD, NO llama al API externo
    fetch(`${baseUrl}/backend/api/get_weather.php`)
        .then(response => {
            if(!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('Respuesta del servidor:', text);
            try {
                const data = JSON.parse(text);
                if(data.success) {
                    // Clima actual
                    const iconNumber = String(data.current.icon).padStart(2, '0');
                    const iconUrl = `https://www.accuweather.com/images/weathericons/${iconNumber}.svg`;
                    document.getElementById('weather-icon-img').src = iconUrl;
                    document.getElementById('current-temp').textContent = data.current.temperature + '°C';
                    document.getElementById('current-desc').textContent = data.current.weather_text;
                    document.getElementById('current-wind').textContent = data.current.wind_speed + ' km/h';
                    
                    // Historial
                    const historyContainer = document.getElementById('weather-history');
                    historyContainer.innerHTML = '';
                    
                    data.history.forEach(day => {
                        const dayIconNumber = String(day.icon).padStart(2, '0');
                        const dayIconUrl = `https://www.accuweather.com/images/weathericons/${dayIconNumber}.svg`;
                        const date = new Date(day.date);
                        const formattedDate = date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
                        
                        historyContainer.innerHTML += `
                            <div class="history-day">
                                <div class="history-date">${formattedDate}<br>${day.time}</div>
                                <div class="history-icon">
                                    <img src="${dayIconUrl}" alt="Weather" onerror="this.style.display='none'">
                                </div>
                                <div class="history-temp">${day.temperature}°C</div>
                                <div class="history-wind">${day.wind_speed} km/h</div>
                            </div>
                        `;
                    });
                } else {
                    console.log('Sin datos del clima:', data.message);
                }
            } catch(e) {
                console.error('Error parseando JSON:', e);
                document.getElementById('current-desc').textContent = 'Error de formato';
            }
        })
        .catch(error => {
            console.error('Error mostrando clima:', error);
            document.getElementById('current-desc').textContent = 'Sin datos';
        });
}

// Función para actualizar manualmente (solo admin)
function refreshWeather() {
    const baseUrl = getBaseUrl();
    
    const btn = document.getElementById('btn-refresh-weather');
    if(btn) {
        btn.disabled = true;
        btn.classList.add('loading');
        btn.textContent = '⏳ Actualizando...';
    }
    
    // Llamar al script que hace fetch al API de AccuWeather
    fetch(`${baseUrl}/backend/api/weather_fetch.php`)
        .then(response => {
            if(!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('Respuesta del servidor:', text);
            try {
                const data = JSON.parse(text);
                if(data.success) {
                    console.log('✅ Datos actualizados desde AccuWeather');
                    // Ahora mostrar los datos actualizados
                    displayWeather();
                    
                    if(btn) {
                        btn.textContent = '✓ Actualizado';
                        setTimeout(() => {
                            btn.textContent = '🔄 Actualizar Clima';
                            btn.disabled = false;
                            btn.classList.remove('loading');
                        }, 2000);
                    }
                } else {
                    console.error('Error:', data.message);
                    if(btn) {
                        btn.textContent = '✗ Error';
                        setTimeout(() => {
                            btn.textContent = '🔄 Actualizar Clima';
                            btn.disabled = false;
                            btn.classList.remove('loading');
                        }, 2000);
                    }
                }
            } catch(e) {
                console.error('Error parseando JSON:', e);
                if(btn) {
                    btn.textContent = '✗ Error';
                    setTimeout(() => {
                        btn.textContent = '🔄 Actualizar Clima';
                        btn.disabled = false;
                        btn.classList.remove('loading');
                    }, 2000);
                }
            }
        })
        .catch(error => {
            console.error('Error actualizando clima:', error);
            if(btn) {
                btn.textContent = '✗ Error';
                setTimeout(() => {
                    btn.textContent = '🔄 Actualizar Clima';
                    btn.disabled = false;
                    btn.classList.remove('loading');
                }, 2000);
            }
        });
}

// Mostrar datos guardados al cargar la página
displayWeather();

// Event listener para el botón de actualización (solo admin)
const btnRefresh = document.getElementById('btn-refresh-weather');
if(btnRefresh) {
    btnRefresh.addEventListener('click', refreshWeather);
}