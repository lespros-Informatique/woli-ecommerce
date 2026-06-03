<?php

// require_once '../core/CartDB.php';
// require_once '../models/Validator.php';
// require_once '../models/commandes/ModelCommandes.php';
// require_once '../models/clients/ModelClients.php';

// // Start session if not already started
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// class CommandesControllerFixed
// {
//     private $validator;
//     private $commandes;
//     private $clients;
//     private $plats;

//     public function __construct()
//     {
//         $this->validator = new Validator();
//         $this->commandes = new ModelCommandes();
//         $this->clients = new ModelClients();
//         $this->plats = new ModelPlats();
//     }

//     public function index()
//     {
//         if (!isset($_SESSION['client']) || !isset($_SESSION['client']['id_client'])) {
//             header('Location: ' . RACINE . 'clients/login');
//             exit;
//         }
        
//         $commandes = $this->commandes->getAllCommandesByClient($_SESSION['client']['id_client']);
        
//         $stats = [
//             'total' => count($commandes),
//             'pending' => 0,
//             'completed' => 0,
//             'total_amount' => 0
//         ];
        
//         foreach ($commandes as $cmd) {
//             if (in_array($cmd['statut'], ['recue', 'preparation'])) {
//                 $stats['pending']++;
//             }
//             if (in_array($cmd['statut'], ['prete', 'livree'])) {
//                 $stats['completed']++;
//             }
//             $stats['total_amount'] += $cmd['total'];
//         }
        
//         require_once '../views/commandes/commandes.php';
//     }

//     public function details($params)
//     {
//         $id = $this->validator->decrypter($params);
//         $commande_complete = $this->commandes->getCommandeComplete($id);
//         if (!$commande_complete) {
//             header('Location: ' . RACINE . 'commandes');
//             exit;
//         }
        
//         $commande = $commande_complete['commande'];
//         $lignes = $commande_complete['lignes'];
//         $paiements = $commande_complete['paiements'];
        
//         require_once '../views/commandes/show.php';
//     }

//     public function create()
//     {
//         $clients = $this->clients->getAllClients();
//         require_once '../views/commandes/create.php';
//     }

//     public function store()
//     {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $data = [
//                 'code_commande' => $_POST['code_commande'] ?? '',
//                 'client_id' => $_POST['client_id'] ?? '',
//                 'total' => $_POST['total'] ?? 0.00,
//                 'frais_livraison' => $_POST['frais_livraison'] ?? 0.00,
//                 'statut' => $_POST['statut'] ?? 'reçue',
//                 'paiement' => $_POST['paiement'] ?? 'à la livraison',
//                 'adresse_livraison' => $_POST['adresse_livraison'] ?? '',
//                 'instructions' => $_POST['instructions'] ?? ''
//             ];

//             $errors = $this->validator->validateCommandeData($data);
//             if (empty($errors)) {
//                 $this->commandes->addCommande($data);
//                 header('Location: ' . RACINE . 'commandes');
//                 exit;
//             } else {
//                 $clients = $this->clients->getAllClients();
//                 require_once '../views/commandes/create.php';
//             }
//         }
//     }

//     public function edit($id)
//     {
//         $commande = $this->commandes->getCommandeById($id);
//         $clients = $this->clients->getAllClients();
//         if (!$commande) {
//             header('Location: ' . RACINE . 'commandes');
//             exit;
//         }
//         require_once '../views/commandes/edit.php';
//     }

//     public function update($id)
//     {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $data = [
//                 'total' => $_POST['total'] ?? 0.00,
//                 'frais_livraison' => $_POST['frais_livraison'] ?? 0.00,
//                 'statut' => $_POST['statut'] ?? 'reçue',
//                 'paiement' => $_POST['paiement'] ?? 'à la livraison',
//                 'adresse_livraison' => $_POST['adresse_livraison'] ?? '',
//                 'instructions' => $_POST['instructions'] ?? ''
//             ];

