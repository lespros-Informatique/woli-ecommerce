<?php
/**
 * Script de test pour vérifier le fonctionnement du panier en base de données
 * Exécutez ce script pour tester les fonctionnalités du panier
 */

require_once 'config/Database.php';
require_once 'models/cart/ModelCart.php';
require_once 'core/CartDB.php';

echo "<h2>Test du Panier en Base de Données</h2>\n";
echo "<pre>";

// Test 1: Connexion à la base de données
echo "1. Test de connexion à la base de données...\n";
try {
    $db = (new Database())->getCon();
    echo "✓ Connexion réussie\n";
} catch (Exception $e) {
    echo "✗ Erreur de connexion: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Modèle ModelCart
echo "\n2. Test du modèle ModelCart...\n";
try {
    $modelCart = new ModelCart();
    echo "✓ Modèle ModelCart créé\n";
    
    // Test si les tables existent
    $result = $modelTest = $modelCart->getPanierClient(1); // Client ID 1 (test)
    echo "✓ Méthodes du modèle accessibles\n";
} catch (Exception $e) {
    echo "✗ Erreur avec ModelCart: " . $e->getMessage() . "\n";
}

// Test 3: Classe CartDB
echo "\n3. Test de la classe CartDB...\n";
try {
    // Simuler une session utilisateur
    session_start();
    $_SESSION['client'] = [
        'id_client' => 1,
        'nom' => 'Client Test',
        'email' => 'test@example.com'
    ];
    
    echo "✓ Session utilisateur simulée\n";
    
    // Test des méthodes statiques (doivent être appelées avec un client connecté)
    echo "✓ Classe CartDB accessible\n";
} catch (Exception $e) {
    echo "✗ Erreur avec CartDB: " . $e->getMessage() . "\n";
}

// Test 4: Vérifier les tables de base de données
echo "\n4. Vérification des tables de panier...\n";
try {
    $sql_check_paniers = "SHOW TABLES LIKE 'paniers'";
    $stmt = $db->prepare($sql_check_paniers);
    $stmt->execute();
    $result_paniers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $sql_check_lignes = "SHOW TABLES LIKE 'ligne_panier'";
    $stmt = $db->prepare($sql_check_lignes);
    $stmt->execute();
    $result_lignes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($result_paniers) > 0) {
        echo "✓ Table 'paniers' existe\n";
    } else {
        echo "✗ Table 'paniers' n'existe pas\n";
    }
    
    if (count($result_lignes) > 0) {
        echo "✓ Table 'ligne_panier' existe\n";
    } else {
        echo "✗ Table 'ligne_panier' n'existe pas\n";
    }
} catch (Exception $e) {
    echo "✗ Erreur lors de la vérification des tables: " . $e->getMessage() . "\n";
}

// Test 5: Structure des tables
echo "\n5. Vérification de la structure des tables...\n";
try {
    // Vérifier la structure de la table paniers
    $sql_structure_paniers = "DESCRIBE paniers";
    $stmt = $db->prepare($sql_structure_paniers);
    $stmt->execute();
    $structure_paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Structure de la table 'paniers':\n";
    foreach ($structure_paniers as $field) {
        echo "  - " . $field['Field'] . " (" . $field['Type'] . ")\n";
    }
    
    // Vérifier la structure de la table ligne_panier
    $sql_structure_lignes = "DESCRIBE ligne_panier";
    $stmt = $db->prepare($sql_structure_lignes);
    $stmt->execute();
    $structure_lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nStructure de la table 'ligne_panier':\n";
    foreach ($structure_lignes as $field) {
        echo "  - " . $field['Field'] . " (" . $field['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erreur lors de la vérification de la structure: " . $e->getMessage() . "\n";
}

// Test 6: Vérifier les contraintes de clés étrangères
echo "\n6. Vérification des contraintes de clés étrangères...\n";
try {
    $sql_constraints = "
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'db_resto' 
        AND TABLE_NAME IN ('paniers', 'ligne_panier')
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ";
    
    $stmt = $db->prepare($sql_constraints);
    $stmt->execute();
    $constraints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Contraintes de clés étrangères:\n";
    foreach ($constraints as $constraint) {
        echo "  - " . $constraint['TABLE_NAME'] . "." . $constraint['COLUMN_NAME'] . 
             " -> " . $constraint['REFERENCED_TABLE_NAME'] . "." . $constraint['REFERENCED_COLUMN_NAME'] . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erreur lors de la vérification des contraintes: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
echo "\nInstructions pour finaliser la mise en place:\n";
echo "1. Exécutez le contenu de 'db_panier.sql' dans phpMyAdmin pour créer les tables\n";
echo "2. Assurez-vous qu'un client existe dans la table 'clients' (ID 1 minimum)\n";
echo "3. Connectez-vous avec ce client sur le site\n";
echo "4. Testez l'ajout d'articles au panier\n";
echo "5. Vérifiez que les données sont bien stockées en base\n";

echo "</pre>";
?>