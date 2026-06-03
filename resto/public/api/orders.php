<?php

// API Routes for Orders Management
// This file handles all AJAX requests for order management

session_start();

// Include necessary files
require_once '../controllers/admin/AdminCommandesController.php';
require_once '../controllers/commandes/CommandesController.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get the requested action
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'update-status':
            $controller = new AdminCommandesController();
            $controller->updateStatus();
            break;
            
        case 'delete-order':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_id = $_POST['order_id'] ?? null;
                if ($order_id) {
                    $controller = new AdminCommandesController();
                    // Add delete method if needed
                    $controller->delete($order_id);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'ID de commande manquant']);
                }
            }
            break;
            
        case 'confirm-delivery':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_id = $_POST['order_id'] ?? null;
                if ($order_id) {
                    // Update order status to delivered
                    $controller = new AdminCommandesController();
                    // Logic to confirm delivery
                    echo json_encode(['status' => 1, 'msg' => 'Livraison confirmée']);
                } else {
                    echo json_encode(['status' => 0, 'msg' => 'ID de commande manquant']);
                }
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
            
            // This would typically query the database and return filtered results
            // For now, return a success response
            echo json_encode([
                'status' => 1,
                'msg' => 'Recherche effectuée',
                'data' => ['results' => []]
            ]);
            break;
            
        case 'get-order-timeline':
            $order_id = $_GET['order_id'] ?? null;
            if ($order_id) {
                // Return order timeline data
                $timeline = [
                    [
                        'status' => 'Commande reçue',
                        'completed' => true,
                        'time' => '14:30',
                        'date' => date('Y-m-d')
                    ],
                    [
                        'status' => 'En préparation',
                        'completed' => true,
                        'time' => '14:45',
                        'date' => date('Y-m-d')
                    ],
                    [
                        'status' => 'Prête',
                        'completed' => false,
                        'time' => null,
                        'date' => null
                    ],
                    [
                        'status' => 'En livraison',
                        'completed' => false,
                        'time' => null,
                        'date' => null
                    ],
                    [
                        'status' => 'Livrée',
                        'completed' => false,
                        'time' => null,
                        'date' => null
                    ]
                ];
                
                echo json_encode([
                    'status' => 1,
                    'msg' => 'Timeline récupérée',
                    'data' => ['timeline' => $timeline]
                ]);
            } else {
                echo json_encode(['status' => 0, 'msg' => 'ID de commande manquant']);
            }
            break;
            
        case 'send-notification':
            // Send notification to customer about order status
            $order_id = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $customer_phone = $_POST['customer_phone'] ?? null;
            
            if ($order_id && $status && $customer_phone) {
                // Here you would integrate with SMS/Email service
                // For now, return success
                echo json_encode([
                    'status' => 1,
                    'msg' => 'Notification envoyée'
                ]);
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Données manquantes']);
            }
            break;
            
        case 'validate-payment':
            // Validate payment for an order
            $order_id = $_POST['order_id'] ?? null;
            $payment_method = $_POST['payment_method'] ?? null;
            
            if ($order_id && $payment_method) {
                // Here you would integrate with payment gateway
                // For now, return success
                echo json_encode([
                    'status' => 1,
                    'msg' => 'Paiement validé',
                    'data' => ['transaction_id' => 'TXN' . time()]
                ]);
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Données de paiement manquantes']);
            }
            break;
            
        case 'update-delivery-address':
            // Update delivery address for an order
            $order_id = $_POST['order_id'] ?? null;
            $new_address = $_POST['new_address'] ?? null;
            
            if ($order_id && $new_address) {
                // Update address in database
                echo json_encode([
                    'status' => 1,
                    'msg' => 'Adresse de livraison mise à jour'
                ]);
            } else {
                echo json_encode(['status' => 0, 'msg' => 'Données manquantes']);
            }
            break;
            
        case 'get-order-analytics':
            // Get analytics data for orders
            $period = $_GET['period'] ?? 'week'; // week, month, year
            
            $analytics = [
                'total_orders' => 150,
                'total_revenue' => 1250000,
                'average_order_value' => 8333,
                'orders_by_status' => [
                    'recue' => 25,
                    'preparation' => 15,
                    'prete' => 10,
                    'livree' => 95,
                    'annulee' => 5
                ],
                'orders_by_payment' => [
                    'à la livraison' => 100,
                    'carte bancaire' => 30,
                    'mobile money' => 20
                ]
            ];
            
            echo json_encode([
                'status' => 1,
                'msg' => 'Analytics récupérées',
                'data' => $analytics
            ]);
            break;
            
        default:
            echo json_encode(['status' => 0, 'msg' => 'Action non reconnue']);
            break;
            
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    echo json_encode([
        'status' => 0,
        'msg' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
?>