<?php require_once '../public/inc/header.php'; ?>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="<?=RACINE?>assets/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <?php require_once '../public/inc/nav.php'; ?>
    <!-- end header section -->
  </div>

  <!-- contact section -->
  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Contactez-Nous
        </h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form_container">
            <form id="contactForm">
              <div>
                <input type="text" class="form-control" placeholder="Votre Nom" name="nom" id="nom" required />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Votre Email" name="email" id="email" required />
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Sujet" name="sujet" id="sujet" required />
              </div>
              <div>
                <textarea class="form-control" placeholder="Votre Message" name="message" id="message" rows="5" required></textarea>
              </div>
              <div class="btn_box">
                <button type="submit" id="submitBtn">
                  Envoyer le Message
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="contact_info_container">
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
            ?>
            <div class="contact_info_box">
              <h3>Informations de Contact</h3>
              
              <div class="info_item">
                <div class="icon_box">
                  <i class="fa fa-map-marker"></i>
                </div>
                <div class="text_box">
                  <h5>Adresse</h5>
                  <p><?= htmlspecialchars($adresse) ?></p>
                </div>
              </div>

              <div class="info_item">
                <div class="icon_box">
                  <i class="fa fa-phone"></i>
                </div>
                <div class="text_box">
                  <h5>Téléphone</h5>
                  <p><a href="tel:<?= htmlspecialchars($telephone) ?>" style="color: inherit; text-decoration: none;"><?= htmlspecialchars($telephone) ?></a></p>
                </div>
              </div>

              <div class="info_item">
                <div class="icon_box">
                  <i class="fa fa-envelope"></i>
                </div>
                <div class="text_box">
                  <h5>Email</h5>
                  <p><a href="mailto:<?= htmlspecialchars($email) ?>" style="color: inherit; text-decoration: none;"><?= htmlspecialchars($email) ?></a></p>
                </div>
              </div>

              <div class="info_item">
                <div class="icon_box">
                  <i class="fa fa-clock-o"></i>
                </div>
                <div class="text_box">
                  <h5>Horaires d'ouverture</h5>
                  <?php if (!empty($heuresGrouped)): ?>
                    <p>
                      <?php foreach ($heuresGrouped as $index => $groupe): ?>
                        <strong><?= htmlspecialchars($groupe['jours']) ?>:</strong> <?= htmlspecialchars($groupe['horaire']) ?><?= $index < count($heuresGrouped) - 1 ? '<br>' : '' ?>
                      <?php endforeach; ?>
                    </p>
                  <?php else: ?>
                    <p>
                      <strong>Lundi - Vendredi:</strong> 11h00 - 23h00<br>
                      <strong>Samedi - Dimanche:</strong> 10h00 - 00h00
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->

  <script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const submitBtn = document.getElementById('submitBtn');
      const originalText = submitBtn.textContent;
      
      // Désactiver le bouton et changer le texte
      submitBtn.disabled = true;
      submitBtn.textContent = 'Envoi en cours...';
      
      // Récupérer les données du formulaire
      const formData = new FormData(this);
      
      // Envoyer la requête AJAX
      fetch('<?=RACINE?>contact/send', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Message envoyé!',
            text: data.message,
            confirmButtonColor: '#ffbe33'
          });
          
          // Réinitialiser le formulaire
          document.getElementById('contactForm').reset();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: data.message,
            confirmButtonColor: '#ffbe33'
          });
        }
      })
      .catch(error => {
        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.',
          confirmButtonColor: '#ffbe33'
        });
      })
      .finally(() => {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
    });
  </script>

<?php require_once '../public/inc/footer.php'; ?>