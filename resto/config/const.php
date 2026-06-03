<?php require_once 'config.php'; ?>

<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('ROOT_REMOVE_IMG', $_SERVER['DOCUMENT_ROOT']."/resto");
define('RACINE', 'http://localhost/resto/');
define('RACINE_EXTERNE', 'http://localhost/restaurant/');
// define('RACINE', 'https://resto.kassanngroup.com/');
// define('RACINE_EXTERNE', 'https://restaurant.kassanngroup.com/');
define('RACINE_2', '/resto/public/');

define('USER_NAME',  $_SESSION['client']['nom'] ?? null);
define('USER_EMAIL', $_SESSION['client']['email'] ?? null);
define('USER_ID', $_SESSION['client']['id_client'] ?? null);
define('CLIENT_CODE', $_SESSION['client']['code'] ?? null);

define('SIGN', $_SESSION['client']['sign'] ?? null);

define('LOGO_WAVE', '<img src="' .RACINE. 'assets/logo/wave.jpg" class="img-circle" alt="Logo" height="100" width="100">');

define('LOGO', '<img src="' .RACINE. 'assets/logo/logo_eeri.jpg" class="img-circle" alt="Logo" height="100" width="80" style="border-radius: 70%; object-fit: covers;">');

define('DOWNLOAD', RACINE . 'app-assets/downloads/Intranet_membres_1_1.0.apk');

define('ICON', '<img src="'.RACINE.'assets/logo/logo_eeri.jpg" alt="LOGO" style="position: relative; bottom:12px; border-radius: 70%; object-fit: covers;" width="50" height="50";>');

define('USER_ICON', '<p class="text-center"  ><i class="fa fa-user-circle fa-lg"></i></p>');

define('IMG_ICON', RACINE.'assets/img/logo/e-logo.png');

define('TITLE_ICON', 'EERI-CI, Race Bénie');

define('MON_TOKEN_TELEGRAM_BOT', "8300635660:AAG6lstbEKFU00dRfsnwALfhWkBTBkZQf2I");

define('MON_CHAT_ID_TELEGRAM_BOT', "7152843515");