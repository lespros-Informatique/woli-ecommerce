<?php

// Admin Orders API Endpoint
// Handles AJAX requests for admin order management

session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Include necessary files
require_once '../../controllers/admin/AdminCommandesController.php';

// Check if user is admin
if (!isset($_SESSION['client']) || !isset($_SESSION['client']['role']) || $_SESSION['client']['role'] !== 'admin') {
    echo json_encode(['status' => 0, 'msg' => 'Accès non autorisé']);
    exit;
}

// Get the requested action
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'update-status':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new AdminCommandesController();
                $controller->updateStatus();
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Méthode non autorisée']);
            }
            break;
            
        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_id = $_POST['order_id'] ?? null;
                if ($order_id) {
                    $controller = new AdminCommandesController();
                    $controller->delete($order_id);
                    echo json_encode(['status' => 1, 'msg' => 'Commande supprimée']);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'ID de commande manquant']);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Méthode non autorisée']);
            }
            break;
            
        case 'confirm-delivery':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_id = $_POST['order_id'] ?? null;
                if ($order_id) {
                    // Update order status to delivered
                    require_once '../../models/commandes/ModelCommandes.php';
                    $model = new ModelCommandes();
                    $model->updateOrderStatus($order_id, 'livree', 'Livraison confirmée par le client');
                    
                    echo json_encode(['status' => 1, 'msg' => 'Livraison confirmée']);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'ID de commande manquant']);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Méthode non autorisée']);
            }
            break;
            
        case 'get-stats':
            $controller = new AdminCommandesController();
            $controller->getStats();
            break;
            
        case 'search-orders':
            // Get search parameters
            $query = $_GET['q'] ?? '';
            $status = $_GET['status'] ?? 'all';
            $payment = $_GET['payment'] ?? 'all';
            
            require_once '../../models/commandes/ModelCommandes.php';
            $model = new ModelCommandes();
            
            // Basic search implementation
            $orders = $model->getAllCommandes();
            $filtered_orders = [];
            
            foreach ($orders as $order) {
                $matches_query = empty($query) || 
                    stripos($order['code_commande'], $query) !== false ||
                    stripos($order['client_nom'], $query) !== false ||
                    stripos($order['adresse_livraison'], $query) !== false;
                    
                $matches_status = $status === 'all' || $order['statut'] === $status;
                $matches_payment = $payment === 'all' || $order['paiement'] === $payment;
                
                if ($matches_query && $matches_status && $matches_payment) {
                    $filtered_orders[] = $order;
                }
            }
            
            echo json_encode([
                'status' => 1,
                'msg' => 'Recherche effectuée',
                'data' => ['orders' => $filtered_orders]
            ]);
            break;
            
        case 'get-order-timeline':
            $order_id = $_GET['order_id'] ?? null;
            if ($order_id) {
                require_once '../../models/commandes/ModelCommandes.php';
                $model = new ModelCommandes();
                $order = $model->getCommandeById($order_id);
                
                if ($order) {
                    // Create timeline based on current status
                    $statuses = ['recue', 'preparation', 'prete', 'livree'];
                    $current_status = $order['statut'];
                    $current_index = array_search($current_status, $statuses);
                    
                    $timeline = [];
                    $status_labels = [
                        'recue' => 'Commande reçue',
                        'preparation' => 'En préparation',
                        'prete' => 'Prête',
                        'livree' => 'Livrée'
                    ];
                    
                    for ($i = 0; $i < count($statuses); $i++) {
                        $timeline[] = [
                            'status' => $status_labels[$statuses[$i]],
                            'completed' => $i <= $current_index,
                            'time' => $i < $current_index ? date('H:i', strtotime($order['created_at']) + ($i * 900)) : null,
                            'date' => $i < $current_index ? date('Y-m-d', strtotime($order['created_at'])) : null,
                            'current' => $i === $current_index
                        ];
                    }
                    
                    echo json_encode([
                        'status' => 1,
                        'msg' => 'Timeline récupérée',
                        'data' => ['timeline' => $timeline]
                    ]);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'Commande non trouvée']);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => 'ID de commande manquant']);
            }
            break;
            
        case 'send-notification':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Send notification to customer about order status
                $order_id = $_POST['order_id'] ?? null;
                $status = $_POST['status'] ?? null;
                $customer_phone = $_POST['customer_phone'] ?? null;
                
                if ($order_id && $status && $customer_phone) {
                    // Here you would integrate with SMS service (like Twilio, Africa's Talking, etc.)
                    // For now, just log the notification
                    error_log("Notification sent for order $order_id status $status to $customer_phone");
                    
                    echo json_encode([
                        'status' => 1,
                        'msg' => 'Notification envoyée avec succès'
                    ]);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'Données manquantes pour la notification']);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Méthode non autorisée']);
            }
            break;
            
        case 'validate-payment':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_id = $_POST['order_id'] ?? null;
                $payment_method = $_POST['payment_method'] ?? null;
                
                if ($order_id && $payment_method) {
                    // Here you would integrate with payment gateway (Paystack, Flutterwave, etc.)
                    // For now, just log the payment validation
                    error_log("Payment validated for order $order_id using $payment_method");
                    
                    echo json_encode([
                        'status' => 1,
                        'msg' => 'Paiement validé avec succès',
                        'data' => ['transaction_id' => 'TXN' . time()]
                    ]);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'Données de paiement manquantes']);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Méthode non autorisée']);
            }
            break;
            
        case 'get-order-analytics':
            $period = $_GET['period'] ?? 'week'; // week, month, year
            
            require_once '../../models/commandes/ModelCommandes.php';
            $model = new ModelCommandes();
            $stats = $model->getDashboardStats();
            
            // Add additional analytics based on period
            $analytics = [
                'total_orders' => $stats['total_commandes'],
                'total_revenue' => $stats['chiffre_affaires'],
                'orders_by_status' => $stats['commandes_par_statut'],
                'orders_by_payment' => [
                    'à la livraison' => 0,
                    'carte bancaire' => 0,
                    'mobile money' => 0
                ],
                'average_order_value' => $stats['total_commandes'] > 0 ? 
                    round($stats['chiffre_affaires'] / $stats['total_commandes']) : 0,
                'recent_orders' => $stats['commandes_recentes']
            ];
            
            echo json_encode([
                'status' => 1,
                'msg' => 'Analytics récupérées',
                'data' => $analytics
            ]);
            break;
            
        case 'bulk-status-update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_ids = $_POST['order_ids'] ?? [];
                $new_status = $_POST['new_status'] ?? null;
                
                if (!empty($order_ids) && $new_status) {
                    require_once '../../models/commandes/ModelCommandes.php';
                    $model = new ModelCommandes();
                    
                    $success_count = 0;
                    foreach ($order_ids as $order_id) {
                        if ($model->updateOrderStatus($order_id, $new_status, 'Mise à jour en lot')) {
                            $success_count++;
                        }
                    }
                    
                    echo json_encode([
                        'status' => 1,
                        'msg' => "$success_count commandes mises à jour avec succès"
                    ]);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'Données manquantes pour la mise à jour en lot']);
                }
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Méthode non autorisée']);
            }
            break;
            
        case 'export-orders':
            // Export orders data to CSV
            require_once '../../models/commandes/ModelCommandes.php';
            $model = new ModelCommandes();
            $orders = $model->getAllCommandes();
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="commandes_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, ['ID', 'Code', 'Client', 'Total', 'Statut', 'Paiement', 'Date']);
            
            // CSV data
            foreach ($orders as $order) {
                fputcsv($output, [
                    $order['id_commande'],
                    $order['code_commande'],
                    $order['client_nom'],
                    $order['total'],
                    $order['statut'],
                    $order['paiement'],
                    $order['created_at']
                ]);
            }
            
            fclose($output);
            exit;
            
        default:
            echo json_encode(['status' => 0, 'msg' => 'Action non reconnue']);
            break;
            
    }
} catch (Exception $e) {
    error_log("Admin Orders API Error: " . $e->getMessage());
    echo json_encode([
        'status' => 0,
        'msg' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
?>