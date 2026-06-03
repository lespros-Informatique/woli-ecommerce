<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_set_cookie_params(3600); // 1 heure
session_start();

date_default_timezone_set('Africa/Abidjan');

class Database
{
    private $pdo;
    private $dns;
//    private $dbname = 'c2588565c_resto';
//     private $user = 'c2588565c_kassann';
//     private $password = 'c2588565c_kassann';
//     private $host = 'localhost';
    private $dbname = 'db_restaurant';
    private $user = 'root';
    private $password = '';
    private $host = 'localhost';
    
    // private $dbname = 'c2156819c_test';
    // private $user = 'root';
    // private $password = '';
    // private $host = 'localhost';

    public function __construct()
    {
        try {
            $this->dns = "mysql:host=$this->host;dbname=$this->dbname";
            $this->pdo = new PDO($this->dns, $this->user, $this->password, [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            ]);
        } catch (Exception $e) {
            die('Erreur de connexion à la base de données '.$e->getMessage());
        }
    }

    public function getCon()
    {
        return $this->pdo;
    }
}

include 'const.php';
