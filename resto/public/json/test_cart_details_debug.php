<?php
require_once '../../core/CartDB.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h2>Debug CartDB::getCartDetails()</h2>";

try {
    // Test the getCartDetails method
    $cart_details = CartDB::getCartDetails();
    
    echo "<h3>Type de retour:</h3>";
    echo "is_array: " . (is_array($cart_details) ? 'Oui' : 'Non') . "<br>";
    echo "empty: " . (empty($cart_details) ? 'Oui' : 'Non') . "<br>";
    echo "count: " . count($cart_details) . "<br>";
    
    if (!empty($cart_details)) {
        echo "<h3>Structure des données:</h3>";
        echo "Premier élément - Keys: " . implode(', ', array_keys($cart_details)) . "<br>";
        
        echo "<h3>Premiers éléments (print_r):</h3>";
        echo "<pre>";
        print_r(array_slice($cart_details, 0, 2, true));
        echo "</pre>";
        
        // Test de boucle foreach
        echo "<h3>Test de la boucle foreach:</h3>";
        $count = 0;
        foreach ($cart_details as $plat_id => $item) {
            $count++;
            echo "Item $count - plat_id: $plat_id, data: ";
            echo is_array($item) ? implode(', ', array_keys($item)) : 'Pas un array';
            echo "<br>";
            
            if ($count >= 3) {
                echo "... et " . (count($cart_details) - 3) . " autres items";
                break;
            }
        }
    } else {
        echo "<p style='color: orange;'>Panier vide - créons des données de test</p>";
        
        // Créer un panier de test si nécessaire
        session_start();
        if (!isset($_SESSION['client']['id_client'])) {
            echo "Aucun client connecté. Simulation avec client_id = 1";
            // Vous pouvez tester avec un client_id spécifique ici
        } else {
            echo "Client connecté: ID = " . $_SESSION['client']['id_client'];
        }
    }
    
    echo "<h3>Méthodes de debug disponibles:</h3>";
    echo "getItemCount(): " . CartDB::getItemCount() . "<br>";
    echo "getCartTotal(): " . CartDB::getCartTotal() . "<br>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
h3 { color: #666; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>