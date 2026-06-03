/**
 * GPS Simplifié - Approche Directe
 * Récupération simple des coordonnées GPS avec fetch vers backend
 */

let currentGPSData = {
    latitude: null,
    longitude: null,
    accuracy: null,
    timestamp: null
};

// Fonction principale pour obtenir les coordonnées GPS
function getGPSCoordinates() {
    console.log('🚀 Récupération GPS simplifiée...');
    
    if (!navigator.geolocation) {
        console.error('❌ Géolocalisation non supportée');
        updateGPSStatus('❌ GPS non supporté', 'error');
        return null;
    }
    
    updateGPSStatus('📍 Récupération GPS...', 'info');
    
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = Math.round(position.coords.accuracy);
            
            currentGPSData = {
                latitude: parseFloat(lat.toFixed(6)),
                longitude: parseFloat(lng.toFixed(6)),
                accuracy: accuracy,
                timestamp: new Date().toLocaleString('fr-FR')
            };
            
            console.log('✅ GPS Récupéré:', currentGPSData);
            updateGPSStatus('✅ GPS activé', 'success');
            updateCoordinatesDisplay(currentGPSData);
            
            // Sauvegarder en localStorage pour réutilisation
            localStorage.setItem('gpsData', JSON.stringify(currentGPSData));
            
        },
        (error) => {
            console.error('❌ Erreur GPS:', error);
            let errorMessage = 'Erreur GPS inconnue';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = 'Accès GPS refusé - Autorisez la géolocalisation';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = 'Position GPS indisponible';
                    break;
                case error.TIMEOUT:
                    errorMessage = 'Timeout GPS - Réessayez';
                    break;
            }
            
            updateGPSStatus('❌ ' + errorMessage, 'error');
            
            // Tenter de récupérer des données GPS sauvegardées
            const savedGPS = localStorage.getItem('gpsData');
            if (savedGPS) {
                console.log('📱 Utilisation GPS sauvegardé');
                currentGPSData = JSON.parse(savedGPS);
                updateGPSStatus('📱 GPS depuis cache', 'warning');
                updateCoordinatesDisplay(currentGPSData);
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000 // 5 minutes
        }
    );
    
    return currentGPSData;
}

// Fonction pour envoyer les coordonnées au backend
function savePositionToServer() {
    if (!currentGPSData.latitude || !currentGPSData.longitude) {
        console.warn('⚠️ Aucune coordonnée GPS à sauvegarder');
        return false;
    }
    
    console.log('📤 Envoi GPS au serveur...');
    
    return fetch('save-position.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            latitude: currentGPSData.latitude,
            longitude: currentGPSData.longitude,
            accuracy: currentGPSData.accuracy,
            timestamp: currentGPSData.timestamp,
            action: 'save_gps_position'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ GPS sauvegardé:', data);
        return data;
    })
    .catch(error => {
        console.error('❌ Erreur sauvegarde GPS:', error);
        return null;
    });
}

// Fonction pour récupérer les coordonnées pour commande
function getCoordinatesForOrder() {
    // Si pas de données GPS récentes, tenter de les récupérer
    if (!currentGPSData.latitude || !currentGPSData.longitude) {
        console.log('🔄 Récupération GPS pour commande...');
        getGPSCoordinates();
        
        // Attendre 3 secondes max pour GPS
        return new Promise((resolve) => {
            setTimeout(() => {
                if (currentGPSData.latitude && currentGPSData.longitude) {
                    resolve({
                        client_latitude: currentGPSData.latitude,
                        client_longitude: currentGPSData.longitude,
                        status: 'fresh'
                    });
                } else {
                    resolve({
                        client_latitude: null,
                        client_longitude: null,
                        status: 'error',
                        error: 'GPS non disponible'
                    });
                }
            }, 3000);
        });
    }
    
    return {
        client_latitude: currentGPSData.latitude,
        client_longitude: currentGPSData.longitude,
        status: 'cached'
    };
}

// Mise à jour du statut GPS
function updateGPSStatus(message, type) {
    const statusElement = document.getElementById('gps-status');
    if (statusElement) {
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 
                         type === 'error' ? 'fas fa-exclamation-triangle' : 
                         type === 'warning' ? 'fas fa-exclamation-circle' :
                         'fas fa-info-circle';
        
        statusElement.innerHTML = `<i class="${iconClass}"></i> ${message}`;
        statusElement.className = `alert alert-${type === 'error' ? 'danger' : type}`;
    }
}

// Affichage des coordonnées
function updateCoordinatesDisplay(gpsData) {
    if (!gpsData) return;
    
    let coordElement = document.getElementById('gps-coordinates');
    if (!coordElement) {
        coordElement = document.createElement('div');
        coordElement.id = 'gps-coordinates';
        coordElement.className = 'mt-2 p-2 bg-light rounded';
        const statusElement = document.getElementById('gps-status');
        if (statusElement && statusElement.parentNode) {
            statusElement.parentNode.appendChild(coordElement);
        }
    }
    
    coordElement.innerHTML = `
        <small class="text-muted">
            <i class="fas fa-map-marker-alt"></i> 
            Position: ${gpsData.latitude}, ${gpsData.longitude}
            <br>
            <i class="fas fa-bullseye"></i> Précision: ±${gpsData.accuracy}m
            <br>
            <i class="fas fa-clock"></i> Mis à jour: ${gpsData.timestamp}
        </small>
    `;
}

// Fonction globale pour commande
window.getGPSForOrderSimple = function() {
    return getCoordinatesForOrder();
};

// Fonction globale pour sauvegarde
window.saveGPSPosition = function() {
    return savePositionToServer();
};

// Initialisation automatique au chargement
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation GPS Simplifié...');
    
    // Récupération GPS automatique après 1 seconde
    setTimeout(() => {
        getGPSCoordinates();
    }, 1000);
});

// Fonction pour forcer actualisation GPS
window.refreshGPSSimple = function() {
    console.log('🔄 Actualisation GPS...');
    getGPSCoordinates();
};