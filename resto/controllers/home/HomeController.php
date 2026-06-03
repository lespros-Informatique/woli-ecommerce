<?php

require_once '../core/CartSession.php';

class HomeController
{
    private $validator;
    private $home;
    private $plats;

    // // constructeur pour initialiser le validator ici, pour pouvoir l'utiliser dans toutes les méthodes de la classe AccueilController
    public function __construct()
    {
        $this->validator = new Validator();
        $this->home = new ModelHome();
        $this->plats = new ModelPlats();
    }

    public function index() // la vue de la connexion
    {
        // Get dishes and categories for the home menu section
        $allPlats = $this->plats->getAllPlats();
        $categories = $this->plats->getCategories();
        
        // Limit to 6 dishes for home page preview
        $plats = array_slice($allPlats, 0, 6);
         
        require_once '../views/home/home.php'; // On inclut la vue d'accueil qui contient le code HTML

    }

}
