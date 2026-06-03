/**
 * GPS Tracking System for Restaurant Orders
 * Récupère automatiquement les coordonnées GPS en temps réel
 */

class GPSTracker {
    constructor() {
        this.currentPosition = null;
        this.isTracking = false;
        this.trackingInterval = null;
        this.watchId = null;
        this.callbacks = {
            onLocationUpdate: [],
            onError: [],
            onPermissionDenied: []
        };
    }

    /**
     * Initialize GPS tracking with permission request
     */
    async init() {
        try {
            // Check if geolocation is supported
            if (!navigator.geolocation) {
                throw new Error('La géolocalisation n\'est pas supportée par ce navigateur');
            }

            // Request permission and get current position
            await this.getCurrentPosition();
            return true;
        } catch (error) {
            console.error('GPS Initialization Error:', error);
            this.triggerErrorCallbacks(error);
            return false;
        }
    }

    /**
     * Get current position once
     */
    async getCurrentPosition() {
        return new Promise((resolve, reject) => {
            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            };

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.currentPosition = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                        timestamp: position.timestamp
                    };
                    console.log('📍 Current GPS Position:', this.currentPosition);
                    this.triggerLocationUpdateCallbacks(this.currentPosition);
                    resolve(this.currentPosition);
                },
                (error) => {
                    console.error('GPS Error:', error);
                    this.handleGeolocationError(error);
                    reject(error);
                },
                options
            );
        });
    }

    /**
     * Start continuous GPS tracking
     */
    startTracking(interval = 30000) {
        if (this.isTracking) {
            console.warn('GPS tracking already active');
            return;
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 30000
        };

        this.watchId = navigator.geolocation.watchPosition(
            (position) => {
                this.currentPosition = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                    timestamp: position.timestamp
                };
                console.log('📍 GPS Update:', this.currentPosition);
                this.triggerLocationUpdateCallbacks(this.currentPosition);
            },
            (error) => {
                console.error('GPS Tracking Error:', error);
                this.handleGeolocationError(error);
            },
            options
        );

        this.isTracking = true;
        console.log('✅ GPS tracking started');
    }

    /**
     * Stop GPS tracking
     */
    stopTracking() {
        if (this.watchId !== null) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
        }
        this.isTracking = false;
        console.log('⏹️ GPS tracking stopped');
    }

    /**
     * Send GPS coordinates to server
     */
    async sendToServer(commandeId, status = 'en route') {
        if (!this.currentPosition) {
            throw new Error('Position GPS non disponible');
        }

        try {
            const response = await fetch('/commandes/updateLocation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'commande_id': commandeId,
                    'latitude': this.currentPosition.latitude,
                    'longitude': this.currentPosition.longitude,
                    'statut': status
                })
            });

            const result = await response.json();
            
            if (result.status === 1) {
                console.log('✅ GPS sent to server successfully');
                return result;
            } else {
                throw new Error(result.msg || 'Erreur lors de l\'envoi GPS');
            }
        } catch (error) {
            console.error('❌ GPS Server Error:', error);
            throw error;
        }
    }

    /**
     * Handle geolocation errors
     */
    handleGeolocationError(error) {
        let message = '';
        switch (error.code) {
            case error.PERMISSION_DENIED:
                message = 'Accès à la géolocalisation refusé par l\'utilisateur';
                this.triggerPermissionDeniedCallbacks();
                break;
            case error.POSITION_UNAVAILABLE:
                message = 'Position GPS indisponible';
                break;
            case error.TIMEOUT:
                message = 'Timeout lors de la récupération de la position GPS';
                break;
            default:
                message = 'Erreur inconnue de géolocalisation';
                break;
        }
        
        const errorObj = new Error(message);
        errorObj.code = error.code;
        this.triggerErrorCallbacks(errorObj);
    }

    /**
     * Add callback for location updates
     */
    onLocationUpdate(callback) {
        this.callbacks.onLocationUpdate.push(callback);
    }

    /**
     * Add callback for GPS errors
     */
    onError(callback) {
        this.callbacks.onError.push(callback);
    }

    /**
     * Add callback for permission denied
     */
    onPermissionDenied(callback) {
        this.callbacks.onPermissionDenied.push(callback);
    }

    /**
     * Trigger location update callbacks
     */
    triggerLocationUpdateCallbacks(position) {
        this.callbacks.onLocationUpdate.forEach(callback => {
            try {
                callback(position);
            } catch (error) {
                console.error('Error in location update callback:', error);
            }
        });
    }

    /**
     * Trigger error callbacks
     */
    triggerErrorCallbacks(error) {
        this.callbacks.onError.forEach(callback => {
            try {
                callback(error);
            } catch (error) {
                console.error('Error in error callback:', error);
            }
        });
    }

    /**
     * Trigger permission denied callbacks
     */
    triggerPermissionDeniedCallbacks() {
        this.callbacks.onPermissionDenied.forEach(callback => {
            try {
                callback();
            } catch (error) {
                console.error('Error in permission denied callback:', error);
            }
        });
    }

    /**
     * Get current position (synchronous)
     */
    getCurrentPositionSync() {
        return this.currentPosition;
    }

    /**
     * Check if GPS is available
     */
    isAvailable() {
        return navigator.geolocation !== undefined;
    }

    /**
     * Format coordinates for display
     */
    formatCoordinates(position) {
        if (!position) return 'Position non disponible';
        
        const lat = position.latitude.toFixed(6);
        const lng = position.longitude.toFixed(6);
        const accuracy = Math.round(position.accuracy);
        
        return `Lat: ${lat}, Lng: ${lng} (±${accuracy}m)`;
    }
}

