<?php

require_once '../core/CartDB.php';

class MenuController
{
    private $plats;

    public function __construct()
    {
        $this->plats = new ModelPlats();
    }

    public function index()
    {
        // Gérer le code promo depuis l'URL
        if (isset($_GET['promo']) && !empty($_GET['promo'])) {
            $_SESSION['promo_id'] = intval($_GET['promo']);
        }

        $modelPlats = new ModelPlats();

        $plats = $modelPlats->getAllPlats();
        $categories = $modelPlats->getCategories();

        require_once '../views/menu/menu.php';
    }

    /**
     * AJAX method for loading more dishes (optional)
     */
    public function loadMore($page = 1, $limit = 9)
    {
        header('Content-Type: application/json');
        try {
            $allPlats = $this->plats->getAllPlats();
            $totalPlats = count($allPlats);

            $offset = ($page - 1) * $limit;
            $plats = array_slice($allPlats, $offset, $limit);
            $hasMore = ($offset + $limit) < $totalPlats;

            echo json_encode([
                'success' => true,
                'plats' => $plats,
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'total' => $totalPlats,
                'totalPages' => ceil($totalPlats / $limit)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement des plats'
            ]);
        }
        exit;
    }

    /**
     * Display the cart/panier contents
     */
    public function panier()
    {
        // Get cart details from database
        $cart_items = CartDB::getCartDetails();
        $cart_count = CartDB::getItemCount();
        $cart_total = CartDB::getCartTotal();

        // Get detailed plat information for items in cart
        $cart_details = [];
        if (!empty($cart_items)) {
            foreach ($cart_items as $item) {
                $plat = $this->plats->getPlatById($item['id']);
                if ($plat) {
                    $cart_details[] = array_merge($item, [
                        'prix_total' => $item['prix'] * $item['quantite'],
                        'categorie_nom' => $plat['categorie_nom']
                    ]);
                }
            }
        }

        // Calculer la réduction si une promotion est active
        $promotion_info = null;
        $montant_reduction = 0;
        $total_final = $cart_total;

        if (isset($_SESSION['promo_id']) && !empty($_SESSION['promo_id'])) {
            $modelPromotions = new ModelPromotions();
            $promo = $modelPromotions->getPromotionById($_SESSION['promo_id']);

            if ($promo && $promo['actif']) {
                // Vérifier les dates
                $today = date('Y-m-d');
                $valid = true;

                if (!empty($promo['date_debut']) && $promo['date_debut'] > $today) {
                    $valid = false;
                }
                if (!empty($promo['date_fin']) && $promo['date_fin'] < $today) {
                    $valid = false;
                }

                if ($valid) {
                    $montant_reduction = $cart_total * ($promo['pourcentage_reduction'] / 100);
                    $total_final = $cart_total - $montant_reduction;
                    $promotion_info = $promo;
                }
            }
        }

        // Calculer les frais de livraison
        $frais_livraison = 0;
        $type_commande = isset($_GET['type']) ? $_GET['type'] : (isset($_SESSION['type_commande']) ? $_SESSION['type_commande'] : 'livraison');

        // Récupérer les settings
        $modelSettings = new ModelSettings();
        $settings = $modelSettings->getAllSettings();
        $livraison_active = isset($settings['livraison_active']) && $settings['livraison_active'] == '1';

        // Si livraison désactivée, forcer "à emporter"
        if (!$livraison_active) {
            $type_commande = 'à emporter';
        }

        if ($type_commande === 'livraison' && $livraison_active) {
            // Récupérer les frais depuis settings
            $frais_livraison = isset($settings['frais_livraison']) ? floatval($settings['frais_livraison']) : 0;
            $total_final += $frais_livraison;
        }

        // Include the panier view
        require_once '../views/menu/panier.php';
    }
}