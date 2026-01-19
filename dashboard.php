<?php
session_start();

// If the user is NOT logged in, redirect them to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Dashboard</title>
    <style>
        body { font-family: sans-serif; background: #0f111a; color: white; padding: 50px; }
        .welcome-card { background: #1a1d2b; padding: 30px; border-radius: 15px; border: 1px solid #a855f7; max-width: 600px; }
        .logout-btn { color: #ff4d4d; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="welcome-card">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>! 👋</h1>
        <p>You have successfully logged into the secure area.</p>
        <p>Your Account Role: <strong><?php echo strtoupper($_SESSION['role']); ?></strong></p>
        <hr style="border: 0; border-top: 1px solid #2d3142; margin: 20px 0;">
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>

    <div class="user-content" style="margin-top: 20px; padding: 15px; background: #2d3142; border-radius: 10px;">
    <h3>Public User Area</h3>
    <p>Every logged-in user can see this part.</p>
</div>

<?php if ($_SESSION['role'] == 'admin'): ?>
    <div class="admin-only-zone" style="margin-top: 20px; padding: 15px; background: rgba(168, 85, 247, 0.2); border: 1px dashed #a855f7; border-radius: 10px;">
        <h3 style="color: #a855f7;">🔒 Admin Control Panel</h3>
        <p>This is secret data. You can see this because your role is <strong>ADMIN</strong>.</p>
        <button style="background: #ff4d4d; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">
            Delete All Database Records (Careful!)
        </button>
    </div>
<?php endif; ?>

</body>
</html>