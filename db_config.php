<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "secure_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// THIS WRAPPER PREVENTS THE "CANNOT REDECLARE" ERROR
if (!function_exists('logActivity')) {
    function logActivity($conn, $username, $action, $module) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $conn->prepare("INSERT INTO activity_log (username, action, module, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $action, $module, $ip);
        $stmt->execute();
        $stmt->close();
    }
}
?>