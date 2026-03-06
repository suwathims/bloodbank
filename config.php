<?php
// Start session early so included pages can use sessions
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Create a mysqli connection and expose it as $conn
$conn = mysqli_connect("localhost", "root", "", "bloodbank");
if (!$conn) {
	// Use a descriptive error for debugging in development
	die("Database connection failed: " . mysqli_connect_error());
}

?>