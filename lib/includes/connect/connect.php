<?php
define('EMAIL_HOST', 'your_email_host');
define('EMAIL_USERNAME', 'your_email_username');
define('EMAIL_PASSWORD', 'your_email_password');
define('EMAIL_ADDRESS', 'email_address');
define('EMAIL_PORT', 587);
if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    define('DATABASE_HOST', 'local_host');
    define('DATABASE_NAME', 'mysimpleblog');
    define('DATABASE_USERNAME', 'database_username');
    define('DATABASE_PASSWORD', 'database_password');
    define('DATABASE_DB', 'mysimpleblog');
} else {
    /* REMOTE SERVER CONSTANTS */
    define('DATABASE_HOST', 'remote_database_host');
    define('DATABASE_NAME', 'remote_database_name');
    define('DATABASE_USERNAME', 'remote_database_username');
    define('DATABASE_PASSWORD', 'remote_database_password');
 }
