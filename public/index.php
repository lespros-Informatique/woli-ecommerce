<?php

require_once '../core/PrincipalRoute.php';

$route = new Router();

// Instanciation des contrôleurs
$homeController = new HomeController();

// Ajout des routes

$route->addRoute('/', [$homeController, 'index']); // Page de connexion

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($url, '/woli/public') === 0) {
    $url = str_replace('/woli/public', '', $url);
} elseif (strpos($url, '/woli') === 0) {
    $url = str_replace('/woli', '', $url);
}
// echo 'URL traitée : ' . $url . '<br>'; // Débogage pour vérifier l'URL après retrait du préfixe

$route->run($url);
