<?php

require_once '../models/commandes/ModelCommandes.php';
require_once '../models/clients/ModelClients.php';
require_once '../models/menu/ModelPlats.php';

class DashboardController
{
    private $commandes;
    private $clients;
    private $plats;

    public function __construct()
    {
        $this->commandes = new ModelCommandes();
        $this->clients = new ModelClients();
        $this->plats = new ModelPlats();
    }

    public function index()
    {
        try {
            // Get dashboard statistics
            $stats = $this->getDashboardStats();
            
            // Get recent orders
            $commandes_recentes = $this->commandes->getAllCommandes();
            $commandes_recentes = array_slice($commandes_recentes, 0, 10);
            
            // Get clients count
            $clients_count = count($this->clients->getAllClients());
            
            // Get plats count
            $plats_count = count($this->plats->getAllPlats());
            
            // Pass data to view
            $dashboard_data = [
                'stats' => $stats,
                'commandes_recentes' => $commandes_recentes,
                'clients_count' => $clients_count,
                'plats_count' => $plats_count
            ];
            
            require_once '../views/admin/dashboard.php';
            
        } catch (Exception $e) {
            error_log("Dashboard error: " . $e->getMessage());
            $dashboard_data = [
                'stats' => $this->getDefaultStats(),
                'commandes_recentes' => [],
                'clients_count' => 0,
                'plats_count' => 0,
                'error' => $e->getMessage()
            ];
            
            require_once '../views/admin/dashboard.php';
        }
    }

    /**
     * Get comprehensive dashboard statistics
     */
    private function getDashboardStats()
    {
        try {
            $stats = [];
            
            // Total orders
            $stats['total_commandes'] = $this->commandes->getCountCommandes();
            
            // Revenue statistics
            $all_commandes = $this->commandes->getAllCommandes();
            $total_revenue = 0;
            $pending_revenue = 0;
            
            foreach ($all_commandes as $commande) {
                if ($commande['statut'] !== 'annulée') {
                    $total_revenue += $commande['total'];
                    if (in_array($commande['statut'], ['reçue', 'en préparation'])) {
                        $pending_revenue += $commande['total'];
                    }
                }
            }
            
            $stats['chiffre_affaires_total'] = $total_revenue;
            $stats['chiffre_affaires_en_attente'] = $pending_revenue;
            
            // Orders by status
            $stats['commandes_par_statut'] = $this->getOrdersByStatus();
            
            // Monthly statistics
            $stats['commandes_par_mois'] = $this->getOrdersByMonth();
            
            // Top dishes
            $stats['plats_populaires'] = $this->getTopDishes();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error getting dashboard stats: " . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Get orders grouped by status
     */
    private function getOrdersByStatus()
    {
        try {
            $sql = "SELECT statut, COUNT(*) as count, SUM(total) as total FROM commandes GROUP BY statut";
            $pdo = new Database();
            $query = $pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting orders by status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get orders grouped by month
     */
    private function getOrdersByMonth()
    {
        try {
            $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as mois, COUNT(*) as commandes, SUM(total) as chiffre_affaires FROM commandes GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY mois DESC LIMIT 12";
            $pdo = new Database();
            $query = $pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting orders by month: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top selling dishes
     */
    private function getTopDishes()
    {
        try {
            $sql = "SELECT p.nom, SUM(lc.quantite) as total_vendu, SUM(lc.quantite * lc.prix_unitaire) as chiffre_affaires 
                   FROM ligne_commande lc 
                   JOIN plats p ON lc.plat_id = p.id_plat 
                   GROUP BY lc.plat_id, p.nom 
                   ORDER BY total_vendu DESC 
                   LIMIT 10";
            $pdo = new Database();
            $query = $pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting top dishes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Default stats when there's an error
     */
    private function getDefaultStats()
    {
        return [
            'total_commandes' => 0,
            'chiffre_affaires_total' => 0,
            'chiffre_affaires_en_attente' => 0,
            'commandes_par_statut' => [],
            'commandes_par_mois' => [],
            'plats_populaires' => []
        ];
    }
}