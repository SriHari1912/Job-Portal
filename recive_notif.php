<?php
session_start();
include('includes/db.php'); // Database connection

// Ensure the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "You must be logged in to view notifications.";
    exit;
}

// Pagination logic
$limit = 10; // Number of notifications per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Fetch notifications for the current user and global notifications (user_id IS NULL)
$notifications = [];
$stmt = $conn->prepare("
    SELECT subject, message, created_at 
    FROM notifications 
    WHERE user_id = ? OR user_id IS NULL
    ORDER BY created_at DESC
    LIMIT ?, ?
");

if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param("iii", $user_id, $offset, $limit);  // Bind user_id and pagination parameters

if (!$stmt->execute()) {
    die("Execution failed: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
$stmt->close();

// Count total notifications for pagination
$count_stmt = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM notifications 
    WHERE user_id = ? OR user_id IS NULL
");

if (!$count_stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$count_stmt->bind_param("i", $user_id); // Bind user_id
if (!$count_stmt->execute()) {
    die("Execution failed: " . htmlspecialchars($count_stmt->error));
}

$count_result = $count_stmt->get_result();
$total_notifications = $count_result->fetch_assoc()['total'];
$count_stmt->close();

// Calculate total pages
$total_pages = ceil($total_notifications / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Your Notifications</title>
    <style>
        .notification {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .notification h3 {
            margin: 0 0 10px;
            color: #007BFF;
        }

        .notification p {
            margin: 5px 0;
            color: #333;
        }

        .notification time {
            display: block;
            color: #666;
            font-size: 0.85em;
            margin-top: 5px;
        }

        .no-notifications {
            font-style: italic;
            color: #777;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            /* Removed padding */
            margin: 0;
            /* Ensure there's no margin around the body as well */
        }


        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #000000;
            color: white;
            animation: slideDown 1s ease-in-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .title {
            text-align: center;
            flex-grow: 1;
            font-size: 24px;
            animation: bounceIn 1.5s ease-in-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .auth-buttons button {
            margin-left: 10px;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .auth-buttons button:hover {
            background-color: #4cae4c;
            transform: scale(1.1);
        }

        .sidebar-menu {
            cursor: pointer;
            font-size: 24px;
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .sidebar-menu:hover {
            transform: rotate(90deg);
            color: #5cb85c;
        }

        .sidebar {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 50px;
            left: 0;
            background-color: #000000;
            width: 200px;
            padding: 20px;
            border-radius: 5px;
            animation: slideInLeft 0.5s ease-in-out;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .sidebar a {
            color: white;
            padding: 10px 0;
            text-decoration: none;
            transition: background-color 0.3s, padding-left 0.3s;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #575757;
            padding-left: 10px;
            color: #5cb85c;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <header>
        <div class="sidebar-menu" onclick="toggleMenu()">☰</div>
        <h1 class="title">Skill Bridge</h1>
        <div class="auth-buttons">

        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="dashboard.php">Home</a>
        <a href="user_profile.php">Profile</a>
        <a href="job_listing.php">Job Listing</a>
        <a href="recive_notif.php">Notifications</a>
        <a href="resume_builder.php">Resume Builder</a>
        <a href="view_resume.php">Your Resume</a>
        <a href="tracking.php">Application Tracking</a>
        <a href="contactus.php">Contact Us</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php if (empty($notifications)) { ?>
        <p class="no-notifications">No notifications to display.</p>
    <?php } else { ?>
        <?php foreach ($notifications as $notification) { ?>
            <div class="notification">
                <h3><?php echo htmlspecialchars($notification['subject']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                <time><?php echo date("F j, Y, g:i a", strtotime($notification['created_at'])); ?></time>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
        <?php } ?>
        <?php if ($page < $total_pages) { ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php } ?>
    </div>
</body>
<script>
    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        sidebar.style.display = sidebar.style.display === 'none' || sidebar.style.display === '' ? 'flex' : 'none';
    }
</script>

</html>