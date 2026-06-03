<?php

class ModelHome
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }


    // public function getCountArtisantByStatus($id_agent,$status) // returns le nbr de client
    // {
    //     $result = 0;
    //     try {
    //         $sql = '  SELECT COUNT(*) total FROM artisant ar WHERE ar.agent_id = ? AND ar.status_artisant = ? ';

    //         $query = $this->pdo->getCon()->prepare($sql);

    //         $query->execute([$id_agent,$status]);

    //         if ($query->rowcount() > 0) {
    //             $result = $query->fetch(PDO::FETCH_ASSOC);

    //             return $result['total'];
    //         } else {
    //             return null;
    //         }
    //     } catch (\Exception $e) {
    //         die('Erreur de recuperation'.$e->getMessage());
    //     }
    // }

    
    public function getSumMontantByAgent($id_agent,$status) // returns le nombre inscription
    {
        $result = 0;
        try {
            $sql = '  SELECT SUM(v.montant) AS total FROM versements v WHERE v.agent_id = ? AND v.statut= ?';

            $query = $this->pdo->getCon()->prepare($sql);

            $query->execute([$id_agent,$status]);

            if ($query->rowcount() > 0) {
                $result = $query->fetch(PDO::FETCH_ASSOC);

                return $result['total'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            die('Erreur de recuperation'.$e->getMessage());
        }
    }

    public function getCountAppartByStatus($pro,$status) // returns le nbr de client
    {
        $result = 0;
        try {
            $sql = '  SELECT COUNT(*) total FROM appart a WHERE a.pro_id= ? AND a.status = ? ';

            $query = $this->pdo->getCon()->prepare($sql);

            $query->execute([$pro,$status]);

            if ($query->rowcount() > 0) {
                $result = $query->fetch(PDO::FETCH_ASSOC);

                return (int)$result['total'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            die('Erreur de recuperation'.$e->getMessage());
        }
    }

    public function getCountPro() // returns le nbr de client
    {
        $result = 0;
        try {
            $sql = '  SELECT COUNT(*) total FROM proprietaire';

            $query = $this->pdo->getCon()->prepare($sql);

            $query->execute();

            if ($query->rowcount() > 0) {
                $result = $query->fetch(PDO::FETCH_ASSOC);

                return $result['total'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            die('Erreur de recuperation'.$e->getMessage());
        }
    }

    public function getSumMontant($status) // returns le nombre inscription
    {
        $result = 0;
        try {
            $sql = '  SELECT SUM(py.montant_paiement) AS total FROM versements py WHERE py.status= ?';

            $query = $this->pdo->getCon()->prepare($sql);

            $query->execute([$status]);

            if ($query->rowcount() > 0) {
                $result = $query->fetch(PDO::FETCH_ASSOC);

                return $result['total'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            die('Erreur de recuperation'.$e->getMessage());
        }
    }

    public function getCountAppartByPro($id_pro) // returns le nbr de client
    {
        $result = 0;
        try {
            $sql = '  SELECT COUNT(*) total FROM appart WHERE pro_id =?';

            $query = $this->pdo->getCon()->prepare($sql);

            $query->execute([$id_pro]);

            if ($query->rowcount() > 0) {
                $result = $query->fetch(PDO::FETCH_ASSOC);

                return $result['total'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            die('Erreur de recuperation'.$e->getMessage());
        }
    }
}
