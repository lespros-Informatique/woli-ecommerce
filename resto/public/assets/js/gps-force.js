/**
 * GPS Force - Solution Finale pour Éliminer les Coordonnées Par Défaut
 * Cette version force la récupération GPS réelle et bloque les commandes si GPS invalide
 */

class GPSForce {
    constructor() {
        this.position = null;
        this.isInitialized = false;
        this.error = null;
        this.REQUIRED_ACCURACY = 1000; // 1km précision maximale acceptée
        this.ABIDJAN_CENTER = { lat: 5.3599, lng: -4.0083 };
        this.BOUAKE_CENTER = { lat: 7.6800, lng: -5.0300 };
    }

    /**
     * Initialisation force GPS avec validation stricte
     */
    async init() {
        console.log('🚀 GPS Force - Initialisation forcée...');
        
        if (!navigator.geolocation) {
            this.error = 'GPS non supporté par ce navigateur';
            console.error(this.error);
            this.updateStatus('❌ GPS non supporté', 'danger');
            return false;
        }

        try {
            const position = await this.getCurrentPosition();
            this.isInitialized = true;
            console.log('✅ GPS Force initialisé:', position);
            this.updateStatus('✅ GPS activé', 'success');
            return true;
        } catch (error) {
            this.error = error.message;
            console.error('❌ Erreur GPS Force:', error.message);
            this.updateStatus('❌ GPS non disponible', 'danger');
            return false;
        }
    }

