<?php

require_once '../config/Database.php';

class ModelCart
{
    private $db;
    private $table_panier = 'paniers';
    private $table_ligne_panier = 'ligne_panier';

    public function __construct()
    {
        $this->db = (new Database())->getCon();
    }

    /**
     * Obtenir le panier actif d'un client
     */
    public function getPanierClient($client_id)
    {
        try {
            $sql = "SELECT * FROM {$this->table_panier}
                   WHERE client_id = ? AND statut = 'actif'
                   LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$client_id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getPanierClient: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Créer un nouveau panier pour un client
     */
    public function createPanier($client_id)
    {
        try {
            // Vérifier si un panier actif existe déjà
            $panier_existant = $this->getPanierClient($client_id);
            if ($panier_existant) {
                return $panier_existant['id_panier'];
            }

            $sql = "INSERT INTO {$this->table_panier} (client_id, statut) VALUES (?, 'actif')";
            $stmt = $this->db->prepare($sql);

            if ($stmt->execute([$client_id])) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log("Erreur createPanier: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les lignes d'un panier
     */
    public function getLignesPanier($panier_id)
    {
        try {
            $sql = "SELECT * FROM {$this->table_ligne_panier}
                   WHERE panier_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$panier_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getLignesPanier: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ajouter un article au panier
     */
    public function addToCart($client_id, $plat_id, $nom, $prix, $quantite = 1, $image = null)
    {
        try {
            // Obtenir ou créer le panier
            $panier_id = $this->getPanierClient($client_id);
            if (!$panier_id) {
                $panier_id = $this->createPanier($client_id);
                if (!$panier_id) {
                    return false;
                }
            } else {
                $panier_id = $panier_id['id_panier'];
            }

            // Vérifier si l'article existe déjà dans le panier
            $existing_item = $this->getLignePanier($panier_id, $plat_id);

            if ($existing_item) {
                // Mettre à jour la quantité
                $new_quantite = $existing_item['quantite'] + $quantite;
                return $this->updateQuantity($panier_id, $plat_id, $new_quantite);
            } else {
                // Ajouter un nouvel article
                $sql = "INSERT INTO {$this->table_ligne_panier}
                       (panier_id, plat_id, quantite, prix_unitaire, nom_plat, image_plat)
                       VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);

                return $stmt->execute([$panier_id, $plat_id, $quantite, $prix, $nom, $image]);
            }
        } catch (Exception $e) {
            error_log("Erreur addToCart: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir une ligne spécifique du panier
     */
    public function getLignePanier($panier_id, $plat_id)
    {
        try {
            $sql = "SELECT * FROM {$this->table_ligne_panier}
                   WHERE panier_id = ? AND plat_id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$panier_id, $plat_id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getLignePanier: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mettre à jour la quantité d'un article
     */
    public function updateQuantity($panier_id, $plat_id, $quantite)
    {
        try {
            if ($quantite <= 0) {
                // Supprimer l'article si quantité = 0
                return $this->removeFromCart($panier_id, $plat_id);
            }

            $sql = "UPDATE {$this->table_ligne_panier}
                   SET quantite = ?, updated_at = CURRENT_TIMESTAMP
                   WHERE panier_id = ? AND plat_id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$quantite, $panier_id, $plat_id]);
        } catch (Exception $e) {
            error_log("Erreur updateQuantity: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer un article du panier
     */
    public function removeFromCart($panier_id, $plat_id)
    {
        try {
            $sql = "DELETE FROM {$this->table_ligne_panier}
                   WHERE panier_id = ? AND plat_id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$panier_id, $plat_id]);
        } catch (Exception $e) {
            error_log("Erreur removeFromCart: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vider complètement le panier
     */
    public function clearCart($panier_id)
    {
        try {
            $sql = "DELETE FROM {$this->table_ligne_panier} WHERE panier_id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$panier_id]);
        } catch (Exception $e) {
            error_log("Erreur clearCart: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir le nombre d'articles dans le panier
     */
    public function getItemCount($panier_id)
    {
        try {
            $sql = "SELECT SUM(quantite) as total FROM {$this->table_ligne_panier}
                   WHERE panier_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$panier_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int) ($row['total'] ?? 0);
        } catch (Exception $e) {
            error_log("Erreur getItemCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtenir le total du panier
     */
    public function getCartTotal($panier_id)
    {
        try {
            $sql = "SELECT SUM(quantite * prix_unitaire) as total FROM {$this->table_ligne_panier}
                   WHERE panier_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$panier_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return (float) ($row['total'] ?? 0);
        } catch (Exception $e) {
            error_log("Erreur getCartTotal: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Marquer un panier comme validé et supprimer ses articles
     */
    public function validerPanier($panier_id)
    {
        try {
            // Supprimer d'abord toutes les lignes du panier
            $delete_sql = "DELETE FROM {$this->table_ligne_panier} WHERE panier_id = ?";
            $delete_stmt = $this->db->prepare($delete_sql);
            $delete_stmt->execute([$panier_id]);

            // Ensuite marquer le panier comme validé
            $update_sql = "UPDATE {$this->table_panier}
                          SET statut = 'validé', updated_at = CURRENT_TIMESTAMP
                          WHERE id_panier = ?";
            $stmt = $this->db->prepare($update_sql);
            $result = $stmt->execute([$panier_id]);

            if ($result) {
                error_log("✅ Panier $panier_id validé et vidé avec succès");
            }

            return $result;
        } catch (Exception $e) {
            error_log("❌ Erreur validerPanier: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marquer un panier comme abandonné
     */
    public function abandonnerPanier($panier_id)
    {
        try {
            $sql = "UPDATE {$this->table_panier}
                   SET statut = 'abandonné', updated_at = CURRENT_TIMESTAMP
                   WHERE id_panier = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$panier_id]);
        } catch (Exception $e) {
            error_log("Erreur abandonnerPanier: " . $e->getMessage());
            return false;
        }
    }
}