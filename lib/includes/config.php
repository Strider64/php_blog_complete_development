<?php
/* Turn on error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    error_reporting(-1); // -1 = on || 0 = off
} else {
    error_reporting(0); // -1 = on || 0 = off
    if (htmlspecialchars($_SERVER["HTTPS"]) != "on") {
        header("Location: https://" . htmlspecialchars($_SERVER["HTTP_HOST"]) . htmlspecialchars($_SERVER["REQUEST_URI"]));
        exit();
    }
}
include 'connect/connect.php';
$seconds = 60;
$minutes = 60;
$hours = 24;
$days = 14;
session_set_cookie_params($seconds * $minutes * $hours * $days, "");
session_start();
date_default_timezone_set("America/Detroit"); // Set Default Timezone:
/* Autoloads classes using namespaces                       */
require_once "lib/website_project/website_project.inc.php";
use website_project\database\Database as DB;
/*
 * Create a constant PDO connection.
 */
$db = DB::getInstance();
$pdo = $db->getConnection();
