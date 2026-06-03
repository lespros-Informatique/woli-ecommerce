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
<style>
  .error-message{
    color: red;
  }
</style>
  <!-- register section -->

  <section class="about_section layout_padding">
    <div class="container">

      <div class="row">
        <div class="col-md-6 offset-md-3">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Inscription
              </h2>
            </div>
            <div class="mb-3">
              <a href="#" class="btn btn-outline-danger btn-block">
                <i class="fa fa-google"></i> S'inscrire avec Google
              </a>
            </div>
            <div class="text-center mb-3">
              <span>Ou</span>
            </div>
            <form action="" method="post" class="formRegister">
              <div class="form-group">
                <label for="nom">Nom et prénoms:</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom">
                <div class="error-message" id="nomError"></div>

              </div>
              <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="text" class="form-control" id="tel" name="tel" placeholder="Entrez votre telephone">
                <div class="error-message" id="telError"></div>

              </div>
              <div class="form-group">
                <label for="adresse">Adresse:</label>
                <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Entrez votre adresse">
                <div class="error-message" id="adresseError"></div>

              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email">
                <div class="error-message" id="emailError"></div>


              </div>
              <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Choisissez un mot de passe">
                <div class="error-message" id="passwordError"></div>


              </div>
              <button type="submit" class="btn btn-primary btn_actions">S'inscrire</button>
            </form>
            <p>Déjà un compte? <a href="<?= RACINE ?>clients/login">Se connecter</a></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end register section -->

  <?php require_once '../public/inc/footer.php'; ?>