//             $errors = $this->validator->validateCommandeData($data);
//             if (empty($errors)) {
//                 $this->commandes->updateCommande($id, $data);
//                 header('Location: ' . RACINE . 'commandes');
//                 exit;
//             } else {
//                 $commande = $this->commandes->getCommandeById($id);
//                 $clients = $this->clients->getAllClients();
//                 require_once '../views/commandes/edit.php';
//             }
//         }
//     }

//     public function delete($id)
//     {
//         $this->commandes->deleteCommande($id);
//         header('Location: ' . RACINE . 'commandes');
//         exit;
//     }

//     /**
//      * Handle cart checkout via AJAX - VERSION CORRIGÉE POUR JSON
//      */
//     public function createFromCart()
//     {
//         try {
//             // Force le header JSON en premier
//             header('Content-Type: application/json');
//             header('Access-Control-Allow-Origin: *');
//             header('Access-Control-Allow-Methods: POST, OPTIONS');
//             header('Access-Control-Allow-Headers: Content-Type');
            
//             if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//                 http_response_code(200);
//                 exit();
//             }
            
//             if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//                 $this->sendJsonResponse(false, 'Méthode non autorisée');
//                 return;
//             }

//             // Get cart data from database
//             $cart_items = CartDB::getCartDetails();
//             $cart_total = CartDB::getCartTotal();
//             $cart_count = CartDB::getItemCount();

//             // DEBUG: Log cart data
//             error_log("🛒 Cart data - Items: " . count($cart_items) . ", Total: " . $cart_total . ", Count: " . $cart_count);

//             if (empty($cart_items) || $cart_count === 0) {
//                 error_log("❌ Cart is empty - Items: " . count($cart_items) . ", Count: " . $cart_count);
//                 $this->sendJsonResponse(false, 'Votre panier est vide');
//                 return;
//             }

//             // Get POST data
//             $adresse_livraison = isset($_POST['adresse_livraison']) ? trim($_POST['adresse_livraison']) : '';
//             $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
//             $methode_paiement = isset($_POST['methode_paiement']) ? trim($_POST['methode_paiement']) : 'à la livraison';

//             // DEBUG: Log all POST data
//             error_log("📋 POST data received in createFromCart:");
//             foreach ($_POST as $key => $value) {
//                 error_log("  📌 $key: $value");
//             }
            
//             // Get GPS coordinates from POST data with enhanced validation
//             $client_latitude = null;
//             $client_longitude = null;
            
//             if (isset($_POST['client_latitude']) && $_POST['client_latitude'] !== '') {
//                 $client_latitude = floatval($_POST['client_latitude']);
//                 error_log("✅ GPS Latitude received and parsed: " . $client_latitude);
//             } else {
//                 error_log("❌ GPS Latitude missing or empty in POST data");
//             }
            
//             if (isset($_POST['client_longitude']) && $_POST['client_longitude'] !== '') {
//                 $client_longitude = floatval($_POST['client_longitude']);
//                 error_log("✅ GPS Longitude received and parsed: " . $client_longitude);
//             } else {
//                 error_log("❌ GPS Longitude missing or empty in POST data");
//             }

//             // Enhanced GPS data logging
//             error_log("📍 GPS Data Analysis:");
//             error_log("  📌 client_latitude: " . ($client_latitude ?? 'NULL'));
//             error_log("  📌 client_longitude: " . ($client_longitude ?? 'NULL'));
//             error_log("  📌 isset(client_latitude): " . (isset($_POST['client_latitude']) ? 'TRUE' : 'FALSE'));
//             error_log("  📌 isset(client_longitude): " . (isset($_POST['client_longitude']) ? 'TRUE' : 'FALSE'));
//             error_log("  📌 POST[client_latitude] value: '" . ($_POST['client_latitude'] ?? 'NOT_SET') . "'");
//             error_log("  📌 POST[client_longitude] value: '" . ($_POST['client_longitude'] ?? 'NOT_SET') . "'");

//             // Validate data
//             if (empty($adresse_livraison)) {
//                 $this->sendJsonResponse(false, 'L\'adresse de livraison est requise');
//                 return;
//             }

