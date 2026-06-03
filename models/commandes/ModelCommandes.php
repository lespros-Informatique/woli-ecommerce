<?php

class ModelCommandes
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    public function getAllCommandes()
    {
        try {
            $sql = 'SELECT c.*, cl.nom as client_nom FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id_client ORDER BY c.created_at DESC';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des commandes: ' . $e->getMessage());
        }
    }

    public function getCommandeById($id)
    {
        try {
            $sql = 'SELECT c.*, cl.nom as client_nom, cl.telephone, cl.adresse, p.titre as promotion_titre, p.pourcentage_reduction as promotion_pourcentage 
                    FROM commandes c 
                    LEFT JOIN clients cl ON c.client_id = cl.id_client 
                    LEFT JOIN promotions p ON c.promotion_id = p.id_promotion 
                    WHERE c.id_commande = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Get paginated commandes for a specific client
     */
    public function getCommandesByClient($client_id, $page = 1, $limit = 4)
    {
        try {
            $offset = ($page - 1) * $limit;

            // Get total count for pagination
            $countSql = 'SELECT COUNT(*) as total FROM commandes c WHERE c.client_id = ?';
            $countQuery = $this->pdo->getCon()->prepare($countSql);
            $countQuery->execute([$client_id]);
            $totalCount = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

            // Get paginated results
            $sql = 'SELECT c.*, cl.nom as client_nom
                   FROM commandes c
                   LEFT JOIN clients cl ON c.client_id = cl.id_client
                   WHERE c.client_id = ?
                   ORDER BY c.created_at DESC
                   LIMIT ? OFFSET ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$client_id, $limit, $offset]);
            $commandes = $query->fetchAll(PDO::FETCH_ASSOC);

            return [
                'data' => $commandes,
                'total' => $totalCount,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($totalCount / $limit)
            ];
        } catch (\Exception $e) {
            die('Erreur de récupération des commandes du client: ' . $e->getMessage());
        }
    }

    /**
     * Get all commandes for a client (legacy method for backward compatibility)
     */
    public function getAllCommandesByClient($client_id)
    {
        try {
            $sql = 'SELECT c.*, cl.nom as client_nom FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id_client WHERE c.client_id = ? ORDER BY c.created_at DESC';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$client_id]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des commandes du client: ' . $e->getMessage());
        }
    }

    public function addCommande($data)
    {
        try {
            $sql = 'INSERT INTO commandes (code_commande, client_id, total, promotion_id, montant_reduction, frais_livraison, statut, paiement, type_commande, adresse_livraison, instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([
                $data['code_commande'],
                $data['client_id'],
                $data['total'],
                $data['promotion_id'] ?? null,
                $data['montant_reduction'] ?? 0.00,
                $data['frais_livraison'] ?? 0.00,
                $data['statut'] ?? 'reçue',
                $data['paiement'] ?? 'à la livraison',
                $data['type_commande'] ?? 'livraison',
                $data['adresse_livraison'],
                $data['instructions'] ?? ''
            ]);
            return $this->pdo->getCon()->lastInsertId();
        } catch (\Exception $e) {
            die('Erreur d\'ajout de la commande: ' . $e->getMessage());
        }
    }

    public function updateCommande($id, $data)
    {
        try {
            $sql = 'UPDATE commandes SET total = ?, frais_livraison = ?, statut = ?, paiement = ?, adresse_livraison = ?, instructions = ?, updated_at = CURRENT_TIMESTAMP WHERE id_commande = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([
                $data['total'],
                $data['frais_livraison'],
                $data['statut'],
                $data['paiement'],
                $data['adresse_livraison'],
                $data['instructions'],
                $id
            ]);
            return $query->rowCount();
        } catch (\Exception $e) {
            die('Erreur de mise à jour de la commande: ' . $e->getMessage());
        }
    }

    public function deleteCommande($id)
    {
        try {
            $sql = 'DELETE FROM commandes WHERE id_commande = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$id]);
            return $query->rowCount();
        } catch (\Exception $e) {
            die('Erreur de suppression de la commande: ' . $e->getMessage());
        }
    }

    public function getCountCommandes()
    {
        try {
            $sql = 'SELECT COUNT(*) as total FROM commandes';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (\Exception $e) {
            die('Erreur de comptage des commandes: ' . $e->getMessage());
        }
    }

    public function getCommandesByStatut($statut)
    {
        try {
            $sql = 'SELECT c.*, cl.nom as client_nom FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id_client WHERE c.statut = ? ORDER BY c.created_at DESC';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$statut]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des commandes par statut: ' . $e->getMessage());
        }
    }

    /**
     * Add item to ligne_commande table
     */
    public function addLigneCommande($data)
    {
        try {
            $sql = 'INSERT INTO ligne_commande (commande_id, plat_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)';
            $query = $this->pdo->getCon()->prepare($sql);
            return $query->execute([
                $data['commande_id'],
                $data['plat_id'],
                $data['quantite'],
                $data['prix_unitaire']
            ]);
        } catch (\Exception $e) {
            error_log("Erreur ajout ligne_commande: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Add payment record
     */
    public function addPaiement($data)
    {
        try {
            $sql = 'INSERT INTO paiements (commande_id, montant, methode, statut) VALUES (?, ?, ?, ?)';
            $query = $this->pdo->getCon()->prepare($sql);
            return $query->execute([
                $data['commande_id'],
                $data['montant'],
                $data['methode'] ?? 'à la livraison',
                $data['statut'] ?? 'en attente'
            ]);
        } catch (\Exception $e) {
            error_log("Erreur ajout paiement: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get ligne_commande by commande_id
     */
    public function getLignesCommande($commande_id)
    {
        try {
            $sql = 'SELECT lc.*, p.nom as plat_nom, p.image as plat_image 
                   FROM ligne_commande lc 
                   LEFT JOIN plats p ON lc.plat_id = p.id_plat 
                   WHERE lc.commande_id = ? 
                   ORDER BY lc.created_at DESC';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$commande_id]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Erreur récupération lignes_commande: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Transaction methods for data integrity
     */
    public function beginTransaction()
    {
        return $this->pdo->getCon()->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->getCon()->commit();
    }

    public function rollback()
    {
        return $this->pdo->getCon()->rollBack();
    }

    /**
     * Get order with line items and payment info
     */
    public function getCommandeComplete($commande_id)
    {
        try {
            // Get commande with client info
            $commande = $this->getCommandeById($commande_id);
            if (!$commande) {
                return null;
            }

            // Get line items
            $lignes = $this->getLignesCommande($commande_id);

            // Get payment info
            $paiements = $this->getPaiementsCommande($commande_id);

            return [
                'commande' => $commande,
                'lignes' => $lignes,
                'paiements' => $paiements
            ];
        } catch (\Exception $e) {
            error_log("Erreur récupération commande complète: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update order status with notes
     */
    public function updateOrderStatus($commande_id, $new_status, $notes = '')
    {
        try {
            $sql = 'UPDATE commandes SET statut = ?, updated_at = CURRENT_TIMESTAMP WHERE id_commande = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $result = $query->execute([$new_status, $commande_id]);

            // Log status change (you can create a separate status_changes table if needed)
            if (!empty($notes)) {
                error_log("Order $commande_id status changed to $new_status. Notes: $notes");
            }

            return $result;
        } catch (\Exception $e) {
            error_log("Erreur mise à jour statut commande: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payments for a commande
     */
    public function getPaiementsCommande($commande_id)
    {
        try {
            $sql = 'SELECT * FROM paiements WHERE commande_id = ? ORDER BY created_at DESC';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$commande_id]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Erreur récupération paiements: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        try {
            $stats = [];

            // Total orders
            $sql = 'SELECT COUNT(*) as total FROM commandes';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            $stats['total_commandes'] = $query->fetch(PDO::FETCH_ASSOC)['total'];

            // Total revenue
            $sql = 'SELECT SUM(total) as total FROM commandes WHERE statut != "annulée"';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            $stats['chiffre_affaires'] = $query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Orders by status
            $sql = 'SELECT statut, COUNT(*) as count FROM commandes GROUP BY statut';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            $stats['commandes_par_statut'] = $query->fetchAll(PDO::FETCH_KEY_PAIR);

            // Recent orders
            $sql = 'SELECT c.*, cl.nom as client_nom FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id_client ORDER BY c.created_at DESC LIMIT 10';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            $stats['commandes_recentes'] = $query->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (\Exception $e) {
            error_log("Erreur récupération stats dashboard: " . $e->getMessage());
            return [
                'total_commandes' => 0,
                'chiffre_affaires' => 0,
                'commandes_par_statut' => [],
                'commandes_recentes' => []
            ];
        }
    }

    /**
     * Get PDO connection for direct queries
     */
    public function getPdo()
    {
        return $this->pdo->getCon();
    }

    /**
     * Add delivery tracking record with GPS location
     */
    public function addSuiviLivraison($commande_id, $latitude = null, $longitude = null, $statut = 'en préparation')
    {
        try {
            $sql = 'INSERT INTO suivi_livraison (commande_id, user_id, latitude, longitude, statut, mise_a_jour) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)';
            $query = $this->pdo->getCon()->prepare($sql);
            $result = $query->execute([
                $commande_id,
                null, // user_id null as requested
                $latitude,
                $longitude,
                $statut
            ]);

            // Log GPS data for debugging
            error_log("📍 addSuiviLivraison - Commande: $commande_id, Lat: " . ($latitude ?? 'null') . ", Lng: " . ($longitude ?? 'null'));

            return $result;
        } catch (\Exception $e) {
            error_log("Erreur ajout suivi_livraison: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate discount amount based on promotion
     */
    public function calculateDiscount($sous_total, $promotion_id)
    {
        try {
            if (empty($promotion_id)) {
                return 0;
            }

            // Get promotion details
            $sql = 'SELECT pourcentage_reduction, actif, date_debut, date_fin FROM promotions WHERE id_promotion = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$promotion_id]);
            $promo = $query->fetch(PDO::FETCH_ASSOC);

            if (!$promo || !$promo['actif']) {
                return 0;
            }

            // Check dates
            $today = date('Y-m-d');
            if (!empty($promo['date_debut']) && $promo['date_debut'] > $today) {
                return 0;
            }
            if (!empty($promo['date_fin']) && $promo['date_fin'] < $today) {
                return 0;
            }

            // Calculate discount
            $pourcentage = $promo['pourcentage_reduction'];
            $montant_reduction = $sous_total * ($pourcentage / 100);

            return round($montant_reduction, 2);
        } catch (\Exception $e) {
            error_log("Erreur calcul réduction: " . $e->getMessage());
            return 0;
        }
    }
}