<?php
session_start();
include 'db.php';

// Check if user is logged in and is an Admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // No hashing, store plain text password
    $role = $_POST['role'];

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "Email is already registered.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $password, $role]);
        $success = "User added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - CCS Clearance Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add New User</h1>
        <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <form method="POST" action="add_user.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter user name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter user email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter password" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="chair">Chair</option>
                    <option value="faculty">Faculty</option>
                </select>
            </div>

            <button type="submit" class="login-btn">Add User</button>
        </form>
    </div>
</body>
</html>
