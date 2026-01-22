<?php
session_start();
// Use require_once to prevent the "Cannot redeclare" error
require_once('db_config.php'); 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 1. Fetch the user from the database
    $result = $conn->query("SELECT * FROM users WHERE username = '$user'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 2. Verify the hashed password
        if (password_verify($pass, $row['password'])) {
            // 3. Password is correct! Start the session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // LOG THE ACTIVITY HERE (Inside the success block)
            logActivity($conn, $_SESSION['username'], "User logged into the system", "AUTH");

            header("Location: portal.php");
            exit();
        } else {
            $error = "Invalid password!";
            // OPTIONAL: Log failed attempts too
            // logActivity($conn, $user, "Failed login attempt (Wrong Password)", "AUTH");
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SecurePass | Login</title>
    <style>
        body { font-family: sans-serif; background: #0f111a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #1a1d2b; padding: 40px; border-radius: 12px; border: 1px solid #2d3142; width: 350px; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #0f111a; border: 1px solid #2d3142; color: white; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #a855f7; border: none; color: white; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .error { color: #ff4d4d; font-size: 14px; }
        .login-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px); /* Frosted glass effect */
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}

input {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-bottom: 2px solid #00d4ff; /* Neon underline */
    color: white;
    padding: 10px;
}
    </style>
</head>
<body>
    <div class="box">
        <h2>Login</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <p style="font-size: 13px; margin-top: 15px;">No account? <a href="register.php" style="color: #a855f7;">Register</a></p>
    </div>
</body>
</html>