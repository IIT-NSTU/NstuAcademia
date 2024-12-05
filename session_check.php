<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || !isset($_SESSION['name'])) {
    // Redirect to login if session variables are missing
    header("Location: login.php");
    exit();
}
?>
