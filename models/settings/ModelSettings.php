<?php

class ModelSettings
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    // Récupérer tous les paramètres
    public function getAllSettings()
    {
        try {
            $query = "SELECT key_name, value FROM settings";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->execute();

            $settings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $settings[$row['key_name']] = $row['value'];
            }

            return $settings;
        } catch (PDOException $e) {
            error_log("Erreur getAllSettings: " . $e->getMessage());
            return [];
        }
    }

    // Récupérer un paramètre spécifique
    public function getSetting($key_name)
    {
        try {
            $query = "SELECT value FROM settings WHERE key_name = :key_name LIMIT 1";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->bindParam(':key_name', $key_name);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['value'] : null;
        } catch (PDOException $e) {
            error_log("Erreur getSetting: " . $e->getMessage());
            return null;
        }
    }

    // Récupérer les heures d'ouverture
    public function getHeuresRestaurant()
    {
        try {
            $query = "SELECT * FROM heures_restaurant ORDER BY jour_semaine ASC";
            $stmt = $this->pdo->getCon()->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getHeuresRestaurant: " . $e->getMessage());
            return [];
        }
    }

    // Formater les heures pour affichage
    public function getFormattedHeures()
    {
        $heures = $this->getHeuresRestaurant();
        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        $formatted = [];
        foreach ($heures as $heure) {
            $jour_num = $heure['jour_semaine'];
            $jour_nom = $jours[$jour_num] ?? '';

            if ($heure['est_ferme']) {
                $formatted[$jour_nom] = 'Fermé';
            } else {
                $ouvert = substr($heure['ouvert'], 0, 5); // HH:MM
                $ferme = substr($heure['ferme'], 0, 5); // HH:MM
                $formatted[$jour_nom] = $ouvert . ' - ' . $ferme;
            }
        }

        return $formatted;
    }

    // Grouper les heures par plages similaires
    public function getGroupedHeures()
    {
        $heures = $this->getFormattedHeures();
        $grouped = [];

        $temp_group = [];
        $current_hours = null;

        foreach ($heures as $jour => $horaire) {
            if ($current_hours === null || $current_hours === $horaire) {
                $temp_group[] = $jour;
                $current_hours = $horaire;
            } else {
                // Sauvegarder le groupe précédent
                if (!empty($temp_group)) {
                    $grouped[] = [
                        'jours' => $this->formatJoursRange($temp_group),
                        'horaire' => $current_hours
                    ];
                }
                // Commencer un nouveau groupe
                $temp_group = [$jour];
                $current_hours = $horaire;
            }
        }

        // Ajouter le dernier groupe
        if (!empty($temp_group)) {
            $grouped[] = [
                'jours' => $this->formatJoursRange($temp_group),
                'horaire' => $current_hours
            ];
        }

        return $grouped;
    }

    // Formater une plage de jours
    private function formatJoursRange($jours)
    {
        if (count($jours) === 1) {
            return $jours[0];
        } elseif (count($jours) === 2) {
            return implode(' et ', $jours);
        } else {
            return $jours[0] . ' - ' . end($jours);
        }
    }
    // Récupère la valeur d'une clé dans settings
    public function getValue($key_name) {
        $stmt = $this->pdo->getCon()->prepare("SELECT value FROM settings WHERE key_name = ? LIMIT 1");
        $stmt->execute([$key_name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : null;
    }

    // Envoie un message Telegram
    public function sendTelegramApi($chat_id, $message) {
        $token = MON_TOKEN_TELEGRAM_BOT;
        $url = "https://api.telegram.org/bot$token/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        file_get_contents($url . '?' . http_build_query($data));
    }
}
