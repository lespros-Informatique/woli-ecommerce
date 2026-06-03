<?php
/**
 * Script de debug pour vérifier les données du panier
 * Exécutez ce fichier pour voir le contenu exact du panier
 */

require_once 'config/Database.php';
require_once 'models/cart/ModelCart.php';
require_once 'core/CartDB.php';

echo "<h2>🔍 Debug Panier - Données Complètes</h2>";
echo "<pre>";

// Démarrer la session comme dans l'application
session_start();

// Simuler un utilisateur connecté (à adapter selon votre système)
if (!isset($_SESSION['client'])) {
    $_SESSION['client'] = [
        'id_client' => 1, // Test avec client ID 1
        'nom' => 'Client Test',
        'email' => 'test@example.com'
    ];
    echo "🔑 Session utilisateur simulée pour test\n\n";
}

echo "1. 📋 Informations de session:\n";
print_r($_SESSION);
echo "\n";

// Test du modèle CartDB
echo "2. 🛒 Test CartDB::getCartDetails():\n";
try {
    $cartDetails = CartDB::getCartDetails();
    echo "Données retournées:\n";
    var_dump($cartDetails);
    echo "\n";
    
    if (empty($cartDetails)) {
        echo "❌ Le panier est vide selon CartDB\n";
    } else {
        echo "✅ Le panier contient des articles\n";
        echo "Nombre d'articles: " . count($cartDetails) . "\n\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur CartDB: " . $e->getMessage() . "\n\n";
}

// Test du nombre d'articles
echo "3. 🔢 Test CartDB::getItemCount():\n";
try {
    $count = CartDB::getItemCount();
    echo "Nombre d'articles dans le panier: " . $count . "\n\n";
} catch (Exception $e) {
    echo "❌ Erreur getItemCount: " . $e->getMessage() . "\n\n";
}

// Test du total
echo "4. 💰 Test CartDB::getCartTotal():\n";
try {
    $total = CartDB::getCartTotal();
    echo "Total du panier: " . number_format($total, 0, ',', ' ') . " FCFA\n\n";
} catch (Exception $e) {
    echo "❌ Erreur getCartTotal: " . $e->getMessage() . "\n\n";
}

// Test direct du modèle
echo "5. 🔧 Test direct ModelCart:\n";
try {
    $modelCart = new ModelCart();
    $clientId = $_SESSION['client']['id_client'];
    
    echo "Client ID: " . $clientId . "\n";
    
    $panier = $modelCart->getPanierClient($clientId);
    echo "Panier trouvé:\n";
    var_dump($panier);
    echo "\n";
    
    if ($panier) {
        $lignes = $modelCart->getLignesPanier($panier['id_panier']);
        echo "Lignes du panier:\n";
        var_dump($lignes);
        echo "\n";
        
        // Vérifier la base de données directement
        $db = (new Database())->getCon();
        
        echo "6. 🗄️ Vérification directe en base:\n";
        
        // Table paniers
        $sqlPaniers = "SELECT * FROM paniers WHERE client_id = ?";
        $stmt = $db->prepare($sqlPaniers);
        $stmt->execute([$clientId]);
        $paniersDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Paniers en base pour client " . $clientId . ":\n";
        var_dump($paniersDb);
        
        // Table ligne_panier
        if (!empty($paniersDb)) {
            $panierId = $paniersDb[0]['id_panier'];
            $sqlLignes = "SELECT * FROM ligne_panier WHERE panier_id = ?";
            $stmt = $db->prepare($sqlLignes);
            $stmt->execute([$panierId]);
            $lignesDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Lignes panier en base pour panier ID " . $panierId . ":\n";
            var_dump($lignesDb);
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur ModelCart: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug terminé ===\n";
echo "\n💡 Si le panier semble vide mais qu'il y a des données en base,\n";
echo "   vérifiez que l'ID client dans la session correspond\n";
echo "   à celui utilisé pour ajouter les articles.\n";

echo "</pre>";
?>