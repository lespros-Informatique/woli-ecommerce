<?php
// Récupérer les paramètres du restaurant
$modelSettings = new ModelSettings();
$settings = $modelSettings->getAllSettings();
$heuresGrouped = $modelSettings->getGroupedHeures();

// Extraire les valeurs
$nomRestaurant = $settings['nom_restaurant'] ?? 'Resto Delicieux';
$telephone = $settings['telephone'] ?? '+225 00 00 00 00';
$email = $settings['email'] ?? 'contact@resto.com';
$adresse = $settings['adresse'] ?? 'Adresse non disponible';
$description = $settings['description'] ?? 'Description non disponible';
$logo = $settings['logo'] ?? '';
?>

<!-- footer section -->
<footer class="footer_section">
  <div class="container">
    <div class="row">
      <div class="col-md-4 footer-col">
        <div class="footer_contact">
          <h4>
            Contactez-nous
          </h4>
          <div class="contact_link_box">
            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                <?= htmlspecialchars($adresse) ?>
              </span>
            </a>
            <a href="tel:<?= htmlspecialchars($telephone) ?>">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                <?= htmlspecialchars($telephone) ?>
              </span>
            </a>
            <a href="mailto:<?= htmlspecialchars($email) ?>">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                <?= htmlspecialchars($email) ?>
              </span>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-4 footer-col">
        <div class="footer_detail">
          <?php if (!empty($logo) && file_exists('../' . $logo)): ?>
            <a href="<?= RACINE ?>" class="footer-logo">
              <img src="<?= RACINE . str_replace('public/', '', $logo) ?>" alt="<?= htmlspecialchars($nomRestaurant) ?>"
                style="max-height: 60px; margin-bottom: 15px;">
            </a>
          <?php else: ?>
            <a href="<?= RACINE ?>" class="footer-logo">
              <?= htmlspecialchars($nomRestaurant) ?>
            </a>
          <?php endif; ?>
          <p>
            <?= htmlspecialchars($description) ?>
          </p>
          <div class="footer_social">
            <a href="">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-linkedin" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-instagram" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-pinterest" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-4 footer-col">
        <h4>
          Heures d'ouverture
        </h4>
        <?php if (!empty($heuresGrouped)): ?>
          <?php foreach ($heuresGrouped as $groupe): ?>
            <p>
              <strong><?= htmlspecialchars($groupe['jours']) ?>:</strong><br>
              <?= htmlspecialchars($groupe['horaire']) ?>
            </p>
          <?php endforeach; ?>
        <?php else: ?>
          <p>
            Tous les jours
          </p>
          <p>
            10h00 - 22h00
          </p>
        <?php endif; ?>
      </div>
    </div>
    <div class="footer-info">
      <p>
        &copy; <span id="displayYear"></span> Tous droits réservés - <?= htmlspecialchars($nomRestaurant) ?>
      </p>
    </div>
  </div>
</footer>
<!-- footer section -->

<!-- jQery -->
<script src="<?= RACINE ?>assets/js/jquery-3.4.1.min.js"></script>
<script src="<?= RACINE ?>json/func.js"></script>
<script>
  // Initialize cart count on page load - this runs after func.js is loaded
  $(document).ready(function () {
    // Initialize immediately with 0, then try to get real count
    updateCartIcon(0);

    // Also add a fallback timer to ensure cart is updated even if AJAX fails
    setTimeout(function () {
      if (typeof updateCartIcon === 'function') {
        // Try to get cart count from sessionStorage first (for immediate display)
        const storedCount = sessionStorage.getItem('cart_count');
        if (storedCount) {
          updateCartIcon(parseInt(storedCount));
        }
      }
    }, 100);

    // Get initial cart count from server (non-blocking)
    setTimeout(function () {
      $.ajax({
        url: '<?= RACINE ?>cart/getCount',
        type: 'POST',
        success: function (rep) {
          try {
            // Handle both string and object responses
            let response = typeof rep === 'string' ? JSON.parse(rep) : rep;
            console.log('Cart response processed:', response);

            if (response.status == 1 && response.data && response.data.cart_count !== undefined) {
              const count = response.data.cart_count;
              console.log('Setting cart count to:', count);
              updateCartIcon(count);
              // Store in sessionStorage for immediate access
              sessionStorage.setItem('cart_count', count);
            } else {
              console.log('Invalid response structure:', response);
            }
          } catch (e) {
            console.log('Cart count response error:', e);
          }
        },
        error: function (xhr, status, error) {
          console.log('Cart count AJAX error:', error);
          // Use stored count if available, otherwise keep 0
          const storedCount = sessionStorage.getItem('cart_count');
          if (storedCount) {
            updateCartIcon(parseInt(storedCount));
          }
        }
      });
    }, 200);
  });

</script>
<script src="<?= RACINE ?>json/ajax.js"></script>
<script src="<?= RACINE ?>json/cart-manager.js"></script>

<!-- popper js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
  integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
<!-- bootstrap js -->
<script src="<?= RACINE ?>assets/js/bootstrap.js"></script>
<!-- owl slider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
</script>
<!-- isotope js -->
<script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
<!-- nice select -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
<!-- custom js -->
<script src="<?= RACINE ?>assets/js/custom.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Google Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
</script>
<!-- End Google Map -->

</body>

</html>