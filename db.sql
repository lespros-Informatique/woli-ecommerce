-- =========================
-- TABLE USERS (ADMIN + BACKOFFICE)
-- =========================
CREATE TABLE users (
    id_user INT AUTO_INCREMENT  PRIMARY KEY,
    code_user VARCHAR(50) NOT NULL,

    nom_user VARCHAR(100),
    email_user VARCHAR(100) UNIQUE,
    PASSWORD_user VARCHAR(255) NOT NULL,

    created_at_user DATETIME,

    statut_user ENUM('actif','inactif') DEFAULT 'actif'

);
CREATE TABLE roles (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    code_role VARCHAR(50) NOT NULL,

    libelle_role VARCHAR(50) NOT NULL, 

    description_role TEXT NULL,
    statut_role ENUM('actif','inactif') DEFAULT 'actif'

);

CREATE TABLE users_roles (
    id_user_role INT AUTO_INCREMENT PRIMARY KEY,

    users_code VARCHAR(50),
    roles_code VARCHAR(50),

    UNIQUE KEY uk_user_role (
        users_code,
        roles_code
    ),

    FOREIGN KEY (users_code)
        REFERENCES users(code_user),

    FOREIGN KEY (roles_code)
        REFERENCES roles(code_role)
);
CREATE TABLE permissions (
    id_permission INT AUTO_INCREMENT PRIMARY KEY,
    code_permission VARCHAR(50) NOT NULL UNIQUE,

    module_permission VARCHAR(50) NOT NULL,
    action_permission VARCHAR(50) NOT NULL,

    description_permission TEXT,

    statut_permission ENUM('actif','inactif') DEFAULT 'actif',

    UNIQUE KEY uk_permission (
        module_permission,
        action_permission
    )
);
CREATE TABLE roles_permissions (
    id_role_permission INT AUTO_INCREMENT PRIMARY KEY,

    role_id INT NOT NULL,
    permissions_id INT NOT NULL,

    UNIQUE KEY uk_role_permission (
        role_id,
        permissions_id
    ),

    FOREIGN KEY (role_id)
        REFERENCES roles(id_role),

    FOREIGN KEY (permissions_id)
        REFERENCES permissions(id_permission)
);
-- =========================
-- TABLE CLIENTS (OPTIONNEL MAIS UTILE MVP)
-- =========================
CREATE TABLE clients (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    code_client VARCHAR(50) NOT NULL,

    nom_client VARCHAR(100),
    telephone_client VARCHAR(20) UNIQUE,
    email_client VARCHAR(100),

    created_at_client DATETIME,

    statut_client ENUM('actif','inactif') DEFAULT 'actif'
);

-- =========================
-- TABLE FOURNISSEURS
-- =========================
CREATE TABLE fournisseurs (
    id_fournisseur int AUTO_INCREMENT PRIMARY KEY,
    code_fournisseur VARCHAR(50) NOT NULL,

    nom_fournisseur VARCHAR(100),
    telephone_fournisseur VARCHAR(20),
    whatsapp_fournisseur VARCHAR(20),
    localisation_fournisseur VARCHAR(150),

    mode_collaboration_fournisseur ENUM('achat_direct','dropshipping','depot_vente','commission'),

    created_at_fournisseur DATETIME,
    statut_fournisseur ENUM('actif','inactif','suspendu') DEFAULT 'actif'
);

-- =========================
-- TABLE CATEGORIES
-- =========================
CREATE TABLE categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    code_categorie VARCHAR(50) NOT NULL,

    libelle_categorie VARCHAR(100),
    description_categorie TEXT,


    created_at_categorie DATETIME,
    statut_categorie ENUM('actif','inactif') DEFAULT 'actif'
);

CREATE TABLE sous_categories (
    id_sous_categorie INT AUTO_INCREMENT PRIMARY KEY,
    code_sous_categorie VARCHAR(50) NOT NULL,

    categorie_code VARCHAR(50),

    libelle_sous_categorie VARCHAR(100),
    description_sous_categorie TEXT,

    created_at_sous_categorie DATETIME ,
    statut_sous_categorie ENUM('actif','inactif') DEFAULT 'actif',


    FOREIGN KEY (categorie_code) REFERENCES categories(code_categorie)
);
-- =========================
-- TABLE PRODUITS
-- =========================
CREATE TABLE produits (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    code_produit VARCHAR(50) NOT NULL,

    fournisseur_code VARCHAR(50),
    categorie_code VARCHAR(50),

    libelle_produit VARCHAR(150),
    description_produit TEXT,
    image_produit VARCHAR(255),

    prix_fournisseur_produit DECIMAL(10,2),
    prix_vente_produit DECIMAL(10,2),

    stock_produit INT DEFAULT 0,
    statut_stock_produit ENUM('disponible','faible','rupture') DEFAULT 'disponible',
    statut_produit ENUM('actif','inactif') DEFAULT 'actif',

    created_at_produit DATETIME,

    FOREIGN KEY (fournisseur_code) REFERENCES fournisseurs(code_fournisseur),
    FOREIGN KEY (categorie_code) REFERENCES categories(code_categorie)
);

-- =========================
-- TABLE COMMANDES
-- =========================
CREATE TABLE commandes (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    code_commande VARCHAR(50),

    client_code VARCHAR(50),

    adresse_livraison_commande TEXT,

    montant_total_commande DECIMAL(10,2),

    statut_commande ENUM('en_attente','confirmee','en_livraison','livree','annulee') DEFAULT 'en_attente',

    methode_paiement_commande ENUM('cash','mobile_money','carte') DEFAULT 'cash',

    created_at_commande DATETIME,

    FOREIGN KEY (client_code) REFERENCES clients(code_client)
);

