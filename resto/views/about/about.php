<?php require_once '../public/inc/header.php'; ?>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="<?= RACINE ?>assets/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <?php require_once '../public/inc/nav.php'; ?>

    <!-- end header section -->
  </div>

  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container  ">
      <?php
      // Récupérer les paramètres du restaurant
      $modelSettings = new ModelSettings();
      $settings = $modelSettings->getAllSettings();

      // Extraire les valeurs
      $nomRestaurant = $settings['nom_restaurant'] ?? 'Resto Delicieux';
      $description = $settings['description'] ?? 'Bienvenue chez Resto Delicieux, où chaque repas est une célébration de la gastronomie.';
      $logo = $settings['logo'] ?? '';
      ?>

      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <?php if (!empty($logo) && file_exists('../' . $logo)): ?>
              <img src="<?= RACINE . str_replace('public/', '', $logo) ?>" alt="<?= htmlspecialchars($nomRestaurant) ?>">
            <?php else: ?>
              <img src="<?= RACINE ?>assets/images/about-img.png" alt="">
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Nous sommes <?= htmlspecialchars($nomRestaurant) ?>
              </h2>
            </div>
            <p>
              <?= htmlspecialchars($description) ?>
            </p>
            <a href="<?= RACINE ?>menu">
              Voir notre menu
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <?php require_once '../public/inc/footer.php'; ?>