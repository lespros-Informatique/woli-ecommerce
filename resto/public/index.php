<?php

require_once '../core/PrincipalRoute.php';

$route = new Router();

// Instanciation des contrôleurs
$homeController = new HomeController();
$aboutController = new AboutController();
$menuController = new MenuController();
$clientsController = new ClientsController();
$commandesController = new CommandesController();
$cartController = new CartController();
$contactController = new ContactController();


// Ajout des routes

$route->addRoute('/', [$homeController, 'index']); // Page de connexion

$route->addRoute('/home/settings', [$homeController, 'settings']); // Vue de settings

$route->addRoute('/home/success', [$homeController, 'success']); // Vue d'accueil

$route->addRoute('/home/error', [$homeController, 'error']); // Vue d'accueil

$route->addRoute('/about', [$aboutController, 'index']); // Vue about

$route->addRoute('/cart', [$cartController, 'index']); // Vue panier
$route->addRoute('/cart/add', [$cartController, 'add']); // AJAX add to cart
$route->addRoute('/cart/update', [$cartController, 'update']); // AJAX update quantity
$route->addRoute('/cart/remove', [$cartController, 'remove']); // AJAX remove item
$route->addRoute('/cart/clear', [$cartController, 'clear']); // AJAX clear cart
$route->addRoute('/cart/getCount', [$cartController, 'getCount']); // AJAX get cart count

$route->addRoute('/commandes/createFromCart', [$commandesController, 'createFromCart']); // AJAX checkout
$route->addRoute('/commandes/details/{param}', [$commandesController, 'details']); // AJAX checkout

$route->addRoute('/menu', [$menuController, 'index']); // Vue menu
$route->addRoute('/menu/panier', [$menuController, 'panier']); // Vue menu


$route->addRoute('/clients', [$clientsController, 'index']); // Vue clients

$route->addRoute('/commandes', [$commandesController, 'index']); // Vue commandes
$route->addRoute('/commandes/details/{param}', [$commandesController, 'details']); // Vue commandes

$route->addRoute('/clients/login', [$clientsController, 'login']); // Vue login

$route->addRoute('/clients/login/connexion', [$clientsController, 'connexion']); // AJAX connexion

$route->addRoute('/clients/register', [$clientsController, 'register']); // Vue register

$route->addRoute('/clients/register/add', [$clientsController, 'add']); // AJAX register

$route->addRoute('/contact', [$contactController, 'index']); // Vue contact

$route->addRoute('/contact/send', [$contactController, 'send']); // AJAX send email


$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($url, '/resto/public') === 0) {
    $url = str_replace('/resto/public', '', $url);
} elseif (strpos($url, '/resto') === 0) {
    $url = str_replace('/resto', '', $url);
}
// echo 'URL traitée : ' . $url . '<br>'; // Débogage pour vérifier l'URL après retrait du préfixe

$route->run($url);
