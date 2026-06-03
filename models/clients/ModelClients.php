<?php

class ModelClients
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    public function getAllClients()
    {
        try {
            $sql = 'SELECT * FROM clients ORDER BY created_at DESC';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération des clients: ' . $e->getMessage());
        }
    }

    public function getClientById($id)
    {
        try {
            $sql = 'SELECT * FROM clients WHERE id_client = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération du client: ' . $e->getMessage());
        }
    }

    public function getClientByCode($code)
    {
        try {
            $sql = 'SELECT * FROM clients WHERE code_client = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([$code]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die('Erreur de récupération du client: ' . $e->getMessage());
        }
    }

    public function addClient($data)
    {
        try {
            $sql = 'INSERT INTO clients (code_client, nom, telephone, adresse, email, password) VALUES (?, ?, ?, ?, ?, ?)';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([
                $data['code_client'],
                $data['nom'],
                $data['telephone'],
                $data['adresse'],
                $data['email'],
                $data['password']
            ]);
            return $this->pdo->getCon()->lastInsertId();
        } catch (\Exception $e) {
            die('Erreur d\'ajout du client: ' . $e->getMessage());
        }
    }

    public function updateClient($id, $data)
    {
        try {
            $sql = 'UPDATE clients SET nom = ?, telephone = ?, adresse = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id_client = ?';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute([
                $data['nom'],
                $data['telephone'],
                $data['adresse'],
                $data['email'],
                $id
            ]);
            return $query->rowCount();
        } catch (\Exception $e) {
            die('Erreur de mise à jour du client: ' . $e->getMessage());
        }
    }

    public function getCountClients()
    {
        try {
            $sql = 'SELECT COUNT(*) as total FROM clients';
            $query = $this->pdo->getCon()->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (\Exception $e) {
            die('Erreur de comptage des clients: ' . $e->getMessage());
        }
    }
}