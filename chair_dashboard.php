<?php
session_start();
if ($_SESSION['role'] != 'chair') {
    header('Location: login.php');
    exit();
}
include 'db.php';

// Fetch tasks assigned to the chair's faculty
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
    <title>Chair Dashboard - CCS Clearance Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container chair-dashboard">
        <div class="dashboard-header">
            <h1>Chair Dashboard</h1>
            <a href="assign_task.php" class="assign-task-btn">Assign Task</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="section">
            <h3>Assigned Tasks</h3>
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
