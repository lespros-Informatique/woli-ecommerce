<?php require_once '../public/inc/header.php'; ?>

<!-- GPS Simple Direct - Approche Simplifiée -->
<script src="<?= RACINE ?>assets/js/gps-simple-direct.js"></script>

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
          Votre Panier
        </h2>
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

              <?php if (!empty($promotion_info) && $montant_reduction > 0): ?>
                <div class="d-flex justify-content-between mb-2 text-success">
                  <span>
                    <i class="fa fa-tag"></i> Réduction (<?= htmlspecialchars($promotion_info['titre']) ?>
                    -<?= $promotion_info['pourcentage_reduction'] ?>%)
                  </span>
                  <span>-<?= number_format($montant_reduction, 0, ',', ' ') ?> FCFA</span>
                </div>
              <?php endif; ?>

              <div class="d-flex justify-content-between mb-2">
                <span>Frais de livraison</span>
                <span id="frais-livraison-display" class="<?= $frais_livraison > 0 ? 'text-danger' : 'text-success' ?>">
                  <?= $frais_livraison > 0 ? number_format($frais_livraison, 0, ',', ' ') . ' FCFA' : 'Gratuit' ?>
                </span>
              </div>

              <hr>

              <div class="d-flex justify-content-between mb-3">
                <strong>Total</strong>
                <strong id="cart-total-final" class="cart-total"><?= number_format($total_final, 0, ',', ' ') ?>
                  FCFA</strong>
              </div>


              <div class="form-group">
                <label for="type_commande">Type de commande</label>
                <select class="form-control" id="type_commande" onchange="toggleDeliveryFields()">
                  <?php if ($livraison_active): ?>
                    <option value="livraison">🚚 Livraison</option>
                    <option value="à emporter">🏪 À emporter</option>
                  <?php else: ?>
                    <option value="à emporter">🏪 À emporter</option>
                  <?php endif; ?>
                </select>
                <?php if (!$livraison_active): ?>
                  <small class="text-muted">
                    <i class="fa fa-info-circle"></i> La livraison n'est pas disponible actuellement
                  </small>
                <?php endif; ?>
              </div>

              <div class="form-group" id="adresse_group"
                style="display: <?= $livraison_active && $type_commande === 'livraison' ? 'block' : 'none' ?>">
                <label for="adresse_livraison">Adresse de livraison</label>
                <textarea class="form-control" id="adresse_livraison" rows="3"
                  placeholder="Entrez votre adresse de livraison complète"></textarea>
              </div>

              <div class="form-group">
                <label for="instructions">Instructions spéciales (optionnel)</label>
                <textarea class="form-control" id="instructions" rows="2"
                  placeholder="Instructions pour la livraison"></textarea>
              </div>

              <div class="form-group">
                <label for="methode_paiement">Méthode de paiement</label>
                <select class="form-control" id="methode_paiement">
                  <option value="à la livraison">💵 Paiement à la livraison</option>
                </select>
                <small class="text-muted">
                  <i class="fa fa-info-circle"></i> Vous paierez en espèces lors de la réception de votre commande
                </small>
              </div>

              <!-- GPS Status Indicator -->
              <div class="form-group">
                <label>Suivi GPS de livraison</label>
                <div id="gps-status" class="text-muted">
                  <i class="fas fa-satellite-dish"></i> Initialisation GPS...
                </div>
                <small class="text-muted">
                  <i class="fa fa-info-circle"></i> Votre position sera utilisée pour le suivi en temps réel
                </small>
              </div>

              <button type="button" class="btn btn-success btn-block btn-lg mt-3" onclick="checkoutWithGPS()"
                <?= empty($cart_details) ? 'disabled' : '' ?>>
                <i class="fa fa-check"></i> Passer la commande
              </button>

              <div class="text-center mt-3">
                <small class="text-muted">
                  <i class="fa fa-shield-alt"></i> Paiement sécurisé
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

  <!-- Cart Styles -->
  <link rel="stylesheet" href="<?= RACINE ?>json/cart-styles.css">

  <!-- Cart Management Scripts -->
  <script src="<?= RACINE ?>json/cart-quantity-fix.js"></script>

  <script>
    // Frais de livraison depuis PHP
    const FRAIS_LIVRAISON = <?= $frais_livraison ?>;
    const LIVRAISON_ACTIVE = <?= $livraison_active ? 'true' : 'false' ?>;
    const SOUS_TOTAL = <?= $cart_total ?>;
    const MONTANT_REDUCTION = <?= $montant_reduction ?>;

    // Toggle delivery fields based on order type
    function toggleDeliveryFields() {
      const typeCommande = document.getElementById('type_commande').value;
      const adresseGroup = document.getElementById('adresse_group');
      const gpsGroup = document.querySelector('.form-group:has(#gps-status)');
      const adresseField = document.getElementById('adresse_livraison');

      // Afficher/masquer les champs
      if (typeCommande === 'à emporter') {
        adresseGroup.style.display = 'none';
        if (gpsGroup) gpsGroup.style.display = 'none';
        adresseField.required = false;
        adresseField.value = 'À emporter au restaurant';
      } else {
        adresseGroup.style.display = 'block';
        if (gpsGroup) gpsGroup.style.display = 'block';
        adresseField.required = true;
        if (adresseField.value === 'À emporter au restaurant') {
          adresseField.value = '';
        }
      }

      // Recalculer les frais dynamiquement
      updateDeliveryFees(typeCommande);
    }

    // Mettre à jour les frais de livraison dynamiquement
    function updateDeliveryFees(typeCommande) {
      const fraisDisplay = document.getElementById('frais-livraison-display');
      const totalElement = document.getElementById('cart-total-final');

      let frais = 0;
      let total = SOUS_TOTAL - MONTANT_REDUCTION;

      // Appliquer les frais seulement si livraison
      if (typeCommande === 'livraison' && LIVRAISON_ACTIVE) {
        frais = FRAIS_LIVRAISON;
        total += frais;
      }

      // Mettre à jour l'affichage des frais
      if (fraisDisplay) {
        if (frais > 0) {
          fraisDisplay.className = 'text-danger';
          fraisDisplay.textContent = frais.toLocaleString('fr-FR') + ' FCFA';
        } else {
          fraisDisplay.className = 'text-success';
          fraisDisplay.textContent = 'Gratuit';
        }
      }

      // Mettre à jour le total
      if (totalElement) {
        totalElement.textContent = total.toLocaleString('fr-FR') + ' FCFA';
      }
    }
    
    // Clear cart function with SweetAlert
    function clearCart() {
      Swal.fire({
        title: 'Vider le panier ?',
        text: 'Êtes-vous sûr de vouloir supprimer tous les articles ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, tout vider',
        cancelButtonText: 'Annuler'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: LINK + 'cart/clear',
            type: 'POST',
            success: function(response) {
              let result = typeof response === 'string' ? JSON.parse(response) : response;
              if (result.status == 1) {
                location.reload();
              } else {
                Swal.fire('Erreur', result.msg || 'Erreur lors du vidage', 'error');
              }
            },
            error: function() {
              Swal.fire('Erreur', 'Erreur de connexion', 'error');
            }
          });
        }
      });
    }
    
    // Remove item from cart function with SweetAlert
    function removeFromCart(platId) {
      Swal.fire({
        title: 'Supprimer l\'article ?',
        text: 'Voulez-vous retirer cet article du panier ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: LINK + 'cart/remove',
            type: 'POST',
            data: { plat_id: platId },
            success: function(response) {
              let result = typeof response === 'string' ? JSON.parse(response) : response;
              if (result.status == 1) {
                location.reload();
              } else {
                Swal.fire('Erreur', result.msg || 'Erreur lors de la suppression', 'error');
              }
            },
            error: function() {
              Swal.fire('Erreur', 'Erreur de connexion', 'error');
            }
          });
        }
      });
    }

    // Global GPS checkout function - GPS Simple Direct
    async function checkoutWithGPS() {
      const typeCommande = document.getElementById('type_commande').value;
      const adresse = document.getElementById('adresse_livraison').value.trim();
      const instructions = document.getElementById('instructions').value.trim();
      const methodePaiement = document.getElementById('methode_paiement').value;
      const LIEN_GPS = window.location.origin + '/resto/public/';

      // Validate form
      if (typeCommande === 'livraison' && !adresse) {
        Swal.fire({
          icon: 'warning',
          title: 'Adresse requise',
          text: 'Veuillez saisir votre adresse de livraison',
          confirmButtonText: 'Compris'
        });
        return;
      }

      // Show loading
      const button = document.querySelector('button[onclick="checkoutWithGPS()"]');
      const originalText = button.innerHTML;
      button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Récupération GPS...';
      button.disabled = true;

      try {
        console.log('🚀 Starting checkout with GPS Simple Direct...');

        // Get GPS coordinates using simple direct approach
        let gpsData = { client_latitude: null, client_longitude: null };

        if (window.getGPSForOrderSimple) {
          const coords = await window.getGPSForOrderSimple();

          if (coords.client_latitude && coords.client_longitude) {
            gpsData.client_latitude = coords.client_latitude;
            gpsData.client_longitude = coords.client_longitude;
            console.log('✅ GPS Simple Direct coordinates:', coords);

            // Save position to server (as per your suggestion)
            if (window.saveGPSPosition) {
              try {
                await window.saveGPSPosition();
                console.log('📤 GPS position saved to server');
              } catch (saveError) {
                console.warn('⚠️ GPS save failed, continuing anyway:', saveError);
              }
            }

          } else {
            console.log('⚠️ No GPS available, continuing without GPS');
            gpsData.client_latitude = null;
            gpsData.client_longitude = null;
          }
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('type_commande', typeCommande);
        formData.append('adresse_livraison', adresse);
        formData.append('instructions', instructions);
        formData.append('methode_paiement', methodePaiement);

        // Add GPS data if available
        if (gpsData.client_latitude && gpsData.client_longitude) {
          formData.append('client_latitude', gpsData.client_latitude);
          formData.append('client_longitude', gpsData.client_longitude);
          console.log('📤 GPS data included in order');
        } else {
          console.log('📤 No GPS data - order without location');
        }

        // Update button
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Envoi commande...';

        // Debug form data
        for (let [key, value] of formData.entries()) {
          console.log(`📋 Form data: ${key} = ${value}`);
        }

        // Send AJAX request
        const response = await fetch(LIEN_GPS + 'commandes/createFromCart', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();
        console.log('📥 Server response:', result);

        if (result.status === 1) {
          console.log('✅ Order created successfully:', result);

          // Show success message
          const gpsMessage = gpsData.client_latitude ?
            `<div class="text-success"><i class="fas fa-satellite-dish"></i> GPS activé<br><small>Coordonnées: ${gpsData.client_latitude}, ${gpsData.client_longitude}</small></div>` :
            '';

          Swal.fire({
            icon: 'success',
            title: 'Commande confirmée !',
            html: `
                    <p>Votre commande a été créée avec succès.</p>
                    <p><strong>Numéro:</strong> ${result.data.code_commande}</p>
                    <p><strong>Total:</strong> ${result.data.total} FCFA</p>
                    ${gpsMessage}
                `,
            confirmButtonText: 'Voir mes commandes',
            allowOutsideClick: false
          }).then((willRedirect) => {
            if (willRedirect.isConfirmed) {
              window.location.href = '../commandes';
            }
          });

          // Cart is already cleared server-side, no need to call clearCart()

        } else {
          throw new Error(result.msg || 'Erreur lors de la création de la commande');
        }

      } catch (error) {
        console.error('❌ Checkout error:', error);

        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: error.message || 'Une erreur est survenue lors de la création de la commande',
          confirmButtonText: 'Réessayer'
        });

      } finally {
        // Restore button
        button.innerHTML = originalText;
        button.disabled = false;
      }
    }

    // Enhanced quantity management functions
    function decrementQuantity(platId) {
      const button = document.querySelector(`.cart-item-${platId} .minus-btn`);
      const input = document.querySelector(`.cart-item-${platId} .quantity-input`);
      const currentQuantity = parseInt(input.value) || 0;

      if (currentQuantity <= 1) {
        // Ask to remove item if quantity is 1 or less
        if (confirm('Voulez-vous supprimer cet article du panier ?')) {
          removeFromCart(platId);
        }
        return;
      }

      const newQuantity = currentQuantity - 1;
      updateCartQuantity(platId, newQuantity);
    }

    function incrementQuantity(platId) {
      const input = document.querySelector(`.cart-item-${platId} .quantity-input`);
      const currentQuantity = parseInt(input.value) || 0;
      const newQuantity = currentQuantity + 1;
      updateCartQuantity(platId, newQuantity);
    }

    // Initialize everything when page loads
    $(document).ready(function () {
      console.log('Enhanced cart page initialized with GPS');

      // Update navigation cart count
      initializeCartCount();

      // Add visual feedback for quantity buttons
      $('.quantity-btn').on('click', function () {
        const btn = $(this);
        btn.addClass('active');
        setTimeout(() => btn.removeClass('active'), 200);
      });

      // Update GPS status display
      setTimeout(function () {
        const gpsStatus = document.getElementById('gps-status');
        if (gpsStatus && gpsStatus.innerHTML.includes('Initialisation')) {
          gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> GPS en cours d\'initialisation...';
        }
      }, 2000);
    });

    // Initialize navigation cart count
    function initializeCartCount() {
      $.ajax({
        url: LINK + 'cart/getCount',
        type: 'POST',
        success: function (rep) {
          let response = JSON.parse(rep);
          if (response.status == 1) {
            updateNavigationCartCount(response.data.cart_count);
          }
        }
      });
    }

    // Backup GPS function (for testing)
    function testGPSFunction() {
      console.log('🧪 Testing GPS function...');
      if (window.gpsTracker) {
        window.gpsTracker.getCurrentPosition()
          .then(position => {
            console.log('✅ Test GPS success:', position);
            alert(`GPS Test Success!\nLat: ${position.latitude}\nLng: ${position.longitude}`);
          })
          .catch(error => {
            console.error('❌ Test GPS failed:', error);
            alert(`GPS Test Failed: ${error.message}`);
          });
      } else {
        alert('GPS Tracker not available');
      }
    }
  </script>

</body>

</html>