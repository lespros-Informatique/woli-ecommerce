<?php require_once '../public/inc/header.php';?>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="<?=RACINE?>assets/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
<?php require_once '../public/inc/nav.php';?>

    <!-- end header section -->
  </div>

  <!-- food section -->

  <section class="food_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Notre Menu
        </h2>
      </div>

      <ul class="filters_menu">
        <li class="active" data-filter="*">Tous</li>
        <?php foreach($categories as $category): ?>
        <li data-filter=".<?= strtolower(str_replace(' ', '', $category['nom'])) ?>"><?= $category['nom'] ?></li>
        <?php endforeach; ?>
      </ul>

      <div class="filters-content">
        <div class="row grid">
          <?php foreach($plats as $plat): ?>
          <div class="col-sm-6 col-lg-4 all <?= strtolower(str_replace(' ', '', $plat['categorie_nom'])) ?>">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="<?=RACINE_EXTERNE.''.$plat['image'] ?: 'f1.png' ?>" alt="<?= $plat['nom'] ?>">
                </div>
                <div class="detail-box">
                  <h5>
                    <?= $plat['nom'] ?>
                  </h5>
                  <p>
                    <?= $plat['description'] ?: 'Délicieux plat à découvrir.' ?>
                  </p>
                  <div class="options">
                    <h6>
                      <?= number_format($plat['prix'], 0, ',', ' ') ?> FCFA
                    </h6>
                    <a href="#" onclick="addToCart(<?= $plat['id_plat'] ?>, '<?= addslashes($plat['nom']) ?>', <?= $plat['prix'] ?>, '<?= $plat['image'] ?: 'f1.png' ?>')">
                      <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                        <g>
                          <g>
                            <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                         c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                          </g>
                        </g>
                        <g>
                          <g>
                            <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                         C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                         c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                         C457.728,97.71,450.56,86.958,439.296,84.91z" />
                          </g>
                        </g>
                        <g>
                          <g>
                            <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                         c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                          </g>
                        </g>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- end food section -->

<?php require_once '../public/inc/footer.php';?>
<!-- Cart functionality now loaded globally via footer.php -->