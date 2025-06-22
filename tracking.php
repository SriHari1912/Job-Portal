<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "sultan1369";
$dbname = "job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch application statuses for the logged-in user
$sql = "SELECT job_id, status FROM job_applications WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Create an array to hold application statuses
$applications = array();
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Statuses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .status-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .status {
            padding: 10px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .status-applied {
            background-color: #007bff;
            /* Blue */
        }

        .status-under-review {
            background-color: #ffc107;
            /* Yellow */
        }

        .status-interview-scheduled {
            background-color: #28a745;
            /* Green */
        }

        .status-interviewing {
            background-color: #17a2b8;
            /* Cyan */
        }

        .status-offer-extended {
            background-color: #fd7e14;
            /* Orange */
        }

        .status-offer-accepted {
            background-color: #6f42c1;
            /* Purple */
        }

        .status-rejected {
            background-color: #dc3545;
            /* Red */
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

        .title1 {
            text-align: center;
            flex-grow: 1;
            color: white;
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
        <h1 class="title1">Skill Bridge</h1>
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
    <h1>Your Application Statuses</h1>
    <div class="status-container">
        <?php foreach ($applications as $application): ?>
            <div class="status status-<?php echo strtolower(str_replace(' ', '-', $application['status'])); ?>">
                Job ID: <?php echo htmlspecialchars($application['job_id']); ?> - Status: <?php echo htmlspecialchars($application['status']); ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
<script>
    // Toggle the profile update form
    document.getElementById('toggle-update-form').addEventListener('click', function() {
        var form = document.getElementById('update-profile-form');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    });

    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        sidebar.style.display = sidebar.style.display === 'none' || sidebar.style.display === '' ? 'flex' : 'none';
    }
</script>

</html>