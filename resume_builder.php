<?php
// Database connection
$host = 'localhost';
$db = 'job_portal';
$user = 'root';
$password = 'sultan1369';

$conn = new mysqli($host, $user, $password, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to access user data (make sure session is initialized after login)
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Please login to create your resume.");
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch user information from 'users' table
$userQuery = "SELECT first_name, last_name, email, phone FROM users WHERE id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc();
    $firstName = $userData['first_name'];
    $lastName = $userData['last_name'];
    $email = $userData['email'];
    $phone = $userData['phone'];
} else {
    die("User data not found.");
}

$userStmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'] ?? ''; // Collect address input
    $summary = $_POST['summary'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $workExperience = $_POST['work_experience'] ?? '';
    $education = $_POST['education'] ?? '';
    $certifications = $_POST['certifications'] ?? '';

    // Prepare SQL query to insert resume data
    $sql = "INSERT INTO resumes 
            (user_id, first_name, last_name, email, phone, address, summary, skills, work_experience, education, certifications) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssssss",
        $user_id,
        $firstName,
        $lastName,
        $email,
        $phone,
        $address,
        $summary,
        $skills,
        $workExperience,
        $education,
        $certifications
    );

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Resume submitted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Resume</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .resume-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .resume-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .resume-form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .resume-form input,
        .resume-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .resume-form textarea {
            height: 80px;
            resize: none;
        }

        .submit-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #218838;
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
    <div class="resume-form">
        <h2>Create Your Resume</h2>
        <form method="POST" action="">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" placeholder="Your address" required>

            <label for="summary">Summary</label>
            <textarea name="summary" id="summary" placeholder="Briefly introduce yourself" required></textarea>

            <label for="skills">Skills (comma-separated)</label>
            <input type="text" name="skills" id="skills" placeholder="e.g., HTML, CSS, JavaScript" required>

            <label for="work_experience">Work Experience</label>
            <textarea name="work_experience" id="work_experience" placeholder="Describe your work experience" required></textarea>

            <label for="education">Education</label>
            <textarea name="education" id="education" placeholder="Your educational background" required></textarea>

            <label for="certifications">Certifications</label>
            <textarea name="certifications" id="certifications" placeholder="List your certifications" required></textarea>

            <button type="submit" class="submit-button">Submit Resume</button>
        </form>
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