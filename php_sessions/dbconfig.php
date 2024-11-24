<?php
// Database Configuration

// Define constants for database connection
define('DB_HOST', 'localhost'); // Database host (e.g., '127.0.0.1' or 'localhost')
define('DB_USER', 'root');      // Database username
define('DB_PASS', '');  // Database password
define('DB_NAME', 'test'); // Database name

// Establish connection to the database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Optional: Set the character set to UTF-8
$connection->set_charset("utf8");
?>
