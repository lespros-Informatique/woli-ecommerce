<?php

// require_once '../../core/CartDB.php';
// require_once '../../models/Validator.php';
// require_once '../../models/commandes/ModelCommandes.php';
// require_once '../../models/clients/ModelClients.php';
// require_once '../../models/menu/ModelPlats.php';

// // Start session if not already started
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// class AdminCommandesController
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

//     /**
//      * Check if user is admin
//      */
//     private function checkAdmin()
//     {
//         if (!isset($_SESSION['client']) || !isset($_SESSION['client']['role']) || $_SESSION['client']['role'] !== 'admin') {
//             header('Location: ' . RACINE . 'clients/login');
//             exit;
//         }
//     }

//     public function index()
//     {
//         $this->checkAdmin();
        
//         // Get all orders with statistics
//         $commandes = $this->commandes->getAllCommandes();
//         $stats = $this->commandes->getDashboardStats();
        
//         // Pass data to view
//         $data = [
//             'commandes' => $commandes,
//             'stats' => $stats
//         ];
        
//         require_once '../../views/admin/commandes/index.php';
//     }

//     public function show($id)
//     {
//         $this->checkAdmin();
        
//         $commande_complete = $this->commandes->getCommandeComplete($id);
//         if (!$commande_complete) {
//             header('Location: ' . RACINE . 'admin/commandes');
//             exit;
//         }
        
//         $commande = $commande_complete['commande'];
//         $lignes = $commande_complete['lignes'];
//         $paiements = $commande_complete['paiements'];
        
//         require_once '../../views/admin/commandes/show.php';
//     }

//     public function edit($id)
//     {
//         $this->checkAdmin();
        
//         $commande = $this->commandes->getCommandeById($id);
//         if (!$commande) {
//             header('Location: ' . RACINE . 'admin/commandes');
//             exit;
//         }
        
//         $clients = $this->clients->getAllClients();
//         require_once '../../views/admin/commandes/edit.php';
//     }

//     public function update($id)
//     {
//         $this->checkAdmin();
        
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $data = [
//                 'total' => $_POST['total'] ?? 0.00,
//                 'frais_livraison' => $_POST['frais_livraison'] ?? 0.00,
//                 'statut' => $_POST['statut'] ?? 'recue',
//                 'paiement' => $_POST['paiement'] ?? 'à la livraison',
//                 'adresse_livraison' => $_POST['adresse_livraison'] ?? '',
//                 'instructions' => $_POST['instructions'] ?? ''
//             ];

//             $errors = $this->validator->validateCommandeData($data);
//             if (empty($errors)) {
//                 $this->commandes->updateCommande($id, $data);
//                 header('Location: ' . RACINE . 'admin/commandes');
//                 exit;
//             } else {
//                 $commande = $this->commandes->getCommandeById($id);
//                 $clients = $this->clients->getAllClients();
//                 require_once '../../views/admin/commandes/edit.php';
//             }
//         }
//     }

//     /**
//      * Update order status via AJAX
//      */
//     public function updateStatus()
//     {
//         $this->checkAdmin();
        
//         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//             $this->sendJsonResponse(false, 'Méthode non autorisée');
//             return;
//         }

//         try {
//             $order_id = $_POST['order_id'] ?? null;
//             $new_status = $_POST['new_status'] ?? null;
//             $notes = $_POST['notes'] ?? '';

//             if (!$order_id || !$new_status) {
//                 $this->sendJsonResponse(false, 'Données manquantes');
//                 return;
//             }

//             // Validate status
//             $valid_statuses = ['recue', 'preparation', 'prete', 'livree', 'annulee'];
//             if (!in_array($new_status, $valid_statuses)) {
//                 $this->sendJsonResponse(false, 'Statut invalide');
//                 return;
//             }

//             // Update order status
//             $result = $this->commandes->updateOrderStatus($order_id, $new_status, $notes);

//             if ($result) {
//                 $this->sendJsonResponse(true, 'Statut mis à jour avec succès');
//             } else {
//                 $this->sendJsonResponse(false, 'Erreur lors de la mise à jour');
//             }

//         } catch (\Exception $e) {
//             error_log("AdminCommandesController::updateStatus error: " . $e->getMessage());
//             $this->sendJsonResponse(false, 'Erreur serveur: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Get orders by status (for filtering)
//      */
//     public function getByStatus($status)
//     {
//         $this->checkAdmin();
        
//         $valid_statuses = ['recue', 'preparation', 'prete', 'livree', 'annulee'];
//         if (!in_array($status, $valid_statuses)) {
//             header('Location: ' . RACINE . 'admin/commandes');
//             exit;
//         }
        
//         $commandes = $this->commandes->getCommandesByStatut($status);
//         $stats = $this->commandes->getDashboardStats();
        
//         $data = [
//             'commandes' => $commandes,
//             'stats' => $stats,
//             'current_filter' => $status
//         ];
        
//         require_once '../../views/admin/commandes/index.php';
//     }

//     /**
//      * Create new order (admin side)
//      */
//     public function create()
//     {
//         $this->checkAdmin();
        
//         $clients = $this->clients->getAllClients();
//         $plats = $this->plats->getAllPlats();
        
//         require_once '../../views/admin/commandes/create.php';
//     }

//     /**
//      * Store new order
//      */
//     public function store()
//     {
//         $this->checkAdmin();
        
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $data = [
//                 'code_commande' => $_POST['code_commande'] ?? '',
//                 'client_id' => $_POST['client_id'] ?? '',
//                 'total' => $_POST['total'] ?? 0.00,
//                 'frais_livraison' => $_POST['frais_livraison'] ?? 0.00,
//                 'statut' => $_POST['statut'] ?? 'recue',
//                 'paiement' => $_POST['paiement'] ?? 'à la livraison',
//                 'adresse_livraison' => $_POST['adresse_livraison'] ?? '',
//                 'instructions' => $_POST['instructions'] ?? ''
//             ];

//             $errors = $this->validator->validateCommandeData($data);
//             if (empty($errors)) {
//                 $this->commandes->addCommande($data);
//                 header('Location: ' . RACINE . 'admin/commandes');
//                 exit;
//             } else {
//                 $clients = $this->clients->getAllClients();
//                 $plats = $this->plats->getAllPlats();
//                 require_once '../../views/admin/commandes/create.php';
//             }
//         }
//     }

//     /**
//      * Delete order
//      */
//     public function delete($id)
//     {
//         $this->checkAdmin();
        
//         $this->commandes->deleteCommande($id);
//         header('Location: ' . RACINE . 'admin/commandes');
//         exit;
//     }

//     /**
//      * Get order statistics for dashboard
//      */
//     public function getStats()
//     {
//         $this->checkAdmin();
        
//         $stats = $this->commandes->getDashboardStats();
        
//         header('Content-Type: application/json');
//         echo json_encode($stats);
//         exit;
//     }

//     /**
//      * Send JSON response
//      */
//     private function sendJsonResponse($success, $message, $data = [])
//     {
//         header('Content-Type: application/json');
//         echo json_encode([
//             'status' => $success ? 1 : 0,
//             'msg' => $message,
//             'data' => $data
//         ]);
//         exit;
//     }
// }