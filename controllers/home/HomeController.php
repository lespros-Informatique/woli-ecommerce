<?php

class HomeController
{
    private $validator;
    private $home;
    // // constructeur pour initialiser le validator ici, pour pouvoir l'utiliser dans toutes les méthodes de la classe AccueilController
    public function __construct()
    {
        $this->validator = new Validator();
        $this->home = new ModelHome();
    }

    public function index() // la vue de la connexion
    {

        require_once '../views/home/index.php'; // On inclut la vue d'accueil qui contient le code HTML

    }

}
