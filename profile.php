<?php
require_once('db_config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user details
$user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_query->fetch_assoc();

// Fetch ONLY this user's last 5 activities
$my_logs = $conn->query("SELECT * FROM activity_log WHERE username = '$username' ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile | SecurePass</title>
    <style>
        body {
            background: linear-gradient(45deg, #0f111a, #1a1d2b);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }

        /* Glassmorphism Card */
        .profile-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            width: 450px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            text-align: center;
        }

        .avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #a855f7, #6f42c1);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.4);
        }

        .role-badge {
            background: #a855f7;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .history-section {
            margin-top: 30px;
            text-align: left;
            background: rgba(0,0,0,0.2);
            padding: 15px;
            border-radius: 10px;
        }

        .log-item {
            font-size: 0.85rem;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: #ccc;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: #a855f7;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="profile-card">
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div style="background: rgba(0, 255, 65, 0.1); border: 1px solid #00ff41; color: #00ff41; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
        ✅ Password updated successfully!
    </div>
<?php endif; ?>
    <div class="avatar">
        <?php echo strtoupper(substr($username, 0, 1)); ?>
    </div>
    
    <h2 style="margin: 10px 0;"><?php echo ucfirst($username); ?></h2>
    <span class="role-badge"><?php echo $user_data['role']; ?></span>
    
    <div class="history-section">
        <h4 style="margin: 0 0 10px 0; color: #a855f7;">Recent Activity</h4>
        <?php while($log = $my_logs->fetch_assoc()): ?>
            <div class="log-item">
                <span style="color: #888;"><?php echo date('H:i', strtotime($log['created_at'])); ?></span> 
                - <?php echo $log['action']; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="portal.php" class="btn-back">← Return to Hub</a>

    <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
    <h4 style="color: #00d4ff; margin-bottom: 15px;">Update Security</h4>
    <form action="update_password.php" method="POST">
        <input type="password" name="new_password" placeholder="New Password" 
               style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); padding: 10px; border-radius: 8px; color: white; margin-bottom: 10px;">
        <button type="submit" 
                style="width: 100%; background: #a855f7; border: none; padding: 10px; border-radius: 8px; color: white; font-weight: bold; cursor: pointer;">
            Update Password
        </button>
    </form>
</div>
</div>

</body>
</html>