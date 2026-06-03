<?php

class ClientsController
{
    private $validator;
    private $clients;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->clients = new ModelClients();
    }

    public function index()
    {
        if (!isset($_SESSION['client'])) {
            require_once '../views/clients/login.php';
            exit;
        }
        // Afficher le profil du client connecté
        $client = $this->clients->getClientById($_SESSION['client']['id_client']);
        require_once '../views/clients/clients.php';
    }

    public function show($id)
    {
        $client = $this->clients->getClientById($id);
        if (!$client) {
            // Rediriger ou afficher erreur
            header('Location: ' . RACINE . 'clients');
            exit;
        }
        require_once '../views/clients/show.php';
    }

    public function create()
    {
        // Afficher le formulaire de création
        require_once '../views/clients/create.php';
    }

    

    public function edit($id)
    {
        $client = $this->clients->getClientById($id);
        if (!$client) {
            header('Location: ' . RACINE . 'clients');
            exit;
        }
        require_once '../views/clients/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];

            $errors = $this->validator->validateClientData($data);
            if (empty($errors)) {
                $this->clients->updateClient($id, $data);
                header('Location: ' . RACINE . 'clients');
                exit;
            } else {
                $client = $this->clients->getClientById($id);
                require_once '../views/clients/edit.php';
            }
        }
    }

    public function delete($id)
    {
        $this->clients->deleteClient($id);
        header('Location: ' . RACINE . 'clients');
        exit;
    }

    public function login()
    {
        require_once '../views/clients/login.php';
    }

    public function connexion()
    {
        // Handle AJAX login for clients
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $msg = [];

            // Simple validation (replace with real logic)
            if (Validator::isValidEmail($email)) {
                $client = $this->validator->getByElement('clients', 'email', $email);
                
                if ($client) {


                if ($this->validator->verifyPassword($password, $client['password'])) {
                    // Set session
                    $_SESSION['client'] = [
                        'id_client' => $client['id_client'],
                        'nom' => $client['nom'],
                        'email' => $client['email']
                    ];
                    $msg = ['status' => 1, 'msg' => 'Connexion réussie'];
                } else {
                    $msg = ['status' => 0, 'msg' => 'Email ou mot de passe incorrect'];
                }
            } else {
                $msg = ['status' => 0, 'msg' => 'Email introuvable'];
            }
            } else {
                $msg = ['status' => 0, 'msg' => 'Email inavide'];
            }
            echo json_encode($msg);
        }
    }

    public function register()
    {
        require_once '../views/clients/register.php';
    }

    public function add()
    {
        // Handle AJAX register for clients
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $notEmpty = Validator::validateRequiredFields($_POST);
            $msg = [];
            // Simple validation
            if ($notEmpty === true) {
                extract($_POST);
                // Check if email exists

                if (Validator::isValidEmail($email)) {
                    if (Validator::validNumber($tel, 10)) {
                        if (!$this->validator->verif("clients", "email", $email)) {
                            if (!$this->validator->verif("clients", "telephone", $tel)) {

                                $code = $this->validator->generateCode("clients", "code_client", "CLI-", 8);
                                $data = [
                                    'code_client' => $code,
                                    'nom' => $nom,
                                    'telephone' => $tel,
                                    'adresse' => $adresse,
                                    'email' => $email,
                                    'password' => password_hash($password, PASSWORD_DEFAULT)
                                ];
                                if ($this->clients->addClient($data)) {
                                    $client = $this->validator->getByElement('clients', 'code_client', $code);
                                    $_SESSION['client'] = [
                                        'id_client' => $client['id_client'],
                                        'code' => $client['code_client'],
                                        'nom' => $client['nom'],
                                        'email' => $client['email']
                                    ];
                                    $msg = ['status' => 1, 'msg' => 'Inscription réussie'];
                                } else {
                                    $msg = ['status' => 0, 'msg' => 'Erreur lors de l\'inscription'];
                                }
                            } else {
                                $msg = ['status' => 0, 'msg' => 'Téléphone déjà utilisé'];
                            }
                        } else {
                            $msg = ['status' => 0, 'msg' => 'Email déjà utilisé'];
                        }
                    } else {
                        $msg = ['status' => 0, 'msg' => 'Téléphone invalide'];
                    }
                } else {
                    $msg = ['status' => 0, 'msg' => 'Email invalide'];
                }
            } else {
                $msg = ['status' => 0, 'msg' => 'Tous les champs sont requis'];
            }
            echo json_encode($msg);
        }
    }
}
