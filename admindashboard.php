<?php
session_start();
// Include the database connection
$servername = "localhost";
$username = "root"; // Update with your database username
$password = "sultan1369"; // Update with your database password
$dbname = "job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
    <style>
        /* Add some styles for the admin details */
        .admin-details {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
            color: #333;
            /* Change as needed */
        }
    </style>
</head>

<body>
    <h1>Admin Dashboard</h1>

    <div class="admin-details">
        <?php if (isset($_SESSION['username']) && isset($_SESSION['email'])): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <?php else: ?>
            <p>You are not logged in.</p>
        <?php endif; ?>
    </div>

    <nav>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_resumes.php">Manage Resumes</a></li>
            <li><a href="manage_jobs.php">Manage Jobs</a></li>
            <li><a href="manage_applications.php">Manage Applications</a></li>
            <li><a href="send_notification.php">Notifcations</a></li>
            <li><a href="manage_notifications.php">Manage Notifcations</a></li>
            <li><a href="adminlogout.php">Logout</a></li>
        </ul>
    </nav>
</body>

</html>