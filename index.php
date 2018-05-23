<?php
// Define base path 
define('BASE_PATH', $_SERVER['SCRIPT_NAME']);
// Fetch autoload
require 'vendor/autoload.php';
// Starting session
if(session_id() == '') {
	session_start();
}
// Set header content type to utf-8
header('Content-Type: text/html; charset=utf-8');
// Initialize roots
$routes = new \Slim\Slim();

require 'config/routes.php';
// Start router
$routes->run();
?>
