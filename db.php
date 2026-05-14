<?php
// db.php - MySQLi database connection bootstrap.
// Include this file in other PHP files when database access is needed.

// Database credentials for local development.
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'ai_expense_tracker';

// Create a reusable MySQLi connection instance.
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// Stop early if the connection cannot be established.
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Optional temporary success message for testing.
// echo 'Database connected successfully';
