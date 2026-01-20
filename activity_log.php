<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "secure_db");

$logs = $conn->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 50");
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Logs | OS Style</title>
    <style>
        body { background: #050505; color: #00ff41; font-family: 'Courier New', monospace; padding: 40px; }
        .log-container { max-width: 900px; margin: 0 auto; background: #0a0a0a; border: 1px solid #333; border-radius: 8px; padding: 20px; box-shadow: 0 0 20px rgba(0, 255, 65, 0.1); }
        .log-entry { border-bottom: 1px solid #1a1a1a; padding: 10px 0; display: flex; font-size: 0.9rem; }
        .timestamp { color: #888; width: 180px; }
        .user { color: #00d4ff; width: 120px; font-weight: bold; }
        .action { color: #eee; flex: 1; }
        .module-tag { background: #333; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; margin-left: 10px; color: #fff; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #00ff41; padding-bottom: 10px; margin-bottom: 20px; }
    
    /* Add this to your activity_log.php <style> */
.log-entry:first-child {
    border-left: 3px solid #00ff41;
    background: rgba(0, 255, 65, 0.05);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0px rgba(0, 255, 65, 0.2); }
    50% { box-shadow: 0 0 10px rgba(0, 255, 65, 0.4); }
    100% { box-shadow: 0 0 0px rgba(0, 255, 65, 0.2); }
}
    </style>
</head>
<body>

<div class="log-container">
    <div class="header">
        <span>[SYSTEM_LOG_ACCESS_GRANTED]</span>
        <a href="portal.php" style="color: #00ff41; text-decoration: none;">[EXIT]</a>
    </div>

    <?php while($row = $logs->fetch_assoc()): ?>
        <div class="log-entry">
            <span class="timestamp"><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></span>
            <span class="user">@<?php echo $row['username']; ?></span>
            <span class="action">
                <?php echo $row['action']; ?>
                <span class="module-tag"><?php echo $row['module']; ?></span>
            </span>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>