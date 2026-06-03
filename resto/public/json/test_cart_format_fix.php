<?php
// Test de vérification du format des données du panier
require_once '../../core/CartDB.php';

echo "<h2>Test de vérification du format des données du panier</h2>";

try {
    // Test du format de retour de getCartDetails()
    $cart_details = CartDB::getCartDetails();
    
    echo "<h3>Format de retour de CartDB::getCartDetails():</h3>";
    echo "Type: " . gettype($cart_details) . "<br>";
    echo "Est un array: " . (is_array($cart_details) ? "✅ Oui" : "❌ Non") . "<br>";
    echo "Nombre d'éléments: " . count($cart_details) . "<br>";
    
    if (!empty($cart_details)) {
        echo "<h3>Structure du premier élément:</h3>";
        echo "<pre>";
        print_r($cart_details[0]);
        echo "</pre>";
        
        echo "<h3>Test de compatibilité avec foreach:</h3>";
        $item_count = 0;
        foreach ($cart_details as $item) {
            $item_count++;
            echo "Item $item_count: ID={$item['id']}, Nom={$item['nom']}, Prix={$item['prix']}, Qté={$item['quantite']}<br>";
        }
        echo "✅ Boucle foreach fonctionne correctement avec $item_count éléments<br>";
    } else {
        echo "<p style='color: orange;'>⚠️ Panier vide - testez l'ajout d'articles pour voir le format</p>";
    }
    
    echo "<h3>Méthodes de validation:</h3>";
    echo "getItemCount(): " . CartDB::getItemCount() . "<br>";
    echo "getCartTotal(): " . CartDB::getCartTotal() . " FCFA<br>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background: #f8f9fa;
}
h2 { 
    color: #2c3e50; 
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}
h3 { 
    color: #34495e; 
    margin-top: 25px;
}
pre { 
    background: #ecf0f1; 
    padding: 15px; 
    border-radius: 5px; 
    border-left: 4px solid #3498db;
    overflow-x: auto;
}
p { 
    padding: 10px; 
    background: #fff3cd; 
    border: 1px solid #ffeaa7; 
    border-radius: 5px; 
}
</style>