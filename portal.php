<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Establish Connection (Centralized in db_config is better, but here works too)
$conn = new mysqli("localhost", "root", "", "secure_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Analytics Logic
$student_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$inventory_count = $conn->query("SELECT COUNT(*) as total FROM inventory")->fetch_assoc()['total'];
$laundry_count = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;
$inventory_alert = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE quantity <= min_stock")->fetch_assoc()['total'] ?? 0;

// Set some targets/capacities
$student_capacity = 100;
$student_percentage = ($student_count / $student_capacity) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workplace Hub | LordS7ark</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f111a; color: white; margin: 0; padding-bottom: 50px; }
        .container { max-width: 1100px; margin: auto; padding: 20px; }
        
        /* Navbar Styling */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid #2d3142; margin-bottom: 40px; }
        
        /* Status Card Styling */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 50px; }
        .stat-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        
        /* App Grid Styling */
        .hub-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .app-card { position: relative; background: #1a1d2b; border: 1px solid #2d3142; padding: 40px; border-radius: 20px; text-align: center; transition: 0.3s; cursor: pointer; text-decoration: none; color: white; display: block; }
        .app-card:hover { border-color: #a855f7; transform: translateY(-10px); background: #24283b; box-shadow: 0 10px 30px rgba(168, 85, 247, 0.2); }
        
        .icon { font-size: 3rem; margin-bottom: 15px; display: block; }
        
        /* Badges */
        .badge { position: absolute; top: 15px; right: 15px; background: #ff4d4d; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 0.8rem; font-weight: bold; border: 2px solid #1a1d2b; }
        .badge-warning { background: #ffb800; color: black; }
    </style>
</head>
<body>

<div class="container">
    
    <nav class="navbar">
        <div style="font-size: 1.5rem; font-weight: bold; color: #a855f7;">Workplace<span style="color:white">Hub</span></div>
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="profile.php" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                <div style="width: 40px; height: 40px; background: #a855f7; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold;">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <span>Welcome, <strong><?php echo $_SESSION['username']; ?></strong></span>
            </a>
            <a href="logout.php" style="background: #ff4d4d; color: white; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: bold;">Logout</a>
        </div>
    </nav>

    <div class="stats-grid">
        <div class="stat-card" style="border-left: 5px solid #a855f7;">
            <h4 style="margin: 0; color: #888; font-size: 0.75rem; text-transform: uppercase;">Enrollment Capacity</h4>
            <div style="font-size: 1.8rem; font-weight: bold; color: #1a1d2b; margin: 10px 0;">
                <?php echo $student_count; ?> <span style="font-size: 1rem; color: #ccc;">/ <?php echo $student_capacity; ?></span>
            </div>
            <div style="width: 100%; height: 8px; background: #eee; border-radius: 10px; overflow: hidden;">
                <div style="width: <?php echo $student_percentage; ?>%; height: 100%; background: #a855f7;"></div>
            </div>
            <p style="font-size: 0.75rem; color: #a855f7; margin-top: 8px; font-weight: bold;">
                <?php echo round($student_percentage); ?>% of total capacity used
            </p>
        </div>

        <div class="stat-card" style="border-left: 5px solid #00d4ff;">
            <h4 style="margin: 0; color: #888; font-size: 0.75rem; text-transform: uppercase;">Stock Items Tracking</h4>
            <div style="font-size: 1.8rem; font-weight: bold; color: #1a1d2b; margin: 10px 0;">
                <?php echo $inventory_count; ?>
            </div>
            <div style="display: flex; align-items: center; gap: 5px; color: #00d4ff; font-size: 0.8rem; font-weight: bold;">
                <span>📦</span> Database Connected
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 25px; color: #a855f7;">Available Modules</h3>
    <div class="hub-container">
        
        <a href="../grading_system/index.php" class="app-card">
            <span class="icon">🎓</span>
            <h3>GradePoint Pro</h3>
            <p style="color: #888; font-size: 0.9rem;">Manage student records and academic grades.</p>
            <div style="margin-top: 15px; font-size: 0.8rem; color: #a855f7;"><?php echo $student_count; ?> Students Active</div>
        </a>

        <a href="../laundry/index.php" class="app-card">
            <?php if($laundry_count > 0): ?>
                <div class="badge badge-warning"><?php echo $laundry_count; ?></div>
            <?php endif; ?>
            <span class="icon">🧺</span>
            <h3>LaunderTrack</h3>
            <p style="color: #888; font-size: 0.9rem;">Monitor student laundry and collection status.</p>
            <div style="margin-top: 15px; font-size: 0.8rem; color: #ffb800;"><?php echo $laundry_count; ?> Pending Orders</div>
        </a>

       <div class="app-card" style="position: relative;">
        <?php if($inventory_alert > 0): ?>
            <a href="/inventory/inventory_system/index.php" 
               class="badge" 
               style="z-index: 10; cursor: pointer; text-decoration: none;">
                <?php echo $inventory_alert; ?>
            </a>
        <?php endif; ?>

        <a href="/inventory/index.php" style="text-decoration: none; color: inherit;">
            <span class="icon">📦</span>
            <h3>StockMaster</h3>
            <p style="color: #888; font-size: 0.9rem;">Track equipment and resource stock levels.</p>
            
            <?php if($inventory_alert > 0): ?>
                <div style="margin-top: 15px; font-size: 0.8rem; color: #ff4d4d; font-weight: bold;">
                    ⚠️ <?php echo $inventory_alert; ?> Items Need Restock
                </div>
            <?php else: ?>
                <div style="margin-top: 15px; font-size: 0.8rem; color: #00ff88;">
                    ✓ Stock Levels Healthy
                </div>
            <?php endif; ?>
        </a>
</div>
    </div>

    <div style="margin-top: 60px; padding: 25px; background: rgba(0, 255, 65, 0.03); border: 1px dashed #00ff41; border-radius: 15px; font-family: 'Courier New', monospace;">
        <h4 style="color: #00ff41; margin-top: 0;">> SECURITY_OVERWATCH_ACTIVE</h4>
        <p style="color: #aaa; font-size: 0.85rem;">Authorized Access: <?php echo $_SESSION['username']; ?> | Session ID: <?php echo session_id(); ?></p>
        <a href="activity_log.php" style="color: #00ff41; text-decoration: none; font-weight: bold; border-bottom: 1px solid #00ff41;">[ACCESS_SYSTEM_LOGS]</a>
    </div>

</div>

</body>
</html>