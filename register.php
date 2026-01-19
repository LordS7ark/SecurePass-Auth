<?php
include('db_config.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // 1. Securely Hash the password
    $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

    // 2. Insert into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$hashed_password')";

    if ($conn->query($sql)) {
        $message = "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SecurePass | Register</title>
    <style>
        body { font-family: sans-serif; background: #0f111a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #1a1d2b; padding: 40px; border-radius: 12px; border: 1px solid #2d3142; width: 350px; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #0f111a; border: 1px solid #2d3142; color: white; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #a855f7; border: none; color: white; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Create Account</h2>
        <?php echo $message; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>