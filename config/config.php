<?php
// Configuration globale de Learn Hub

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Constantes
define('BASE_URL', 'http://localhost:8080/learn-hub/public/');
define('APP_NAME', 'Learn Hub');
define('APP_VERSION', '1.0.0');

// Rôles
define('ROLE_APPRENANT', 'apprenant');
define('ROLE_FORMATEUR', 'formateur');
define('ROLE_ADMIN', 'admin');

// Chemins
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('MODELS_PATH', APP_PATH . '/models');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');

// Fuseau horaire
date_default_timezone_set('Africa/Douala');

// Affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);