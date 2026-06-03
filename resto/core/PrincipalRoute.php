<?php

require_once '../config/Database.php';

require_once '../models/Validator.php';

require_once '../core/Router.php';
require_once '../core/CartSession.php';

require_once '../models/home/ModelHome.php';

require_once '../models/clients/ModelClients.php';

require_once '../models/menu/ModelPlats.php';
require_once '../models/commandes/ModelCommandes.php';
require_once '../models/settings/ModelSettings.php';
require_once '../models/settings/ModelPromotions.php';

require_once '../controllers/home/HomeController.php';

require_once '../controllers/about/AboutController.php';

require_once '../controllers/menu/MenuController.php';

require_once '../controllers/clients/ClientsController.php';
require_once '../controllers/cart/CartController.php';

require_once '../controllers/commandes/CommandesController.php';

require_once '../controllers/contact/ContactController.php';
