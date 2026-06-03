<?php
/**
 * Test simple et direct du panier
 */

require_once 'config/Database.php';
require_once 'models/cart/ModelCart.php';
require_once 'core/CartDB.php';

session_start();

// Simuler l'utilisateur connecté (ID 2 selon votre base)
if (!isset($_SESSION['client'])) {
    $_SESSION['client'] = [
        'id_client' => 2, // Le client qui a les articles
        'nom' => 'Test Client'
    ];
}

echo "<h2>🧪 Test Direct Panier</h2>";
echo "<pre>";

echo "1. Session utilisateur:\n";
var_dump($_SESSION['client']);
echo "\n";

// Test direct CartDB
echo "2. Test CartDB::getCartDetails():\n";
try {
    $cartDetails = CartDB::getCartDetails();
    echo "Type: " . gettype($cartDetails) . "\n";
    echo "Est vide: " . (empty($cartDetails) ? 'Oui' : 'Non') . "\n";
    echo "Nombre d'éléments: " . count($cartDetails) . "\n";
    
    if (!empty($cartDetails)) {
        echo "Premier élément:\n";
        var_dump($cartDetails[array_key_first($cartDetails)]);
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

// Test Item Count
echo "\n3. Test CartDB::getItemCount():\n";
try {
    $count = CartDB::getItemCount();
    echo "Nombre d'articles: " . $count . "\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

// Test Total
echo "\n4. Test CartDB::getCartTotal():\n";
try {
    $total = CartDB::getCartTotal();
    echo "Total: " . $total . " FCFA\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

// Vérifier la base directement
echo "\n5. Vérification base de données:\n";
try {
    $db = (new Database())->getCon();
    
    // Paniers du client
    $stmt = $db->prepare("SELECT * FROM paniers WHERE client_id = ?");
    $stmt->execute([$_SESSION['client']['id_client']]);
    $paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Paniers trouvés: " . count($paniers) . "\n";
    foreach ($paniers as $panier) {
        echo "  - Panier ID: " . $panier['id_panier'] . ", Statut: " . $panier['statut'] . "\n";
        
        // Lignes de ce panier
        $stmt2 = $db->prepare("SELECT * FROM ligne_panier WHERE panier_id = ?");
        $stmt2->execute([$panier['id_panier']]);
        $lignes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        echo "    Lignes: " . count($lignes) . "\n";
        foreach ($lignes as $ligne) {
            echo "      - Plat ID: " . $ligne['plat_id'] . 
                 ", Nom: " . $ligne['nom_plat'] . 
                 ", Qté: " . $ligne['quantite'] . 
                 ", Prix: " . $ligne['prix_unitaire'] . "\n";
        }
    }
} catch (Exception $e) {
    echo "Erreur base: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
echo "</pre>";
?>