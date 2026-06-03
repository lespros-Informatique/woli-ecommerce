<?php

require_once '../models/cart/ModelCart.php';

class CartDB
{
    private static $modelCart;

    /**
     * Initialiser le modèle de panier
     */
    private static function init()
    {
        if (!self::$modelCart) {
            self::$modelCart = new ModelCart();
        }
    }

    /**
     * Obtenir l'ID du client connecté
     */
    private static function getClientId()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Récupérer l'ID du client depuis la session
        return $_SESSION['client']['id_client'] ?? null;
    }

    /**
     * Ajouter un article au panier
     */
    public static function addToCart($plat_id, $nom, $prix, $quantite = 1, $image = null)
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            throw new Exception("Client non connecté");
        }

        return self::$modelCart->addToCart($client_id, $plat_id, $nom, $prix, $quantite, $image);
    }

    /**
     * Mettre à jour la quantité d'un article dans le panier
     */
    public static function updateQuantity($plat_id, $quantite)
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            throw new Exception("Client non connecté");
        }

        // Obtenir le panier du client
        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return false;
        }

        return self::$modelCart->updateQuantity($panier['id_panier'], $plat_id, $quantite);
    }

    /**
     * Supprimer un article du panier
     */
    public static function removeFromCart($plat_id)
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            throw new Exception("Client non connecté");
        }

        // Obtenir le panier du client
        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return false;
        }

        return self::$modelCart->removeFromCart($panier['id_panier'], $plat_id);
    }

    /**
     * Vider complètement le panier
     */
    public static function clearCart()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            throw new Exception("Client non connecté");
        }

        // Obtenir le panier du client
        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return true; // Panier déjà vide
        }

        return self::$modelCart->clearCart($panier['id_panier']);
    }

    /**
     * Obtenir le contenu du panier
     */
    public static function getCart()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            return [];
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return [];
        }

        return self::$modelCart->getLignesPanier($panier['id_panier']);
    }

    /**
     * Obtenir le nombre d'articles dans le panier
     */
    public static function getItemCount()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            return 0;
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return 0;
        }

        return self::$modelCart->getItemCount($panier['id_panier']);
    }

    /**
     * Obtenir le total du panier
     */
    public static function getCartTotal()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            return 0.0;
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return 0.0;
        }

        return self::$modelCart->getCartTotal($panier['id_panier']);
    }

    /**
     * Obtenir les détails complets du panier
     */
    public static function getCartDetails()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            return [];
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return [];
        }

        $lignes = self::$modelCart->getLignesPanier($panier['id_panier']);
        
        // Formater les données pour compatibilité avec l'interface existante
        // Retourner un tableau numérique pour une meilleure compatibilité avec foreach
        $cart_details = [];
        foreach ($lignes as $ligne) {
            $cart_details[] = [
                'id' => $ligne['plat_id'],
                'nom' => $ligne['nom_plat'],
                'prix' => $ligne['prix_unitaire'],
                'quantite' => $ligne['quantite'],
                'image' => $ligne['image_plat']
            ];
        }

        return $cart_details;
    }

    /**
     * Valider le panier (pour convertir en commande)
     */
    public static function validerPanier()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            throw new Exception("Client non connecté");
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return false;
        }

        return self::$modelCart->validerPanier($panier['id_panier']);
    }

    /**
     * Marquer le panier comme abandonné
     */
    public static function abandonnerPanier()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            throw new Exception("Client non connecté");
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return false;
        }

        return self::$modelCart->abandonnerPanier($panier['id_panier']);
    }

    /**
     * Obtenir le panier complet avec informations détaillées
     */
    public static function getPanierComplet()
    {
        self::init();
        $client_id = self::getClientId();
        
        if (!$client_id) {
            return null;
        }

        $panier = self::$modelCart->getPanierClient($client_id);
        if (!$panier) {
            return null;
        }

        $lignes = self::$modelCart->getLignesPanier($panier['id_panier']);
        $total = self::$modelCart->getCartTotal($panier['id_panier']);
        $count = self::$modelCart->getItemCount($panier['id_panier']);

        return [
            'panier' => $panier,
            'lignes' => $lignes,
            'total' => $total,
            'count' => $count
        ];
    }
}