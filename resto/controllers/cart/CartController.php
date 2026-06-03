<?php

require_once '../core/CartDB.php';

class CartController
{
    private $platsModel;

    public function __construct()
    {
        $this->platsModel = new ModelPlats();
    }

    /**
     * Add item to cart via AJAX
     */
    public function add()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJsonResponse(false, 'Méthode non autorisée');
                return;
            }

            // Get POST data
            $plat_id = isset($_POST['plat_id']) ? (int) $_POST['plat_id'] : 0;
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $prix = isset($_POST['prix']) ? (float) $_POST['prix'] : 0;
            $image = isset($_POST['image']) ? trim($_POST['image']) : null;
            $quantite = isset($_POST['quantite']) ? (int) $_POST['quantite'] : 1;

            // Validate data
            if ($plat_id <= 0 || empty($nom) || $prix <= 0 || $quantite <= 0) {
                $this->sendJsonResponse(false, 'Données invalides');
                return;
            }

            // Verify the plat exists in database
            $plat = $this->platsModel->getPlatById($plat_id);
            if (!$plat) {
                $this->sendJsonResponse(false, 'Plat non trouvé');
                return;
            }

            // Add to cart
            CartDB::addToCart($plat_id, $nom, $prix, $quantite, $image);

            // Get updated cart info
            $cart_count = CartDB::getItemCount();
            $cart_total = CartDB::getCartTotal();

            $this->sendJsonResponse(true, 'Article ajouté au panier avec succès', [
                'cart_count' => $cart_count,
                'cart_total' => $cart_total
            ]);

        } catch (\Exception $e) {
            $this->sendJsonResponse(false, 'Erreur lors de l\'ajout au panier: ' . $e->getMessage());
        }
    }

    /**
     * Update item quantity in cart
     */
    public function update()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJsonResponse(false, 'Méthode non autorisée');
                return;
            }

            $plat_id = isset($_POST['plat_id']) ? (int) $_POST['plat_id'] : 0;
            $quantite = isset($_POST['quantite']) ? (int) $_POST['quantite'] : 0;

            if ($plat_id <= 0 || $quantite < 0) {
                $this->sendJsonResponse(false, 'Données invalides');
                return;
            }

            // Update cart
            $success = CartDB::updateQuantity($plat_id, $quantite);
            
            if ($success) {
                $cart_count = CartDB::getItemCount();
                $cart_total = CartDB::getCartTotal();

                $this->sendJsonResponse(true, 'Quantité mise à jour', [
                    'cart_count' => $cart_count,
                    'cart_total' => $cart_total
                ]);
            } else {
                $this->sendJsonResponse(false, 'Article non trouvé dans le panier');
            }

        } catch (\Exception $e) {
            $this->sendJsonResponse(false, 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     */
    public function remove()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJsonResponse(false, 'Méthode non autorisée');
                return;
            }

            $plat_id = isset($_POST['plat_id']) ? (int) $_POST['plat_id'] : 0;

            if ($plat_id <= 0) {
                $this->sendJsonResponse(false, 'Données invalides');
                return;
            }

            // Remove from cart
            $success = CartDB::removeFromCart($plat_id);
            
            if ($success) {
                $cart_count = CartDB::getItemCount();
                $cart_total = CartDB::getCartTotal();

                $this->sendJsonResponse(true, 'Article supprimé du panier', [
                    'cart_count' => $cart_count,
                    'cart_total' => $cart_total
                ]);
            } else {
                $this->sendJsonResponse(false, 'Article non trouvé dans le panier');
            }

        } catch (\Exception $e) {
            $this->sendJsonResponse(false, 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Get cart details for display
     */
    public function index()
    {
        try {
            $cart_items = CartDB::getCartDetails();
            $cart_count = CartDB::getItemCount();
            $cart_total = CartDB::getCartTotal();

            // Debug: Log cart data
            error_log("CartController - Cart items: " . print_r($cart_items, true));
            error_log("CartController - Cart count: " . $cart_count);
            error_log("CartController - Cart total: " . $cart_total);

            // Format cart details for the view
            $cart_details = [];
            if (!empty($cart_items)) {
                // CartDB::getCartDetails() retourne maintenant un tableau numérique
                foreach ($cart_items as $item) {
                    $plat_id = $item['id'];
                    
                    // Get detailed plat information from database
                    $plat = $this->platsModel->getPlatById($plat_id);
                    
                    if ($plat) {
                        $cart_details[] = [
                            'id' => $plat_id,
                            'nom' => $item['nom'],
                            'prix' => (float) $item['prix'],
                            'quantite' => (int) $item['quantite'],
                            'image' => $item['image'] ?? 'f1.png',
                            'prix_total' => (float) $item['prix'] * (int) $item['quantite'],
                            'categorie_nom' => $plat['categorie_nom'] ?? 'Non classifié'
                        ];
                    } else {
                        // Si le plat n'est pas trouvé en base, créer un élément minimal
                        $cart_details[] = [
                            'id' => $plat_id,
                            'nom' => $item['nom'] ?? 'Plat inconnu',
                            'prix' => (float) ($item['prix'] ?? 0),
                            'quantite' => (int) ($item['quantite'] ?? 1),
                            'image' => $item['image'] ?? 'f1.png',
                            'prix_total' => (float) ($item['prix'] ?? 0) * (int) ($item['quantite'] ?? 1),
                            'categorie_nom' => 'Non classifié'
                        ];
                    }
                }
            }

            error_log("CartController - Formatted cart details: " . print_r($cart_details, true));

            require_once '../views/menu/panier.php';
            
        } catch (\Exception $e) {
            // Log error
            error_log("CartController error: " . $e->getMessage());
            
            // If there's an error, still show the page with empty cart
            $cart_details = [];
            $cart_count = 0;
            $cart_total = 0;
            
            require_once '../views/menu/panier.php';
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJsonResponse(false, 'Méthode non autorisée');
                return;
            }

            CartDB::clearCart();
            
            $this->sendJsonResponse(true, 'Panier vidé avec succès', [
                'cart_count' => 0,
                'cart_total' => 0
            ]);

        } catch (\Exception $e) {
            $this->sendJsonResponse(false, 'Erreur lors du vidage du panier: ' . $e->getMessage());
        }
    }

    /**
     * Get cart count for navigation (AJAX)
     */
    public function getCount()
    {
        try {
            $cart_count = CartDB::getItemCount();
            $this->sendJsonResponse(true, 'Compteur du panier', [
                'cart_count' => $cart_count
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse(false, 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Send JSON response
     */
    private function sendJsonResponse($success, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $success ? 1 : 0,
            'msg' => $message,
            'data' => $data
        ]);
        exit;
    }
}