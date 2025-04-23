<?php 
session_start();
if ($_SESSION['role'] != 'faculty') {
    header('Location: login.php');
    exit();
}
include 'db.php'; // Include the database connection

// Fetch tasks assigned to the faculty
$tasks = $pdo->query("SELECT * FROM tasks WHERE assigned_faculty = '{$_SESSION['user_id']}'")->fetchAll();

$google_drive_link = 'https://drive.google.com/your_shared_link'; // Your pre-defined Google Drive link

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_file'])) {
    // Get the task id
    $task_id = $_POST['task_id'];

    // Update the task to 'completed' when the file is submitted
    $sql = "UPDATE tasks SET status = 'completed' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$task_id]);

    // Redirect to the same page to reflect changes
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - CCS Clearance Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container faculty-dashboard">
        <div class="dashboard-header">
            <h1>Faculty Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="section">
            <h3>Task Tracker</h3>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td class="<?php echo $task['status'] == 'completed' ? 'completed' : 'pending'; ?>">
                                <?php echo ucfirst($task['status']); ?>
                            </td>
                            <td>
                                <!-- Display link to Google Drive submission if task is not completed -->
                                <?php if ($task['status'] != 'completed'): ?>
                                    <form method="POST" action="faculty_dashboard.php">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <label for="file_url">Please submit your task:</label>
                                        <a href="<?php echo $google_drive_link= 'https://drive.google.com/drive/folders/1QIQH02cpIOYdI1MWylxLYJqwTBBu1F7F?usp=sharing'; ?>" target="_blank" class="submit-btn">Go to Google Drive</a>
                                        <button type="submit" name="submit_file" class="submit-btn">Task Submitted</button>
                                    </form>
                                <?php else: ?>
                                    <!-- Provide a link to the submitted file -->
                                    <span>Task completed</span>
                                    <a href="<?php echo $google_drive_link= 'https://drive.google.com/drive/folders/1QIQH02cpIOYdI1MWylxLYJqwTBBu1F7F?usp=sharing'; ?>" target="_blank" class="submit-btn">Go to Google Drive</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
