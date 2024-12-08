<?php
// Start session
session_start();

// Database connection function
function getDatabaseConnection()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "nstu_academia";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Check if user is logged in
function checkSession()
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
        header("Location: login.php");
        exit();
    }
}
?>
