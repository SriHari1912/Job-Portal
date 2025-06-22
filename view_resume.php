<?php
// Database connection
$host = 'localhost';
$db = 'job_portal';
$user = 'root';
$password = 'sultan1369';

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session and ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Please log in to view your resume.");
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch resume data for the logged-in user
$resumeQuery = "SELECT * FROM resumes WHERE user_id = ?";
$resumeStmt = $conn->prepare($resumeQuery);
$resumeStmt->bind_param("i", $user_id);
$resumeStmt->execute();
$resumeResult = $resumeStmt->get_result();

if ($resumeResult->num_rows > 0) {
    $resumeData = $resumeResult->fetch_assoc();
} else {
    die("<p style='color:red;'>No resume found for this user.</p>");
}

$resumeStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f3f3;
            padding: 30px;
        }

        .resume-container {
            max-width: 850px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        .header p {
            font-size: 16px;
            color: #555;
        }

        .section-title {
            font-size: 22px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #444;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info strong {
            color: #222;
        }

        .resume-section p {
            line-height: 1.6;
            margin: 5px 0;
        }

        .skills-list {
            list-style: none;
            padding: 0;
        }

        .skills-list li {
            background-color: #eef;
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            border-radius: 20px;
            color: #444;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            /* Aligns child elements to the left and right */
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            font-style: italic;
            border-top: 1px solid #ccc;
            /* Optional: adds a top border */
            padding-top: 10px;
            /* Optional: adds some space above the footer text */
        }

        .highlight {
            font-weight: bold;
            /* Make the text bold */
            color: #007BFF;
            /* Change color to a blue shade */
            background-color: #e7f1ff;
            /* Optional: Add a light background */
            padding: 2px 5px;
            /* Optional: Add some padding around the text */
            border-radius: 5px;
            /* Optional: Rounded corners for the background */
            cursor: pointer;
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

        .download-section {
            text-align: center;
            margin-top: 30px;
        }

        .download-section button {
            padding: 12px 25px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .download-section button:hover {
            background-color: #4cae4c;
            transform: scale(1.05);
        }

        .download-section button:active {
            transform: scale(0.98);
        }

        .download-section button:focus {
            outline: none;
        }

        .download-section button:first-child {
            background-color: #007bff;
        }

        .download-section button:first-child:hover {
            background-color: #0069d9;
        }

        .download-section button:nth-child(2) {
            background-color: #f0ad4e;
        }

        .download-section button:nth-child(2):hover {
            background-color: #ec971f;
        }

        .download-section button:nth-child(3) {
            background-color: #d9534f;
        }

        .download-section button:nth-child(3):hover {
            background-color: #c9302c;
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
    <div class="resume-container">
        <!-- Header Section -->
        <div class="header">
            <h1><?php echo $resumeData['first_name'] . ' ' . $resumeData['last_name']; ?></h1>
            <p>Email: <?php echo $resumeData['email']; ?> | Phone: <?php echo $resumeData['phone']; ?></p>
        </div>

        <!-- Address Section -->
        <div class="resume-section">
            <div class="section-title">Address</div>
            <p><?php echo htmlspecialchars($resumeData['address']); ?></p>
        </div>

        <!-- Summary Section -->
        <div class="resume-section">
            <div class="section-title">Summary</div>
            <p><?php echo nl2br($resumeData['summary']); ?></p>
        </div>

        <!-- Skills Section -->
        <div class="resume-section">
            <div class="section-title">Skills</div>
            <ul class="skills-list">
                <?php
                $skills = explode(',', $resumeData['skills']);
                foreach ($skills as $skill) {
                    echo "<li>" . htmlspecialchars(trim($skill)) . "</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Work Experience Section -->
        <div class="resume-section">
            <div class="section-title">Work Experience</div>
            <p><?php echo nl2br($resumeData['work_experience']); ?></p>
        </div>

        <!-- Education Section -->
        <div class="resume-section">
            <div class="section-title">Education</div>
            <p><?php echo nl2br($resumeData['education']); ?></p>
        </div>

        <!-- Certifications Section -->
        <div class="resume-section">
            <div class="section-title">Certifications</div>
            <p><?php echo nl2br($resumeData['certifications']); ?></p>
        </div>

        <!-- Footer -->
        <!-- Footer -->
        <div class="footer">
            <p>Created by <a class="highlight" href="contactus.php">Skill Bridge</a></p>

            <p>Resume created on: <?php echo date('F j, Y, g:i a', strtotime($resumeData['created_at'])); ?></p>
        </div>

        <!-- Download Button -->


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