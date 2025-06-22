<?php
session_start(); // Ensure the session is started

include('includes/db.php');  // Database connection
include('includes/session.php'); // Session management
include('includes/functions.php'); // Additional functions

// Initialize messages
$error_message = '';
$success_message = '';

// Get the user ID from the session
$user_id = $_SESSION['user_id'] ?? null; // Adjust based on your session logic

if ($user_id === null) {
    $error_message = "User not logged in.";
} else {
    // Prepare SQL statement to fetch user and profile details
    $stmt = $conn->prepare("
        SELECT u.username, u.email, u.created_at, p.profile_picture, p.bio, p.achievements, p.goals, p.projects, p.project_links 
        FROM users u 
        JOIN user_profiles p ON u.id = p.user_id 
        WHERE u.id = ?
    ");
    $stmt->bind_param("i", $user_id);

    // Execute and fetch results
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error_message = "Error fetching profile details: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Handle form submission for updating profile details
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION['profile_updated'])) {
        $bio = $_POST['bio'];
        $achievements = $_POST['achievements'];
        $goals = $_POST['goals'];
        $projects = $_POST['projects'];
        $project_links = $_POST['project_links'];

        // Handle profile picture upload
        $profile_picture = $user['profile_picture'] ?? ''; // Default to current picture

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES['profile_picture']['tmp_name'];
            $file_name = $_FILES['profile_picture']['name'];
            $file_size = $_FILES['profile_picture']['size'];
            $file_type = $_FILES['profile_picture']['type'];

            // Define the path to store uploaded files
            $upload_dir = 'uploads/profile_pictures/';
            $dest_path = $upload_dir . basename($file_name);

            // Create the uploads directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the uploaded file to the designated folder
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $profile_picture = $dest_path; // Update profile picture path
            } else {
                $error_message = "Error uploading profile picture.";
            }
        }

        // Prepare SQL statement to update profile details
        $update_stmt = $conn->prepare("
            UPDATE user_profiles 
            SET profile_picture = ?, bio = ?, achievements = ?, goals = ?, projects = ?, project_links = ?
            WHERE user_id = ?
        ");
        $update_stmt->bind_param("ssssssi", $profile_picture, $bio, $achievements, $goals, $projects, $project_links, $user_id);

        // Execute and check for success
        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully!";
            $_SESSION['profile_updated'] = true; // Set flag to indicate the update has occurred
        } else {
            $_SESSION['error_message'] = "Error updating profile: " . $update_stmt->error;
        }

        // Close the update statement
        $update_stmt->close();

        // Redirect to avoid form resubmission
        header("Location: user_profile.php"); // Redirect to the user profile page
        exit; // Ensure no further code is executed
    }
}

// Check for success or error messages and clear them
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message
    unset($_SESSION['profile_updated']); // Unset the update flag after processing
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the message
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
        }

        .details {
            margin-top: 20px;
        }

        .details p {
            margin: 10px 0;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            display: none;
            /* Initially hidden */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        button {
            padding: 10px 15px;
            background-color: #1166b0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0e4e8c;
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

    <div class="container">
        <h1>User Profile</h1>
        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <div class="details">
            <h2>Profile Details</h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username'] ?? 'N/A') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
            <p><strong>Account Created:</strong> <?= htmlspecialchars($user['created_at'] ?? 'N/A') ?></p>
            <p><strong>Profile Picture:</strong>
                <?php if ($user['profile_picture']): ?>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture">
                <?php else: ?>
            <p>No profile picture uploaded.</p>
        <?php endif; ?>
        </p>
        <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($user['bio'] ?? 'N/A')) ?></p>
        <p><strong>Achievements:</strong> <?= nl2br(htmlspecialchars($user['achievements'] ?? 'N/A')) ?></p>
        <p><strong>Goals:</strong> <?= nl2br(htmlspecialchars($user['goals'] ?? 'N/A')) ?></p>
        <p><strong>Projects:</strong> <?= nl2br(htmlspecialchars($user['projects'] ?? 'N/A')) ?></p>
        <p><strong>Project Links:</strong> <?= nl2br(htmlspecialchars($user['project_links'] ?? 'N/A')) ?></p>
        </div>

        <form method="POST" enctype="multipart/form-data" style="display: block;">
            <h2>Update Profile</h2>

            <label for="bio">Bio:</label>
            <textarea name="bio" id="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

            <label for="achievements">Achievements:</label>
            <textarea name="achievements" id="achievements"><?= htmlspecialchars($user['achievements'] ?? '') ?></textarea>

            <label for="goals">Goals:</label>
            <textarea name="goals" id="goals"><?= htmlspecialchars($user['goals'] ?? '') ?></textarea>

            <label for="projects">Projects:</label>
            <textarea name="projects" id="projects"><?= htmlspecialchars($user['projects'] ?? '') ?></textarea>

            <label for="project_links">Project Links:</label>
            <textarea name="project_links" id="project_links"><?= htmlspecialchars($user['project_links'] ?? '') ?></textarea>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture">

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <script>
        // Sidebar toggle function
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
        }

        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.display = sidebar.style.display === 'none' || sidebar.style.display === '' ? 'flex' : 'none';
        }
    </script>
</body>

</html>