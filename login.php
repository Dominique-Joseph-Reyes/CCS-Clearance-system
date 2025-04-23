<?php
session_start();
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'admin') {
            header('Location: admin_dashboard.php');
        } elseif ($user['role'] == 'chair') {
            header('Location: chair_dashboard.php');
        } else {
            header('Location: faculty_dashboard.php');
        }
        exit(); // Stop the script here after redirect
    } else {
        echo "<p style='color: red; text-align: center;'>Invalid credentials.</p>";
    }    
}    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS Clearance Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <body class="login-background">
    <div class="login-container">
        <div class="login-box">
        <img src="CCS BG.jpg" alt="Logo" class="logo"> <!-- Logo image -->
            <h1>CCS Clearance Management System</h1>
            <h3>Sign in to continue</h3>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
