<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'db.php'; // Include the database connection

// Fetch all users
$users = $pdo->query("SELECT * FROM users")->fetchAll();

// Fetch all tasks
$tasks = $pdo->query("SELECT * FROM tasks")->fetchAll();

$tasks = $pdo->query("SELECT t.task_name, u.name as faculty_name, t.status 
                      FROM tasks t
                      JOIN users u ON t.assigned_faculty = u.id
                      WHERE u.role = 'faculty'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CCS Clearance Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <a href="add_user.php" class="add-user-btn">Add User</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="section">
            <h3>Users List</h3>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Name: </th>
                        <th>Email: </th>
                        <th>Role: </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Task Tracker: </h3>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Assigned Faculty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td><?php echo htmlspecialchars($task['faculty_name']); ?></td>
                            <td class="<?php echo $task['status'] == 'completed' ? 'completed' : 'pending'; ?>">
                                <?php echo ucfirst($task['status']); ?>
                                <a href="<?php echo $google_drive_link= 'https://drive.google.com/drive/folders/1QIQH02cpIOYdI1MWylxLYJqwTBBu1F7F?usp=sharing'; ?>" target="_blank" class="submit-btn">Go to Google Drive</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
