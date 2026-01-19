<?php
session_start();

// Gatekeeper 1: Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Gatekeeper 2: Must be an ADMIN
if ($_SESSION['role'] != 'admin') {
    die("<h1 style='color:red;'>ACCESS DENIED: You are not an admin!</h1>");
}

echo "<h1>Welcome to the Top Secret Admin Page</h1>";
?>