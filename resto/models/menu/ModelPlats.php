<?php

class ModelPlats
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    public function getAllPlats()
    {
        try {
            $sql = 'SELECT p.*, c.nom as categorie_nom FROM plats p LEFT JOIN categories c ON p.category_id = c.id_category WHERE p.disponible = 1 ORDER BY c.nom, p.nom';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des plats: ' . $e->getMessage());
        }
    }

    public function getPlatsByCategory($category_id)
    {
        try {
            $sql = 'SELECT * FROM plats WHERE category_id = ? AND disponible = 1 ORDER BY nom';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$category_id]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des plats par catégorie: ' . $e->getMessage());
        }
    }

    public function getPlatById($id)
    {
        try {
            $sql = 'SELECT p.*, c.nom as categorie_nom FROM plats p LEFT JOIN categories c ON p.category_id = c.id_category WHERE p.id_plat = ? AND p.disponible = 1';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération du plat: ' . $e->getMessage());
        }
    }

    public function getCategories()
    {
        try {
            $sql = 'SELECT * FROM categories ORDER BY nom';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des catégories: ' . $e->getMessage());
        }
    }

    public function getPlatsByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }
        try {
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "SELECT p.*, c.nom as categorie_nom FROM plats p LEFT JOIN categories c ON p.category_id = c.id_category WHERE p.id_plat IN ($placeholders) AND p.disponible = 1";
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute($ids);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des plats par IDs: ' . $e->getMessage());
        }
    }
}