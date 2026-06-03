<?php

class CartSession
{
    private static $cart_key = 'restaurant_cart';

    /**
     * Initialize cart session
     */
    public static function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[self::$cart_key])) {
            $_SESSION[self::$cart_key] = [];
        }
    }

    /**
     * Get cart contents
     */
    public static function getCart()
    {
        self::init();
        return $_SESSION[self::$cart_key];
    }

    /**
     * Add item to cart
     */
    public static function addToCart($plat_id, $nom, $prix, $quantite = 1, $image = null)
    {
        self::init();
        $plat_id = (int) $plat_id;
        
        // Check if item already exists in cart
        if (isset($_SESSION[self::$cart_key][$plat_id])) {
            // Update quantity
            $_SESSION[self::$cart_key][$plat_id]['quantite'] += $quantite;
        } else {
            // Add new item
            $_SESSION[self::$cart_key][$plat_id] = [
                'id' => $plat_id,
                'nom' => $nom,
                'prix' => (float) $prix,
                'quantite' => $quantite,
                'image' => $image
            ];
        }
        
        return true;
    }

    /**
     * Update item quantity in cart
     */
    public static function updateQuantity($plat_id, $quantite)
    {
        self::init();
        $plat_id = (int) $plat_id;
        
        if (isset($_SESSION[self::$cart_key][$plat_id])) {
            if ($quantite <= 0) {
                // Remove item if quantity is 0 or negative
                unset($_SESSION[self::$cart_key][$plat_id]);
            } else {
                // Update quantity
                $_SESSION[self::$cart_key][$plat_id]['quantite'] = (int) $quantite;
            }
            return true;
        }
        
        return false;
    }

    /**
     * Remove item from cart
     */
    public static function removeFromCart($plat_id)
    {
        self::init();
        $plat_id = (int) $plat_id;
        
        if (isset($_SESSION[self::$cart_key][$plat_id])) {
            unset($_SESSION[self::$cart_key][$plat_id]);
            return true;
        }
        
        return false;
    }

    /**
     * Clear entire cart
     */
    public static function clearCart()
    {
        self::init();
        $_SESSION[self::$cart_key] = [];
        return true;
    }

    /**
     * Get cart item count
     */
    public static function getItemCount()
    {
        self::init();
        $count = 0;
        foreach ($_SESSION[self::$cart_key] as $item) {
            $count += $item['quantite'];
        }
        return $count;
    }

    /**
     * Get cart total
     */
    public static function getCartTotal()
    {
        self::init();
        $total = 0;
        foreach ($_SESSION[self::$cart_key] as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }

    /**
     * Get cart items with detailed info
     */
    public static function getCartDetails()
    {
        self::init();
        return $_SESSION[self::$cart_key];
    }
}