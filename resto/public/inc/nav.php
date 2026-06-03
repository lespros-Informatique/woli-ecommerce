<style>
  .header_section {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background: #131212ff;
    /* ou ta couleur */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    /* optionnel */
  }

  /* Cart icon animation */
  .bounce-animation {
    animation: cartBounce 0.6s ease-in-out;
  }

  @keyframes cartBounce {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.2);
    }

    100% {
      transform: scale(1);
    }
  }

  /* Cart count pulse effect */
  .cart-count.pulse {
    animation: pulse 0.6s ease-in-out;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.3);
    }

    100% {
      transform: scale(1);
    }
  }
</style>
<header class="header_section">
  <div class="container">
    <nav class="navbar navbar-expand-lg custom_nav-container ">
      <a class="navbar-brand" href="<?= RACINE ?>">
        <span>
          Resto
        </span>
        <span class="text-warning">
          Delicieux
        </span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class=""> </span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav  mx-auto ">
          <li class="nav-item active">
            <a class="nav-link" href="<?= RACINE ?>">Accueil <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE ?>menu">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE ?>about">À propos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE ?>contact">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link cart-icon position-relative" href="<?= RACINE ?>menu/panier">
              <i class="fa fa-shopping-cart"></i>
              <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="display: none;">0</span>
            </a>
          </li>
          <?php if (USER_NAME) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= RACINE ?>commandes">Mes Commandes</a>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= RACINE ?>clients/login">
                <i class="fa fa-user"></i></a>
            </li>
          <?php } ?>
        </ul>
        <small class="text-success"> <?= USER_NAME ?></small>
      </div>
    </nav>
  </div>
</header>

