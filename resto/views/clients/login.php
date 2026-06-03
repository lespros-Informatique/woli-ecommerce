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
<style>
  .error-message{
    color: red;
  }
</style>
  <!-- login section -->

  <section class="about_section layout_padding">
    <div class="container">

      <div class="row">
        <div class="col-md-6 offset-md-3">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Connexion
              </h2>
            </div>
            <div class="mb-3">
              <a href="#" class="btn btn-outline-danger btn-block">
                <i class="fa fa-google"></i> Se connecter avec Google
              </a>
            </div>
            <div class="text-center mb-3">
              <span>Ou</span>
            </div>
            <form action="" method="post" class="formConnexion">
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
              <button type="submit" class="btn btn-primary btn_actions">Se connecter</button>
            </form>
            <p>Pas encore de compte? <a href="<?=RACINE?>clients/register">S'inscrire</a></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end login section -->

<?php require_once '../public/inc/footer.php';?>