    /**
     * Récupération position avec validation stricte
     */
    async getCurrentPosition() {
        return new Promise((resolve, reject) => {
            const options = {
                enableHighAccuracy: true,
                timeout: 15000, // Timeout plus long
                maximumAge: 0 // Pas de cache - forcer position fraîche
            };

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const coords = {
                        latitude: parseFloat(position.coords.latitude.toFixed(6)),
                        longitude: parseFloat(position.coords.longitude.toFixed(6)),
                        accuracy: Math.round(position.coords.accuracy),
                        timestamp: new Date().toLocaleString('fr-FR')
                    };

                    // Validation stricte des coordonnées
                    if (this.isValidPosition(coords)) {
                        this.position = coords;
                        resolve(coords);
                    } else {
                        reject(new Error('Coordonnées GPS invalides'));
                    }
                },
                (error) => {
                    let message = 'Erreur GPS inconnue';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Accès GPS refusé - Autorisation requise';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Position GPS indisponible';
                            break;
                        case error.TIMEOUT:
                            message = 'Timeout GPS - Vérifiez votre connexion';
                            break;
                    }
                    reject(new Error(message));
                },
                options
            );
        });
    }

    /**
     * Validation stricte des coordonnées GPS
     */
    isValidPosition(coords) {
        // Vérifier que les coordonnées sont dans des limites raisonnables pour la Côte d'Ivoire
        if (coords.latitude < 4.0 || coords.latitude > 10.5) {
            console.warn('⚠️ Latitude hors limites Côte d\'Ivoire');
            return false;
        }
        
        if (coords.longitude < -8.5 || coords.longitude > -2.0) {
            console.warn('⚠️ Longitude hors limites Côte d\'Ivoire');
            return false;
        }

        // Vérifier la précision GPS
        if (coords.accuracy > this.REQUIRED_ACCURACY) {
            console.warn(`⚠️ Précision GPS insuffisante: ${coords.accuracy}m (max: ${this.REQUIRED_ACCURACY}m)`);
            return false;
        }

        console.log('✅ Position GPS validée:', coords);
        return true;
    }

    /**
     * Vérification si la position est suspecte (Abidjan au lieu de Bouaké)
     */
    isSuspectedAbidjanPosition(coords) {
        const distanceToAbidjan = this.calculateDistance(
            coords.latitude, coords.longitude,
            this.ABIDJAN_CENTER.lat, this.ABIDJAN_CENTER.lng
        );

        const distanceToBouake = this.calculateDistance(
            coords.latitude, coords.longitude,
            this.BOUAKE_CENTER.lat, this.BOUAKE_CENTER.lng
        );

        // Si très proche d'Abidjan et loin de Bouaké, c'est suspect
        if (distanceToAbidjan < 50 && distanceToBouake > 100) {
            console.warn(`🚨 Position suspecte - Proche d'Abidjan (${distanceToAbidjan.toFixed(1)}km) et loin de Bouaké (${distanceToBouake.toFixed(1)}km)`);
            return true;
        }

        return false;
    }

    /**
     * Alerte utilisateur si position suspecte
     */
    showPositionWarning(coords) {
        const warning = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Vérification GPS:</strong> Votre position GPS semble proche d'Abidjan. 
                Si vous êtes à Bouaké, cela peut indiquer un problème GPS. 
                Voulez-vous continuer ou corriger votre position ?
            </div>
        `;

        // Afficher l'alerte dans l'interface
        const gpsStatus = document.getElementById('gps-status');
        if (gpsStatus) {
            gpsStatus.innerHTML += warning;
        }

        return confirm('Votre position GPS semble être à Abidjan. Êtes-vous sûr d\'être à Bouaké ?');
    }

    /**
     * Mise à jour statut GPS
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

        if (this.position && type === 'success') {
            this.updateCoordinatesDisplay();
        }
    }

    /**
     * Affichage coordonnées avec validation
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

        // Vérifier si position suspecte
        const isSuspected = this.isSuspectedAbidjanPosition(this.position);
        const warningClass = isSuspected ? 'border-warning' : 'border-success';
        const warningIcon = isSuspected ? 'fas fa-exclamation-triangle text-warning' : 'fas fa-check-circle text-success';

        coordElement.innerHTML = `
            <div class="border ${warningClass} p-2 rounded">
                <small class="${warningIcon}">
                    <strong>Position GPS:</strong> ${this.position.latitude}, ${this.position.longitude}
                    <br><strong>Précision:</strong> ±${this.position.accuracy}m
                    <br><strong>Mis à jour:</strong> ${this.position.timestamp}
                    ${isSuspected ? '<br><span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Position proche d\'Abidjan</span>' : ''}
                </small>
            </div>
        `;
    }

    /**
     * Fonction globale pour récupération GPS FORCÉE lors de la commande
     */
    getCoordinatesForOrder() {
        if (!this.position) {
            console.error('❌ GPS Force: Aucune position disponible');
            return {
                client_latitude: null,
                client_longitude: null,
                error: 'GPS non initialisé'
            };
        }

        // Vérification position suspecte
        if (this.isSuspectedAbidjanPosition(this.position)) {
            const userConfirmed = this.showPositionWarning(this.position);
            if (!userConfirmed) {
                return {
                    client_latitude: null,
                    client_longitude: null,
                    error: 'Position suspecte - utilisateur a refusé'
                };
            }
        }

        console.log('📍 GPS Force coordinates pour commande:', this.position);
        return {
            client_latitude: this.position.latitude,
            client_longitude: this.position.longitude
        };
    }

    /**
     * Calculer distance entre deux points GPS
     */
    calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Rayon de la Terre en km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    /**
     * Forcer actualisation GPS
     */
    async refresh() {
        console.log('🔄 GPS Force - Actualisation forcée...');
        this.updateStatus('🔄 Actualisation GPS...', 'info');
        
        try {
            const coords = await this.getCurrentPosition();
            this.updateStatus('✅ GPS actualisé', 'success');
            return true;
        } catch (error) {
            console.error('❌ Erreur actualisation GPS Force:', error.message);
            this.updateStatus('❌ Erreur actualisation', 'danger');
            return false;
        }
    }
}

// Instance globale GPS Force
window.gpsForce = new GPSForce();

// Initialisation automatique
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🚀 Démarrage GPS Force...');
    
    setTimeout(async () => {
        await window.gpsForce.init();
    }, 500);
});

// Fonction globale pour commande avec validation stricte
window.getGPSForOrderForce = function() {
    return window.gpsForce.getCoordinatesForOrder();
};

// Bloquer commande si GPS invalide
window.validateOrderGPS = function() {
    const coords = window.gpsForce.getCoordinatesForOrder();
    
    if (!coords.client_latitude || !coords.client_longitude) {
        alert('❌ GPS requis: Position GPS nécessaire pour la commande. Veuillez autoriser la géolocalisation.');
        return false;
    }
    
    if (coords.error) {
        alert('❌ Erreur GPS: ' + coords.error);
        return false;
    }
    
    return true;
};