//             // Get client ID from session
//             $client_id = $this->getClientId();
//             if (!$client_id) {
//                 $this->sendJsonResponse(false, 'Vous devez être connecté pour passer une commande');
//                 return;
//             }

//             // Generate unique code for commande
//             $code_commande = 'CMD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

//             // Start transaction for data integrity
//             $this->commandes->beginTransaction();

//             try {
//                 // Create commande using existing method
//                 $commande_data = [
//                     'code_commande' => $code_commande,
//                     'client_id' => $client_id,
//                     'total' => $cart_total,
//                     'frais_livraison' => 0.00,
//                     'statut' => 'reçue',
//                     'paiement' => $methode_paiement,
//                     'adresse_livraison' => $adresse_livraison,
//                     'instructions' => $instructions
//                 ];

//                 $commande_id = $this->commandes->addCommande($commande_data);

//                 // Copy cart items to ligne_commande
//                 foreach ($cart_items as $item) {
//                     $this->commandes->addLigneCommande([
//                         'commande_id' => $commande_id,
//                         'plat_id' => $item['id'],
//                         'quantite' => $item['quantite'],
//                         'prix_unitaire' => $item['prix']
//                     ]);
//                 }

//                 // Create payment record
//                 $this->commandes->addPaiement([
//                     'commande_id' => $commande_id,
//                     'montant' => $cart_total,
//                     'methode' => $methode_paiement,
//                     'statut' => 'en attente'
//                 ]);
//                 Validator::notifyNodeNewOrder($code_commande);
                
//                 // DEBUG: Log before clearing cart
//                 error_log("🔔 Avant validation panier - Client: $client_id, Items: $cart_count, Total: $cart_total");
                
//                 // Mark cart as validated in database
//                 $cartCleared = CartDB::validerPanier();
                
//                 // DEBUG: Log after clearing cart
//                 error_log("✅ Après validation panier - Résultat: " . ($cartCleared ? 'SUCCÈS' : 'ÉCHEC'));
                
//                 // Double check by getting cart count after validation
//                 $cartCountAfter = CartDB::getItemCount();
//                 error_log("🔍 Nombre d'articles après validation: $cartCountAfter");
                
//                 // Enable GPS tracking with client coordinates if available
//                 $suiviAdded = $this->enableGPSTracking($commande_id, $client_latitude, $client_longitude);
//                 error_log("📋 Suivi livraison ajouté: " . ($suiviAdded ? 'SUCCÈS' : 'ÉCHEC'));
                
//                 if (!$suiviAdded) {
//                     throw new Exception("Erreur lors de l'ajout du suivi de livraison");
//                 }

//                 // Commit transaction
//                 $this->commandes->commit();

//                 $this->sendJsonResponse(true, 'Commande créée avec succès ! Votre numéro de commande est: ' . $code_commande, [
//                     'commande_id' => $commande_id,
//                     'code_commande' => $code_commande,
//                     'total' => $cart_total,
//                     'gps_enabled' => true,
//                     'client_coordinates' => [
//                         'latitude' => $client_latitude,
//                         'longitude' => $client_longitude
//                     ]
//                 ]);
                

//             } catch (Exception $e) {
//                 // Rollback on error
//                 $this->commandes->rollback();
//                 throw $e;
//             }

//         } catch (\Exception $e) {
//             error_log("CommandesControllerFixed::createFromCart error: " . $e->getMessage());
//             $this->sendJsonResponse(false, 'Erreur lors de la création de la commande: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Enable GPS tracking for new orders
//      */
//     public function enableGPSTracking($commande_id, $client_latitude = null, $client_longitude = null)
//     {
//         try {
//             // Log GPS coordinates for debugging
//             error_log("📍 Activation GPS - Commande: $commande_id");
//             error_log("📍 Coordonnées client - Lat: " . ($client_latitude ?? 'N/A') . ", Lng: " . ($client_longitude ?? 'N/A'));
            
//             // Add delivery tracking record with GPS location
//             $suiviAdded = $this->commandes->addSuiviLivraison(
//                 $commande_id,
//                 $client_latitude,
//                 $client_longitude,
//                 'en préparation'
//             );