// Global GPS tracker instance
window.gpsTracker = new GPSTracker();

/**
 * Initialize GPS tracking when page loads
 */
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🚀 Initializing GPS Tracker...');
    
    // Initialize GPS
    const initialized = await window.gpsTracker.init();
    
    if (initialized) {
        console.log('✅ GPS Tracker initialized successfully');
        
        // Add UI feedback
        const gpsStatus = document.getElementById('gps-status');
        if (gpsStatus) {
            gpsStatus.innerHTML = '<i class="fas fa-satellite-dish text-success"></i> GPS activé';
            gpsStatus.className = 'text-success';
        }
        
        // Setup automatic GPS sending for orders
        window.gpsTracker.onLocationUpdate(async function(position) {
            // Check if we're on an order tracking page
            const commandeId = document.querySelector('[data-commande-id]');
            if (commandeId) {
                try {
                    await window.gpsTracker.sendToServer(
                        commandeId.dataset.commandeId, 
                        'en route'
                    );
                } catch (error) {
                    console.error('Failed to send GPS to server:', error);
                }
            }
        });
    } else {
        console.warn('⚠️ GPS Tracker initialization failed');
        
        const gpsStatus = document.getElementById('gps-status');
        if (gpsStatus) {
            gpsStatus.innerHTML = '<i class="fas fa-satellite-dish text-danger"></i> GPS non disponible';
            gpsStatus.className = 'text-danger';
        }
    }
    
    // Handle permission denied
    window.gpsTracker.onPermissionDenied(function() {
        console.warn('🚫 GPS permission denied');
        showGPSPermissionDeniedAlert();
    });
    
    // Handle GPS errors
    window.gpsTracker.onError(function(error) {
        console.error('💥 GPS Error:', error.message);
        showGPSErrorAlert(error.message);
    });
});

/**
 * Show GPS permission denied alert
 */
function showGPSPermissionDeniedAlert() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'GPS Non Autorisé',
            text: 'L\'accès à la géolocalisation est nécessaire pour le suivi de commande en temps réel.',
            footer: 'Vous pouvez changer cette autorisation dans les paramètres de votre navigateur.',
            confirmButtonText: 'Compris'
        });
    }
}

/**
 * Show GPS error alert
 */
function showGPSErrorAlert(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Erreur GPS',
            text: message,
            confirmButtonText: 'Compris'
        });
    }
}

/**
 * Manual GPS refresh function
 */
window.refreshGPS = async function() {
    try {
        console.log('🔄 Refreshing GPS position...');
        await window.gpsTracker.getCurrentPosition();
        return true;
    } catch (error) {
        console.error('GPS refresh failed:', error);
        return false;
    }
};

/**
 * Start GPS tracking manually
 */
window.startGPSTracking = function(interval = 30000) {
    window.gpsTracker.startTracking(interval);
};

/**
 * Stop GPS tracking manually
 */
window.stopGPSTracking = function() {
    window.gpsTracker.stopTracking();
};