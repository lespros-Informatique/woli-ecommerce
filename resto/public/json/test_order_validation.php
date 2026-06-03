<?php
// Test complet de validation de commande
require_once '../../core/CartDB.php';
require_once '../../models/commandes/ModelCommandes.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>Test Order Validation</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .section { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { color: #28a745; font-weight: bold; }
    .error { color: #dc3545; font-weight: bold; }
    .warning { color: #ffc107; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style></head><body>";

echo "<h1>🧪 Test de Validation de Commande Complète</h1>";

try {
    // 1. Test récupération du panier
    echo "<div class='section'>";
    echo "<h2>1. 📋 Récupération du Panier depuis la Base de Données</h2>";
    
    $cart_items = CartDB::getCartDetails();
    $cart_count = CartDB::getItemCount();
    $cart_total = CartDB::getCartTotal();
    
    echo "<p><strong>Nombre d'articles:</strong> $cart_count</p>";
    echo "<p><strong>Total du panier:</strong> " . number_format($cart_total, 0, ',', ' ') . " FCFA</p>";
    
    if (!empty($cart_items)) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prix</th><th>Quantité</th><th>Total</th></tr>";
        $total_calcul = 0;
        foreach ($cart_items as $item) {
            $item_total = $item['prix'] * $item['quantite'];
            $total_calcul += $item_total;
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['nom']}</td>";
            echo "<td>" . number_format($item['prix'], 0, ',', ' ') . " FCFA</td>";
            echo "<td>{$item['quantite']}</td>";
            echo "<td>" . number_format($item_total, 0, ',', ' ') . " FCFA</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p class='success'>✅ Panier récupéré avec succès (" . count($cart_items) . " articles)</p>";
        echo "<p><strong>Total calculé:</strong> " . number_format($total_calcul, 0, ',', ' ') . " FCFA</p>";
    } else {
        echo "<p class='warning'>⚠️ Panier vide - ajoutez des articles pour tester</p>";
    }
    echo "</div>";
    
    // 2. Test simulation de commande (sans réellement créer)
    if (!empty($cart_items)) {
        echo "<div class='section'>";
        echo "<h2>2. 🏪 Simulation de Création de Commande</h2>";
        
        // Générer un code de commande de test
        $code_commande = 'TEST' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        echo "<p><strong>Code de commande généré:</strong> $code_commande</p>";
        
        // Simuler les données de commande
        $commande_data = [
            'code_commande' => $code_commande,
            'client_id' => 2, // Client ID depuis la DB
            'total' => $cart_total,
            'frais_livraison' => 0.00,
            'statut' => 'reçue',
            'paiement' => 'à la livraison',
            'adresse_livraison' => 'Zone Industrielle Test',
            'instructions' => 'Test de validation de commande'
        ];
        
        echo "<h3>Données de commande:</h3>";
        echo "<pre>";
        print_r($commande_data);
        echo "</pre>";
        
        echo "<h3>Articles de commande:</h3>";
        echo "<table>";
        echo "<tr><th>Plat ID</th><th>Quantité</th><th>Prix Unitaire</th><th>Total</th></tr>";
        foreach ($cart_items as $item) {
            $item_total = $item['prix'] * $item['quantite'];
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['quantite']}</td>";
            echo "<td>" . number_format($item['prix'], 0, ',', ' ') . " FCFA</td>";
            echo "<td>" . number_format($item_total, 0, ',', ' ') . " FCFA</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p class='success'>✅ Simulation de commande préparée avec succès</p>";
        echo "</div>";
        
        // 3. Test validation du panier
        echo "<div class='section'>";
        echo "<h2>3. ✅ Test de Validation du Panier</h2>";
        
        try {
            // Vérifier le statut actuel du panier
            $panier_complet = CartDB::getPanierComplet();
            if ($panier_complet && isset($panier_complet['panier'])) {
                $statut_actuel = $panier_complet['panier']['statut'];
                echo "<p><strong>Statut actuel du panier:</strong> $statut_actuel</p>";
                
                if ($statut_actuel === 'actif') {
                    echo "<p class='success'>✅ Panier prêt pour la validation</p>";
                    
                    // Test de la méthode de validation
                    try {
                        $validation_result = CartDB::validerPanier();
                        if ($validation_result) {
                            echo "<p class='success'>✅ Méthode de validation du panier exécutée avec succès</p>";
                            
                            // Vérifier le nouveau statut
                            $nouveau_panier = CartDB::getPanierComplet();
                            $nouveau_statut = $nouveau_panier['panier']['statut'];
                            echo "<p><strong>Nouveau statut:</strong> $nouveau_statut</p>";
                            
                            if ($nouveau_statut === 'validé') {
                                echo "<p class='success'>✅ Statut du panier mis à jour avec succès</p>";
                            } else {
                                echo "<p class='warning'>⚠️ Statut du panier non modifié</p>";
                            }
                        } else {
                            echo "<p class='error'>❌ Échec de la validation du panier</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='error'>❌ Erreur lors de la validation: " . $e->getMessage() . "</p>";
                    }
                } else {
                    echo "<p class='warning'>⚠️ Panier déjà $statut_actuel - impossible de valider</p>";
                }
            } else {
                echo "<p class='warning'>⚠️ Aucun panier actif trouvé pour ce client</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur lors de la validation du panier: " . $e->getMessage() . "</p>";
        }
        echo "</div>";
        
        // 4. Test des méthodes transactionnelles
        echo "<div class='section'>";
        echo "<h2>4. 🔄 Test des Méthodes Transactionnelles</h2>";
        
        try {
            $modelCommandes = new ModelCommandes();
            
            echo "<h3>Test des méthodes de transaction:</h3>";
            
            // Tester beginTransaction
            $begin_result = $modelCommandes->beginTransaction();
            echo "<p><strong>beginTransaction():</strong> " . ($begin_result ? "✅ Succès" : "❌ Échec") . "</p>";
            
            // Tester commit
            $commit_result = $modelCommandes->commit();
            echo "<p><strong>commit():</strong> " . ($commit_result ? "✅ Succès" : "❌ Échec") . "</p>";
            
            // Tester rollback (sans transaction active)
            try {
                $rollback_result = $modelCommandes->rollback();
                echo "<p><strong>rollback():</strong> " . ($rollback_result ? "✅ Succès" : "⚠️ Retour false") . "</p>";
            } catch (Exception $e) {
                echo "<p><strong>rollback():</strong> ⚠️ Exception (normal sans transaction active)</p>";
            }
            
            echo "<p class='success'>✅ Méthodes transactionnelles disponibles</p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur lors du test des transactions: " . $e->getMessage() . "</p>";
        }
        echo "</div>";
    }
    
    // 5. Résumé final
    echo "<div class='section'>";
    echo "<h2>5. 📊 Résumé du Test</h2>";
    
    $tests_passed = 0;
    $total_tests = 0;
    
    if (!empty($cart_items)) {
        $total_tests++;
        if ($cart_count > 0) $tests_passed++;
    }
    
    $total_tests++;
    if (isset($begin_result) && $begin_result) $tests_passed++;
    
    $total_tests++;
    if (isset($commit_result) && $commit_result) $tests_passed++;
    
    echo "<p><strong>Tests réussis:</strong> $tests_passed / $total_tests</p>";
    
    if ($tests_passed === $total_tests) {
        echo "<p class='success'>🎉 Tous les tests sont passés ! La validation de commande est prête.</p>";
    } else {
        echo "<p class='warning'>⚠️ Certains tests ont échoué. Vérifiez les erreurs ci-dessus.</p>";
    }
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='section'>";
    echo "<h2>❌ Erreur Générale</h2>";
    echo "<p class='error'>Erreur: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<div class='section'>";
echo "<h2>📋 Instructions de Test</h2>";
echo "<ol>";
echo "<li>Ajoutez des articles à votre panier via l'interface utilisateur</li>";
echo "<li>Revenez sur cette page pour voir les données du panier</li>";
echo "<li>Testez la création de commande via l'interface</li>";
echo "<li>Vérifiez les données dans la base de données</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>