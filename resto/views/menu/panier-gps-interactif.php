<?php require_once '../public/inc/header.php'; ?>

<!-- GPS Tracking System avec Carte Interactive -->
<script src="<?= RACINE ?>assets/js/gps-tracking.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
  #map {
    height: 400px;
    width: 100%;
    border-radius: 8px;
    border: 2px solid #007bff;
  }

  .coordinate-display {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    margin: 10px 0;
    font-family: 'Courier New', monospace;
    font-size: 12px;
  }

  .gps-section {
    background: #e3f2fd;
    border: 1px solid #2196f3;
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
  }
</style>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="<?= RACINE ?>assets/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <?php require_once '../public/inc/nav.php'; ?>

    <!-- end header section -->
  </div>

  <!-- cart section -->

  <section class="cart_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Votre Panier avec GPS Interactif
        </h2>
        <p class="text-muted">
          <i class="fas fa-map-marked-alt"></i> Visualisez et corrigez votre position GPS directement
        </p>
      </div>

      <?php if (empty($cart_details)): ?>
        <div class="empty-cart text-center py-5">
          <i class="fa fa-shopping-cart fa-5x text-muted mb-4"></i>
          <h4>Votre panier est vide</h4>
          <p class="text-muted mb-4">Ajoutez des articles pour commencer votre commande</p>
          <a href="<?= RACINE ?>menu" class="btn btn-primary btn-lg">
            <i class="fa fa-utensils"></i> Voir le Menu
          </a>
        </div>
      <?php else: ?>
        <div class="row">
          <div class="col-lg-8">
            <div class="cart-items">
              <?php foreach ($cart_details as $item): ?>
                <div class="cart-item cart-item-<?= $item['id'] ?> bg-white rounded shadow-sm p-3 mb-3">
                  <div class="row align-items-center">
                    <div class="col-md-2">
                      <img src="<?= RACINE_EXTERNE . '' . $item['image'] ?: 'f1.png' ?>" alt="<?= $item['nom'] ?>"
                        class="img-fluid rounded">
                    </div>
                    <div class="col-md-4">
                      <h6 class="mb-1"><?= $item['nom'] ?></h6>
                      <small class="text-muted"><?= $item['categorie_nom'] ?></small>
                    </div>
                    <div class="col-md-2 text-center">
                      <span class="font-weight-bold"><?= number_format($item['prix'], 0, ',', ' ') ?> FCFA</span>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group">
                        <div class="input-group-btn">
                          <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn minus-btn"
                            onclick="decrementQuantity(<?= $item['id'] ?>)" data-plat-id="<?= $item['id'] ?>"
                            data-current-quantity="<?= $item['quantite'] ?>" <?= $item['quantite'] <= 1 ? 'disabled' : '' ?>>
                            <i class="fa fa-minus"></i>
                          </button>
                        </div>
                        <input type="number" class="form-control form-control-sm text-center quantity-input"
                          value="<?= $item['quantite'] ?>" min="1" readonly data-plat-id="<?= $item['id'] ?>">
                        <div class="input-group-btn">
                          <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn plus-btn"
                            onclick="incrementQuantity(<?= $item['id'] ?>)" data-plat-id="<?= $item['id'] ?>"
                            data-current-quantity="<?= $item['quantite'] ?>">
                            <i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-1 text-center">
                      <span class="font-weight-bold"><?= number_format($item['prix_total'], 0, ',', ' ') ?></span>
                    </div>
                    <div class="col-md-1 text-center">
                      <button type="button" class="btn btn-outline-danger btn-sm delete-item-btn"
                        onclick="removeFromCart(<?= $item['id'] ?>)" data-plat-id="<?= $item['id'] ?>"
                        data-plat-nom="<?= htmlspecialchars($item['nom']) ?>" title="Supprimer">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="mt-3">
              <a href="<?= RACINE ?>menu" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left"></i> Continuer les achats
              </a>
              <button type="button" class="btn btn-outline-danger ml-2" onclick="clearCart()">
                <i class="fa fa-trash"></i> Vider le panier
              </button>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="cart-summary bg-white rounded shadow-sm p-4">
              <h5 class="mb-3">Résumé de la commande</h5>

              <div class="d-flex justify-content-between mb-2">
                <span>Sous-total</span>
                <span id="cart-total" class="cart-total"><?= number_format($cart_total, 0, ',', ' ') ?> FCFA</span>
              </div>

              <div class="d-flex justify-content-between mb-2">
                <span>Frais de livraison</span>
                <span class="text-success">Gratuit</span>
              </div>

              <hr>

              <div class="d-flex justify-content-between mb-3">
                <strong>Total</strong>
                <strong id="cart-total-final" class="cart-total"><?= number_format($cart_total, 0, ',', ' ') ?>
                  FCFA</strong>
              </div>

              <div class="form-group">
                <label for="adresse_livraison">Adresse de livraison *</label>
                <textarea class="form-control" id="adresse_livraison" rows="3"
                  placeholder="Ex: Bouaké, Quartier Centre, Rue X..." required></textarea>
              </div>

              <div class="form-group">
                <label for="instructions">Instructions spéciales (optionnel)</label>
                <textarea class="form-control" id="instructions" rows="2"
                  placeholder="Instructions pour la livraison"></textarea>
              </div>

              <div class="form-group">
                <label for="methode_paiement">Méthode de paiement</label>
                <select class="form-control" id="methode_paiement">
                  <option value="à la livraison">À la livraison</option>
                  <option value="en ligne">Paiement en ligne</option>
                </select>
              </div>

              <!-- GPS avec Carte Interactive -->
              <div class="gps-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label><i class="fas fa-map-marker-alt"></i> Position GPS</label>
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="initializeGPSAndMap()">
                    <i class="fas fa-satellite-dish"></i> GPS + Carte
                  </button>
                </div>

                <div id="gps-status" class="text-muted mb-2">
                  <i class="fas fa-clock"></i> Initialisation GPS...
                </div>

                <div id="coordinates-display" class="coordinate-display" style="display: none;">
                  <div id="gps-coords">GPS: En cours...</div>
                  <div id="corrected-coords" style="display: none; color: #28a745;">✓ Corrigé: En cours...</div>
                </div>

                <small class="text-muted">
                  <i class="fa fa-info-circle"></i> Cliquez sur la carte pour corriger votre position
                </small>

                <!-- Carte intégrée -->
                <div id="map" style="margin-top: 10px; display: none;"></div>
              </div>

              <button type="button" class="btn btn-success btn-block btn-lg mt-3" onclick="checkoutWithInteractiveGPS()"
                <?= empty($cart_details) ? 'disabled' : '' ?>>
                <i class="fa fa-check"></i> Passer la commande avec GPS
              </button>

              <div class="text-center mt-3">
                <small class="text-muted">
                  <i class="fa fa-shield-alt"></i> Paiement sécurisé avec suivi GPS
                </small>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- end cart section -->

  <?php require_once '../public/inc/footer.php'; ?>

  <!-- Scripts Leaflet -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- Cart Styles -->
  <link rel="stylesheet" href="<?= RACINE ?>json/cart-styles.css">

  <!-- Cart Management Scripts -->
  <script src="<?= RACINE ?>json/cart-manager.js"></script>
  <script src="<?= RACINE ?>json/cart-quantity-fix.js"></script>

  <script>
    // Variables globales pour la carte GPS
    let map;
    let gpsMarker;
    let correctedMarker;
    let gpsData = { latitude: null, longitude: null, timestamp: null, accuracy: null };
    let correctedData = { latitude: null, longitude: null, timestamp: null };

    // Fonction d'initialisation GPS et Carte
    async function initializeGPSAndMap() {
      log("🔍 Initialisation GPS + Carte interactive...");

      const gpsStatus = document.getElementById('gps-status');
      gpsStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Récupération GPS...';

      try {
        // Obtenir la position GPS
        const position = await new Promise((resolve, reject) => {
          navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 15000
          });
        });

        gpsData.latitude = position.coords.latitude;
        gpsData.longitude = position.coords.longitude;
        gpsData.accuracy = position.coords.accuracy;
        gpsData.timestamp = new Date().toISOString();

        log(`✅ GPS récupéré: ${gpsData.latitude}, ${gpsData.longitude} (±${gpsData.accuracy}m)`);

        // Afficher les coordonnées
        document.getElementById('gps-coords').textContent =
          `📍 GPS: ${gpsData.latitude}, ${gpsData.longitude} (±${gpsData.accuracy}m)`;

        gpsStatus.innerHTML = '<i class="fas fa-check-circle text-success"></i> GPS activé';

        // Initialiser et afficher la carte
        initializeMap();
        showOnMap(gpsData.latitude, gpsData.longitude, 'gps');

        // Afficher la section des coordonnées
        document.getElementById('coordinates-display').style.display = 'block';

      } catch (error) {
        log(`❌ Erreur GPS: ${error.message}`);
        gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Erreur GPS';

        // Afficher la carte quand même pour saisie manuelle
        initializeMap();
        document.getElementById('coordinates-display').style.display = 'block';
      }
    }

    // Initialiser la carte
    function initializeMap() {
      const mapElement = document.getElementById('map');
      mapElement.style.display = 'block';

      if (!map) {
        // Centrer sur Abidjan par défaut
        map = L.map('map').setView([5.3599, -4.0083], 8);

        // Ajouter les tuiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Gestionnaire de clic
        map.on('click', function (e) {
          const lat = e.latlng.lat;
          const lng = e.latlng.lng;

          log(`🖱️ Clic sur carte: ${lat}, ${lng}`);

          // Marquer comme position corrigée
          correctedData.latitude = lat;
          correctedData.longitude = lng;
          correctedData.timestamp = new Date().toISOString();

          showOnMap(lat, lng, 'corrected');
          updateCoordinatesDisplay();

          log("✅ Position corrigée par l'utilisateur");
        });

        log("🗺️ Carte initialisée");
      }
    }

    // Afficher les marqueurs sur la carte
    function showOnMap(lat, lng, type) {
      const color = type === 'gps' ? 'red' : 'blue';
      const label = type === 'gps' ? 'Position GPS' : 'Position Corrigée';

      // Supprimer l'ancien marqueur
      if (type === 'gps' && gpsMarker) {
        map.removeLayer(gpsMarker);
      }
      if (type === 'corrected' && correctedMarker) {
        map.removeLayer(correctedMarker);
      }

      // Créer le nouveau marqueur
      const marker = L.circleMarker([lat, lng], {
        radius: 12,
        fillColor: color,
        color: '#fff',
        weight: 3,
        opacity: 1,
        fillOpacity: 0.8
      }).addTo(map);

      marker.bindPopup(`<strong>${label}</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);

      if (type === 'gps') {
        gpsMarker = marker;
      } else {
        correctedMarker = marker;
      }

      // Centrer la carte
      map.setView([lat, lng], 13);
    }

    // Mettre à jour l'affichage des coordonnées
    function updateCoordinatesDisplay() {
      const correctedDiv = document.getElementById('corrected-coords');

      if (correctedData.latitude && correctedData.longitude) {
        correctedDiv.style.display = 'block';
        correctedDiv.textContent =
          `✓ Corrigé: ${correctedData.latitude}, ${correctedData.longitude}`;
      } else {
        correctedDiv.style.display = 'none';
      }
    }

    // Fonction de checkout améliorée avec GPS interactif
    async function checkoutWithInteractiveGPS() {
      const adresse = document.getElementById('adresse_livraison').value.trim();
      const instructions = document.getElementById('instructions').value.trim();
      const methodePaiement = document.getElementById('methode_paiement').value;

      // Validation
      if (!adresse) {
        Swal.fire({
          icon: 'warning',
          title: 'Adresse requise',
          text: 'Veuillez saisir votre adresse de livraison',
          confirmButtonText: 'Compris'
        });
        return;
      }

      // Bouton de chargement
      const button = document.querySelector('button[onclick="checkoutWithInteractiveGPS()"]');
      const originalText = button.innerHTML;
      button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Traitement...';
      button.disabled = true;

      try {
        log("🚀 Démarrage checkout avec GPS interactif");

        // Déterminer les coordonnées finales
        const finalLat = correctedData.latitude || gpsData.latitude;
        const finalLng = correctedData.longitude || gpsData.longitude;

        // Préparer les données
        const formData = new FormData();
        formData.append('adresse_livraison', adresse);
        formData.append('instructions', instructions);
        formData.append('methode_paiement', methodePaiement);

        // Ajouter les coordonnées GPS si disponibles
        if (finalLat && finalLng) {
          formData.append('client_latitude', finalLat);
          formData.append('client_longitude', finalLng);
          formData.append('gps_source', correctedData.latitude ? 'CORRECTED' : 'AUTO');
          log(`📍 Coordonnées GPS: ${finalLat}, ${finalLng} (${correctedData.latitude ? 'CORRIGÉ' : 'AUTO'})`);
        } else {
          log("⚠️ Aucune coordonnée GPS disponible");
        }

        // Envoyer la requête
        const response = await fetch('commandes/createFromCart', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();
        log("📥 Réponse serveur:", result);

        if (result.status === 1) {
          log("✅ Commande créée avec succès");

          Swal.fire({
            icon: 'success',
            title: 'Commande confirmée !',
            html: `
                    <p>Votre commande a été créée avec succès.</p>
                    <p><strong>Numéro:</strong> ${result.data.code_commande}</p>
                    <p><strong>Total:</strong> ${result.data.total} FCFA</p>
                    <p><strong>GPS:</strong> ${result.data.gps_enabled ? 'Activé' : 'Désactivé'}</p>
                    ${result.data.client_coordinates ?
                `<p><strong>Position:</strong> ${result.data.client_coordinates.latitude}, ${result.data.client_coordinates.longitude}</p>` : ''}
                `,
            confirmButtonText: ''
          }).then(() => {
            window.location.href = 'commandes';
          });

        } else {
          throw new Error(result.msg || 'Erreur lors de la création');
        }

      } catch (error) {
        log(`❌ Erreur checkout: ${error.message}`);

        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: error.message || 'Une erreur est survenue',
          confirmButtonText: 'Réessayer'
        });

      } finally {
        button.innerHTML = originalText;
        button.disabled = false;
      }
    }

    // Logging function
    function log(message, data = null) {
      const timestamp = new Date().toLocaleTimeString();
      console.log(`[${timestamp}] ${message}`, data);
    }

    // Initialisation de la page
    document.addEventListener('DOMContentLoaded', function () {
      log("🛒 Panier avec GPS interactif initialisé");

      // Initialiser le compteur du panier
      initializeCartCount();

      // Initialiser automatiquement le GPS et la carte après 2 secondes
      setTimeout(() => {
        log("🔄 Auto-initialisation GPS après 2s");
        initializeGPSAndMap();
      }, 2000);
    });
  </script>

</body>

</html>