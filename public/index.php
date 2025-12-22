<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get URI path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Project folder name
$basePath = '/trainer_profile';
$uri = str_replace($basePath, '', $uri);
$uri = trim($uri, '/');

switch ($uri) {

    // FRONTEND
    case '':
    case 'form':
        require 'form.php';
        break;

    case 'success.php':
        require 'success.php';
        break;

    // ADMIN
    case 'admin/login':
        require '../admin/admin_login.php';
        break;

    case 'admin/dashboard':
        require '../admin/admin_dashboard.php';
        break;

    case 'admin/admin_dashboard.php':
        require '../admin/admin_dashboard.php';
        break;

    case 'admin/logout':
        require '../admin/admin_logout.php';
        break;

    case 'admin/admin_logout.php':
        require '../admin/admin_logout.php';
        break;

    // API
    case 'api/save-trainer':
        require '../api/save_trainer.php';
        break;

    case 'api/fetch-profiles':
        require '../api/fetch_profiles.php';
        break;

    case 'api/search-trainers':
        require '../api/search_trainers.php';
        break;

    case 'api/search_trainers.php':
        require '../api/search_trainers.php';
        break;

    case 'api/submit':
        require '../api/submit.php';
        break;

    // 404
    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
