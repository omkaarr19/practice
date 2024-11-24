<?php
session_start();
// require_once 'db_config.php'; // Include your database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Use your MySQL username
define('DB_PASS', ''); // Use your MySQL password
define('DB_NAME', 'test'); // Your database name
// Set session timeout (5 minutes)
$session_timeout = 5 * 60; // 5 minutes in seconds
$user_id = $_SESSION['user_id']; // Assuming the user is logged in and user_id is stored in session

// Check if the user is logged in
if (!isset($user_id)) {
    die("You must be logged in to access this page.");
}

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Clean up old sessions (those that expired 5 minutes ago)
$cleanup_query = "DELETE FROM user_sessions WHERE last_activity < NOW() - INTERVAL 5 MINUTE";
$conn->query($cleanup_query);

// Check the number of concurrent sessions
$session_check_query = "SELECT COUNT(*) FROM user_sessions WHERE user_id = ?";
$stmt = $conn->prepare($session_check_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($session_count);
$stmt->fetch();

// If the user has 3 or more concurrent sessions, deny access
if ($session_count >= 3) {
    die("You have reached the maximum number of concurrent sessions (3). Please log out from other sessions first.");
}

// Create a new session if within the limit
$session_id = session_id();
$insert_session_query = "INSERT INTO user_sessions (session_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($insert_session_query);
$stmt->bind_param("si", $session_id, $user_id);
$stmt->execute();

// Update the session's last activity timestamp
$update_activity_query = "UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?";
$stmt = $conn->prepare($update_activity_query);
$stmt->bind_param("s", $session_id);
$stmt->execute();

// Set session expiration time (5 minutes)
ini_set('session.gc_maxlifetime', $session_timeout);
session_set_cookie_params($session_timeout);

// Your page content goes here
echo "Welcome! You are successfully logged in.";

// Close the database connection
$stmt->close();
$conn->close();
?>
