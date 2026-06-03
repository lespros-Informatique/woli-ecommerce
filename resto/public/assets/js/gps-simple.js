/**
 * GPS Simple - Solution automatique pour récupération GPS
 * Récupère automatiquement les coordonnées GPS lors de la commande
 */

class GPSSimple {
    constructor() {
        this.position = null;
        this.isInitialized = false;
        this.error = null;
    }

    /**
     * Initialise le GPS automatiquement
     */
    async init() {
        console.log('🚀 Initialisation GPS Simple...');
        
        // Vérifier si la géolocalisation est supportée
        if (!navigator.geolocation) {
            this.error = 'GPS non supporté par ce navigateur';
            console.error(this.error);
            this.updateStatus('❌ GPS non supporté', 'danger');
            return false;
        }

        try {
            // Demander la position GPS avec des options raisonnables
            const position = await this.getCurrentPosition();
            this.isInitialized = true;
            console.log('✅ GPS initialisé avec succès:', position);
            this.updateStatus('✅ GPS activé', 'success');
            return true;
        } catch (error) {
            this.error = error.message;
            console.error('❌ Erreur GPS:', error.message);
            this.updateStatus('❌ GPS non disponible', 'danger');
            return false;
        }
    }

    /**
     * Récupère la position actuelle
     */
    async getCurrentPosition() {
        return new Promise((resolve, reject) => {
            const options = {
                enableHighAccuracy: true,
                timeout: 8000,
                maximumAge: 300000 // 5 minutes
            };

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.position = {
                        latitude: parseFloat(position.coords.latitude.toFixed(6)),
                        longitude: parseFloat(position.coords.longitude.toFixed(6)),
                        accuracy: Math.round(position.coords.accuracy),
                        timestamp: new Date().toLocaleString('fr-FR')
                    };
                    resolve(this.position);
                },
                (error) => {
                    let message = 'Erreur GPS inconnue';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Accès GPS refusé';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Position GPS indisponible';
                            break;
                        case error.TIMEOUT:
                            message = 'Timeout GPS';
                            break;
                    }
                    reject(new Error(message));
                },
                options
            );
        });
    }

    /**
     * Met à jour le statut GPS dans l'interface
     */
    updateStatus(message, type = 'info') {
        const statusElement = document.getElementById('gps-status');
        if (statusElement) {
            const iconClass = type === 'success' ? 'fas fa-check-circle' : 
                             type === 'danger' ? 'fas fa-exclamation-triangle' : 
                             'fas fa-info-circle';
            
            statusElement.innerHTML = `<i class="${iconClass}"></i> ${message}`;
            statusElement.className = `text-${type}`;
        }

        // Mettre à jour les coordonnées si disponibles
        if (this.position && type === 'success') {
            this.updateCoordinatesDisplay();
        }
    }

    /**
     * Affiche les coordonnées GPS
     */
    updateCoordinatesDisplay() {
        if (!this.position) return;

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
                Position: ${this.position.latitude}, ${this.position.longitude}
                <br>
                <i class="fas fa-clock"></i> Mis à jour: ${this.position.timestamp}
            </small>
        `;
    }

    /**
     * Retourne les coordonnées GPS
     */
    getCoordinates() {
        return this.position;
    }

    /**
     * Vérifie si le GPS est disponible
     */
    isAvailable() {
        return this.isInitialized && this.position !== null;
    }

    /**
     * Force la récupération d'une nouvelle position
     */
    async refresh() {
        console.log('🔄 Actualisation GPS...');
        this.updateStatus('🔄 Actualisation...', 'info');
        
        try {
            await this.getCurrentPosition();
            this.updateStatus('✅ GPS actualisé', 'success');
            return true;
        } catch (error) {
            console.error('❌ Erreur actualisation GPS:', error.message);
            this.updateStatus('❌ Erreur actualisation', 'danger');
            return false;
        }
    }
}

// Instance globale
window.gpsSimple = new GPSSimple();

// Initialisation automatique au chargement de la page
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🚀 Démarrage GPS Simple...');
    
    // Attendre un peu pour que la page soit complètement chargée
    setTimeout(async () => {
        await window.gpsSimple.init();
    }, 500);
});

// Fonction globale pour récupération GPS lors de la commande
window.getGPSForOrder = function() {
    const coords = window.gpsSimple.getCoordinates();
    if (coords) {
        console.log('📍 GPS pour commande:', coords);
        return {
            client_latitude: coords.latitude,
            client_longitude: coords.longitude
        };
    } else {
        console.warn('⚠️ GPS non disponible pour la commande');
        return {
            client_latitude: null,
            client_longitude: null
        };
    }
};