//             if ($suiviAdded) {
//                 error_log("✅ GPS activé avec succès - Commande: $commande_id");
//             } else {
//                 error_log("❌ Échec activation GPS - Commande: $commande_id");
//             }

//             return $suiviAdded;

//         } catch (\Exception $e) {
//             error_log("CommandesControllerFixed::enableGPSTracking error: " . $e->getMessage());
//             return false;
//         }
//     }

//     /**
//      * Update GPS location for delivery tracking
//      */
//     public function updateLocation()
//     {
//         try {
//             // Force le header JSON
//             header('Content-Type: application/json');
            
//             if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//                 $this->sendJsonResponse(false, 'Méthode non autorisée');
//                 return;
//             }

//             $commande_id = $_POST['commande_id'] ?? '';
//             $latitude = $_POST['latitude'] ?? '';
//             $longitude = $_POST['longitude'] ?? '';
//             $statut = $_POST['statut'] ?? 'en route';

//             // Validation
//             if (empty($commande_id) || empty($latitude) || empty($longitude)) {
//                 $this->sendJsonResponse(false, 'Données GPS manquantes');
//                 return;
//             }

//             // Add new tracking record with GPS coordinates
//             $suiviAdded = $this->commandes->addSuiviLivraison(
//                 $commande_id,
//                 $latitude,
//                 $longitude,
//                 $statut
//             );

//             if ($suiviAdded) {
//                 error_log("✅ GPS mis à jour - Commande: $commande_id, Lat: $latitude, Lng: $longitude");
//                 $this->sendJsonResponse(true, 'Position GPS mise à jour avec succès', [
//                     'commande_id' => $commande_id,
//                     'latitude' => $latitude,
//                     'longitude' => $longitude,
//                     'timestamp' => date('Y-m-d H:i:s')
//                 ]);
//             } else {
//                 throw new Exception("Erreur lors de l'enregistrement GPS");
//             }

//         } catch (\Exception $e) {
//             error_log("CommandesControllerFixed::updateLocation error: " . $e->getMessage());
//             $this->sendJsonResponse(false, 'Erreur lors de la mise à jour GPS: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Get delivery tracking information for a command
//      */
//     public function getTracking($commande_id)
//     {
//         try {
//             // Force le header JSON
//             header('Content-Type: application/json');
            
//             if (empty($commande_id)) {
//                 $this->sendJsonResponse(false, 'ID commande manquant');
//                 return;
//             }

//             // Get latest tracking info
//             $sql = 'SELECT * FROM suivi_livraison WHERE commande_id = ? ORDER BY mise_a_jour DESC LIMIT 1';
//             $query = $this->commandes->getPdo()->prepare($sql);
//             $query->execute([$commande_id]);
//             $tracking = $query->fetch(PDO::FETCH_ASSOC);

//             if ($tracking) {
//                 $this->sendJsonResponse(true, 'Suivi trouvé', $tracking);
//             } else {
//                 $this->sendJsonResponse(false, 'Aucun suivi trouvé pour cette commande');
//             }

//         } catch (\Exception $e) {
//             error_log("CommandesControllerFixed::getTracking error: " . $e->getMessage());
//             $this->sendJsonResponse(false, 'Erreur lors de la récupération du suivi: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Get client ID from session
//      */
//     private function getClientId()
//     {
//         // Check if user is logged in
//         if (isset($_SESSION['client']) && isset($_SESSION['client']['id_client'])) {
//             return $_SESSION['client']['id_client'];
//         }

//         return null; // No guest orders allowed
//     }

//     /**
//      * Send JSON response
//      */
//     private function sendJsonResponse($success, $message, $data = [])
//     {
//         header('Content-Type: application/json');
//         header('Access-Control-Allow-Origin: *');
//         header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
//         header('Access-Control-Allow-Headers: Content-Type');
        
//         echo json_encode([
//             'status' => $success ? 1 : 0,
//             'msg' => $message,
//             'data' => $data
//         ]);
//         exit;
//     }
// }
?>