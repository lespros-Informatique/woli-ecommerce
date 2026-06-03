<?php

class ModelPromotions
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    // Récupérer toutes les promotions actives
    public function getActivePromotions()
    {
        try {
            $today = date('Y-m-d');
            $query = "SELECT * FROM promotions 
                      WHERE actif = 1 
                      AND (date_debut IS NULL OR date_debut <= :today)
                      AND (date_fin IS NULL OR date_fin >= :today)
                      ORDER BY ordre ASC, created_at DESC";

            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':today', $today);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getActivePromotions: " . $e->getMessage());
            return [];
        }
    }

    // Récupérer toutes les promotions (pour l'admin)
    public function getAllPromotions()
    {
        try {
            $query = "SELECT * FROM promotions ORDER BY ordre ASC, created_at DESC";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAllPromotions: " . $e->getMessage());
            return [];
        }
    }

    // Récupérer une promotion par ID
    public function getPromotionById($id)
    {
        try {
            $query = "SELECT * FROM promotions WHERE id_promotion = :id LIMIT 1";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getPromotionById: " . $e->getMessage());
            return null;
        }
    }

    // Ajouter une nouvelle promotion
    public function addPromotion($data)
    {
        try {
            $query = "INSERT INTO promotions 
                      (titre, description, pourcentage_reduction, image, lien_url, actif, ordre, date_debut, date_fin) 
                      VALUES 
                      (:titre, :description, :pourcentage, :image, :lien, :actif, :ordre, :date_debut, :date_fin)";

            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':titre', $data['titre']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':pourcentage', $data['pourcentage_reduction']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':lien', $data['lien_url']);
            $stmt->bindParam(':actif', $data['actif']);
            $stmt->bindParam(':ordre', $data['ordre']);
            $stmt->bindParam(':date_debut', $data['date_debut']);
            $stmt->bindParam(':date_fin', $data['date_fin']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur addPromotion: " . $e->getMessage());
            return false;
        }
    }

    // Mettre à jour une promotion
    public function updatePromotion($id, $data)
    {
        try {
            $query = "UPDATE promotions SET 
                      titre = :titre,
                      description = :description,
                      pourcentage_reduction = :pourcentage,
                      image = :image,
                      lien_url = :lien,
                      actif = :actif,
                      ordre = :ordre,
                      date_debut = :date_debut,
                      date_fin = :date_fin
                      WHERE id_promotion = :id";

            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':titre', $data['titre']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':pourcentage', $data['pourcentage_reduction']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':lien', $data['lien_url']);
            $stmt->bindParam(':actif', $data['actif']);
            $stmt->bindParam(':ordre', $data['ordre']);
            $stmt->bindParam(':date_debut', $data['date_debut']);
            $stmt->bindParam(':date_fin', $data['date_fin']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur updatePromotion: " . $e->getMessage());
            return false;
        }
    }

    // Supprimer une promotion
    public function deletePromotion($id)
    {
        try {
            $query = "DELETE FROM promotions WHERE id_promotion = :id";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur deletePromotion: " . $e->getMessage());
            return false;
        }
    }

    // Activer/Désactiver une promotion
    public function toggleActive($id)
    {
        try {
            $query = "UPDATE promotions SET actif = NOT actif WHERE id_promotion = :id";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur toggleActive: " . $e->getMessage());
            return false;
        }
    }
}
