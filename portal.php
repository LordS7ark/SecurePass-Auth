<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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
    </style>
</head>
<body>

    <h1>Welcome back, <?php echo $_SESSION['username']; ?>!</h1>
    <p style="color: #888;">Select an application to manage</p>

    <div class="hub-container">
        <a href="../grading_system/index.php" class="app-card">
            <span class="icon">🎓</span>
            <h3>GradePoint Pro</h3>
            <p style="font-size: 0.8rem; color: #888;">Manage students, input scores, and generate reports.</p>
        </a>

        <a href="../laundry/index.php" class="app-card">
            <span class="icon">🧺</span>
            <h3>Laundry Master</h3>
            <p style="font-size: 0.8rem; color: #888;">Track laundry orders, status, and customer billing.</p>
        </a>

        <a href="../inventory/index.php" class="app-card">
    <span class="icon">📦</span>
    <h3>Inventory System</h3>
</a>
    </div>

    <a href="logout.php" class="btn-logout">Sign Out</a>

</body>
</html>