-- =========================
-- TABLE PANIER (OPTIONNEL MVP MAIS TRÈS UTILE)
-- =========================
CREATE TABLE panier (
    id_panier INT AUTO_INCREMENT PRIMARY KEY,
    code_panier VARCHAR(50) ,

    client_code VARCHAR(50),

    statut_panier ENUM('actif','converti','abandonne') DEFAULT 'actif',

    created_at_panier DATETIME,
    updated_at_panier DATETIME NULL,

    FOREIGN KEY (client_code) REFERENCES clients(code_client)
);

-- =========================
-- TABLE LIGNES PANIER
-- =========================
CREATE TABLE lignes_panier (
    id_ligne_panier INT AUTO_INCREMENT PRIMARY KEY,
    code_ligne_panier VARCHAR(50),

    panier_code VARCHAR(50),
    produit_code VARCHAR(50),

    quantite_produit INT,
    prix_unitaire_produit INT,

    FOREIGN KEY (panier_code) REFERENCES panier(code_panier),
    FOREIGN KEY (produit_code) REFERENCES produits(code_produit)
);

-- =========================
-- TABLE LIGNES COMMANDE
-- =========================
CREATE TABLE lignes_commande (
    id_ligne_commande INT AUTO_INCREMENT PRIMARY KEY,
    code_ligne_commande VARCHAR(50) ,

    commande_code VARCHAR(50),
    produit_code VARCHAR(50),

    quantite_produit INT,
    prix_unitaire_produit INT,

    FOREIGN KEY (commande_code) REFERENCES commandes(code_commande),
    FOREIGN KEY (produit_code) REFERENCES produits(code_produit)
);

-- =========================
-- TABLE COMMISSIONS / MARGES
-- =========================
CREATE TABLE commissions (
    id_commission INT AUTO_INCREMENT PRIMARY KEY,
    code_commission VARCHAR(50),

    commande_code VARCHAR(50),
    fournisseur_code VARCHAR(50),

    montant_commission INT,

    statut_commission ENUM('en_attente','payee','annulee') DEFAULT 'en_attente',

    created_at_commission DATETIME,

    FOREIGN KEY (commande_code) REFERENCES commandes(code_commande),
    FOREIGN KEY (fournisseur_code) REFERENCES fournisseurs(code_fournisseur)
);

CREATE TABLE parametres (
    id_parametre
    code_parametre VARCHAR(50) PRIMARY KEY,

    libelle_parametre VARCHAR(100),
    valeur_parametre TEXT,

    type_parametre ENUM('texte','nombre','boolean','json') DEFAULT 'texte',
    statut_parametre ENUM('actif','inactif'),

    created_at_parametre DATETIME
);
CREATE TABLE livreurs (
    code_livreur VARCHAR(50) PRIMARY KEY,

    nom_livreur VARCHAR(100),
    telephone_livreur VARCHAR(20),
    email_livreur VARCHAR(100),

    moyen_transport_livreur ENUM('moto','voiture','velo','tricycle'),

    created_at_livreur DATETIME,
    statut_livreur ENUM('actif','inactif','suspendu') DEFAULT 'actif'

);
CREATE TABLE livraisons (
    id_livraison INT AUTO_INCREMENT PRIMARY KEY,
    code_livraison VARCHAR(50),
    livreur_code VARCHAR(50),
    commande_code VARCHAR(50),

    frais_livraison DECIMAL(10,2) DEFAULT 0,

    created_at_attribution_livraison DATETIME NULL,
    created_at_depart_livraison DATETIME NULL,
    created_at_livraison DATETIME NULL,
    statut_livraison ENUM('en_attente','en_cours','livree','echec') DEFAULT 'en_attente',

    FOREIGN KEY (commande_code) REFERENCES commandes(code_commande),
    FOREIGN KEY (livreur_code) REFERENCES livreurs(code_livreur)
);

CREATE TABLE adresses_clients (
    id_adresse_client INT AUTO_INCREMENT PRIMARY KEY,
    code_adresse VARCHAR(50),

    client_code VARCHAR(50),

    adresse TEXT,
    ville VARCHAR(100),
    indications TEXT,

    est_principale BOOLEAN DEFAULT 0,

    FOREIGN KEY (client_code) REFERENCES clients(code_client)
);

CREATE TABLE paiements (
    id_paiement INT AUTO_INCREMENT PRIMARY KEY,
    code_paiement VARCHAR(50),

    commande_code VARCHAR(50),

    methode_paiement ENUM('cash','mobile_money','carte'),
    montant_paiement INT,

    statut_paiement ENUM('en_attente','partiel','paye','echoue') DEFAULT 'en_attente',

    reference_transaction VARCHAR(100),

    created_at_paiement DATETIME,

    FOREIGN KEY (commande_code) REFERENCES commandes(code_commande)
);
CREATE TABLE mouvements_stock (
    id_mouvement_stock INT AUTO_INCREMENT PRIMARY KEY,
    code_mouvement VARCHAR(50),

    produit_code VARCHAR(50),

    type_mouvement ENUM('entree','sortie'),

    quantite INT,

    source VARCHAR(100),

    created_at_mouvement DATETIME,

    FOREIGN KEY (produit_code) REFERENCES produits(code_produit)
);