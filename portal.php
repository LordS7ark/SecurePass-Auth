<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Establish Connection (THIS MUST COME FIRST)
$conn = new mysqli("localhost", "root", "", "secure_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Now you can run the queries
$student_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$laundry_count = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'")->fetch_assoc()['total'];
$inventory_alert = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE quantity <= min_stock")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workplace Hub | LordS7ark</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f111a; color: white; margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; }
        .hub-container { display: flex; gap: 30px; margin-top: 40px; }
        .app-card { background: #1a1d2b; border: 1px solid #2d3142; padding: 40px; border-radius: 15px; text-align: center; width: 250px; transition: 0.3s; cursor: pointer; text-decoration: none; color: white; }
        .app-card:hover { border-color: #a855f7; transform: translateY(-10px); background: #24283b; }
        .icon { font-size: 3rem; margin-bottom: 20px; display: block; }
        .btn-logout { margin-top: 50px; color: #ff4d4d; text-decoration: none; font-weight: bold; border: 1px solid #ff4d4d; padding: 10px 20px; border-radius: 5px; }
    /* Container for the card needs to be relative so the bubble stays inside it */
.app-card {
    position: relative;
    padding: 30px;
    /* ... your existing card styles ... */
}

.badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #ff4d4d; /* Red for alerts */
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 0.8rem;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(255, 77, 77, 0.5);
    border: 2px solid #0f111a; /* Matches background to make it 'pop' */
}

.badge-warning { background: #ffb800; color: black; } /* Orange for pending */
    
    </style>
</head>
<body>

    <h1>Welcome back, <?php echo $_SESSION['username']; ?>!</h1>
    <p style="color: #888;">Select an application to manage</p>

    <<div class="hub-container">
    <a href="../grading_system/index.php" class="app-card">
        <span class="icon">🎓</span>
        <h3>GradePoint Pro</h3>
        <p><?php echo $student_count; ?> Students</p>
    </a>

    <a href="../laundry_system/index.php" class="app-card">
        <?php if($laundry_count > 0): ?>
            <div class="badge badge-warning"><?php echo $laundry_count; ?></div>
        <?php endif; ?>
        <span class="icon">🧺</span>
        <h3>Laundry Master</h3>
        <p>Manage Orders</p>
    </a>

    <a href="../inventory/index.php" class="app-card">
        <?php if($inventory_alert > 0): ?>
            <div class="badge"><?php echo $inventory_alert; ?></div>
        <?php endif; ?>
        <span class="icon">📦</span>
        <h3>Stock Control</h3>
        <p>Inventory List</p>
    </a>
</div>

    <a href="logout.php" class="btn-logout">Sign Out</a>

    <div style="margin-top: 50px; padding: 20px; background: rgba(0, 255, 65, 0.05); border: 1px dashed #00ff41; border-radius: 10px; font-family: 'Courier New', monospace;">
    <h4 style="color: #00ff41; margin-top: 0;">> SYSTEM_MONITOR_ACTIVE</h4>
    <p style="color: #888; font-size: 0.8rem;">Security logs are being recorded for user: <?php echo $_SESSION['username']; ?></p>
    <a href="activity_log.php" style="color: #00ff41; text-decoration: none; font-weight: bold;">[VIEW_SYSTEM_LOGS]</a>
</div>
</body>
</html>