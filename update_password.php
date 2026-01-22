<?php
require_once('db_config.php');
session_start();

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $new_pass = $_POST['new_password'];

    // 1. Basic Validation
    if (strlen($new_pass) < 6) {
        die("Password must be at least 6 characters long.");
    }

    // 2. Hash the password for security
    $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

    // 3. Update the Database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);

    if ($stmt->execute()) {
        // 4. LOG THE ACTIVITY (The Snitch System)
        logActivity($conn, $username, "Changed their account password", "SECURITY");

        // 5. Redirect back with success message
        header("Location: profile.php?status=success");
        exit();
    } else {
        echo "Error updating password: " . $conn->error;
    }
}
?>