<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'chair') {
    header('Location: login.php');
    exit();
}

// Fetch all faculty members
$faculty = $pdo->query("SELECT * FROM users WHERE role = 'faculty'")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $task_name = $_POST['task_name'];
    $faculty_id = $_POST['faculty'];
    
    // Insert task into tasks table
    $sql = "INSERT INTO tasks (task_name, assigned_faculty, status) VALUES (?, ?, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$task_name, $faculty_id]);

    // Redirect to the same page or admin dashboard
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task - Chair Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container chair-dashboard">
        <div class="dashboard-header">
            <h1>Assign Task</h1>
            <a href="chair_dashboard.php" class="assign-task-btn">Back to Dashboard</a>
        </div>

        <div class="section">
            <h3>Assign a New Task</h3>
            <form method="POST" action="assign_task.php">
                <div class="form-group">
                    <label for="task_name">Task Name</label>
                    <input type="text" name="task_name" id="task_name" placeholder="Enter task name" required>
                </div>

                <div class="form-group">
                    <label for="faculty">Assign to Faculty</label>
                    <select name="faculty" id="faculty" required>
                        <option value="">Select Faculty</option>
                        <?php foreach ($faculty as $member): ?>
                            <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="assign-task-btn">Assign Task</button>
            </form>
        </div>
    </div>
</body>
</html>
