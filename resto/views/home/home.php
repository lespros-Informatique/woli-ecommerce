<?php require_once '../public/inc/header.php'; ?>


<body>

  <div class="hero_area">
    <div class="bg-box">
      <img src="<?= RACINE ?>assets/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <?php require_once '../public/inc/nav.php'; ?>

    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Restaurant de Fast Food
                    </h1>
                    <p>
                      Découvrez une expérience culinaire rapide et savoureuse, où chaque bouchée est une explosion de
                      saveurs. Nos plats sont préparés avec soin pour satisfaire vos envies instantanées dans une
                      ambiance conviviale.
                    </p>
                    <div class="btn-box">
                      <a href="<?= RACINE ?>book" class="btn1">
                        Commander Maintenant
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Restaurant Délicieux
                    </h1>
                    <p>
                      Découvrez une expérience culinaire unique où la qualité des ingrédients rencontre l'art de la
                      cuisine. Notre restaurant vous propose des plats savoureux préparés avec passion, dans une
                      ambiance chaleureuse et conviviale. Réservez dès maintenant pour un moment inoubliable.
                    </p>
                    <div class="btn-box">
                      <a href="<?= RACINE ?>book" class="btn1">
                        Commander Maintenant
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Restaurant Délicieux
                    </h1>
                    <p>
                      Découvrez une expérience culinaire unique où la qualité des ingrédients rencontre l'art de la
                      cuisine. Notre restaurant vous propose des plats savoureux préparés avec passion, dans une
                      ambiance chaleureuse et conviviale. Réservez dès maintenant pour un moment inoubliable.
                    </p>
                    <div class="btn-box">
                      <a href="<?= RACINE ?>book" class="btn1">
                        Commander Maintenant
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <ol class="carousel-indicators">
            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
            <li data-target="#customCarousel1" data-slide-to="1"></li>
            <li data-target="#customCarousel1" data-slide-to="2"></li>
          </ol>
        </div>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <!-- offer section -->

  <section class="offer_section layout_padding-bottom">
    <div class="offer_container">
      <div class="container ">
        <?php
        // Récupérer les promotions actives
        $modelPromotions = new ModelPromotions();
        $promotions = $modelPromotions->getActivePromotions();
        ?>

        <?php if (!empty($promotions)): ?>
          <div class="row">
            <?php foreach ($promotions as $promo): ?>
              <div class="col-md-6  ">
                <div class="box ">
                  <div class="img-box">
                    <img src="<?= RACINE_EXTERNE . (!empty($promo['image']) ? $promo['image'] : 'assets/images/o1.jpg') ?>"
                      alt="<?= htmlspecialchars($promo['titre']) ?>">
                  </div>
                  <div class="detail-box">
                    <h5>
                      <?= htmlspecialchars($promo['titre']) ?>
                    </h5>
                    <h6>
                      <span><?= htmlspecialchars($promo['pourcentage_reduction']) ?>%</span> de réduction
                    </h6>
                    <a href="<?= RACINE . ltrim($promo['lien_url'] ?? 'menu', '/') ?>?promo=<?= $promo['id_promotion'] ?>">
                      Commander Maintenant <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029"
                        style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
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
                            <path
                              d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                         c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                          </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="row">
            <div class="col-12 text-center">
              <p>Aucune promotion disponible pour le moment.</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- end offer section -->

  <!-- food section -->

  <section class="food_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Notre Menu
        </h2>
      </div>

      <ul class="filters_menu">
        <li class="active" data-filter="*">Tous</li>
        <?php foreach ($categories as $category): ?>
          <li data-filter=".<?= strtolower(str_replace(' ', '', $category['nom'])) ?>"><?= $category['nom'] ?></li>
        <?php endforeach; ?>
      </ul>

      <div class="filters-content">
        <div class="row grid">
          <?php if (isset($plats) && !empty($plats)): ?>
            <?php foreach ($plats as $plat): ?>
              <div class="col-sm-6 col-lg-4 all <?= strtolower(str_replace(' ', '', $plat['categorie_nom'])) ?>">
                <div class="box">
                  <div>
                    <div class="img-box">
                      <img src="<?= RACINE_EXTERNE . '' . $plat['image'] ?: 'f1.png' ?>" alt="<?= $plat['nom'] ?>">
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
                        <a href="#"
                          onclick="addToCart(<?= $plat['id_plat'] ?>, '<?= addslashes($plat['nom']) ?>', <?= $plat['prix'] ?>, '<?= $plat['image'] ?: 'f1.png' ?>')">
                          <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029"
                            style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
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
                                <path
                                  d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
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
          <?php else: ?>
            <div class="col-12">
              <div class="text-center">
                <h4>Aucun plat disponible pour le moment.</h4>
                <p>Veuillez revenir plus tard ou contacter le restaurant.</p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="btn-box">
        <a href="<?= RACINE ?>menu" class="btn1">
          Voir Plus
        </a>
      </div>
  </section>

  <!-- end food section -->

  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container  ">

      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="<?= RACINE ?>assets/images/about-img.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                We Are Resto Delicieux
              </h2>
            </div>
            <p>
              Bienvenue chez Resto Delicieux, où chaque repas est une célébration de la gastronomie. Notre équipe
              passionnée s'engage à vous offrir une expérience culinaire exceptionnelle, en utilisant des ingrédients
              frais et locaux pour créer des plats savoureux qui raviront vos papilles. Que ce soit pour un dîner
              romantique, une réunion familiale ou un repas entre amis, nous sommes là pour rendre chaque moment
              mémorable.
            </p>
            <a href="<?= RACINE ?>about">
              Read More
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- book section -->
  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Réserver une Table
        </h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form_container">
            <form action="">
              <div>
                <input type="text" class="form-control" placeholder="Your Name" />
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Phone Number" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Your Email" />
              </div>
              <div>
                <select class="form-control nice-select wide">
                  <option value="" disabled selected>
                    How many persons?
                  </option>
                  <option value="">
                    2
                  </option>
                  <option value="">
                    3
                  </option>
                  <option value="">
                    4
                  </option>
                  <option value="">
                    5
                  </option>
                </select>
              </div>
              <div>
                <input type="date" class="form-control">
              </div>
              <div class="btn_box">
                <button>
                  Book Now
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="map_container ">
            <div id="googleMap"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end book section -->

  <!-- client section -->

  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center psudo_white_primary mb_45">
        <h2>
          Ce que disent nos clients
        </h2>
      </div>
      <div class="carousel-wrap row ">
        <div class="owl-carousel client_owl-carousel">
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  Un service impeccable et des plats délicieux ! Chaque visite chez Resto Delicieux est une expérience
                  inoubliable. La qualité des ingrédients et la créativité des recettes font de cet endroit mon préféré.
                </p>
                <h6>
                  Marie Dupont
                </h6>
                <p>
                  Cliente fidèle
                </p>
              </div>
              <div class="img-box">
                <img src="<?= RACINE ?>assets/images/client1.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  Atmosphère chaleureuse et nourriture exceptionnelle. J'adore venir ici pour célébrer les occasions
                  spéciales. Le personnel est toujours attentionné et les plats sont toujours parfaits.
                </p>
                <h6>
                  Jean Martin
                </h6>
                <p>
                  Client régulier
                </p>
              </div>
              <div class="img-box">
                <img src="<?= RACINE ?>assets/images/client2.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end client section -->
  <?php require_once '../public/inc/footer.php'; ?>