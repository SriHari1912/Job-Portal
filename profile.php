<?php
include('includes/db.php');  // Database connection
include('includes/session.php'); // Session management
include('includes/functions.php'); // Additional functions

// Initialize messages
$success_message = '';
$error_message = '';

// Check if the user ID is provided
if (!isset($_GET['user_id'])) {
    header("Location: register.php"); // Redirect to registration if no user ID
    exit();
}

$user_id = $_GET['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve profile data
    $bio = $_POST['bio'];
    $achievements = $_POST['achievements'];
    $goals = $_POST['goals'];
    $projects = $_POST['projects'];
    $project_links = $_POST['project_links'];

    // Handle profile picture upload
    $target_dir = "uploads/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check === false) {
        $error_message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["profile_pic"]["size"] > 5000000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if everything is ok to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            // Prepare SQL statement for insertion
            $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, profile_picture, bio, achievements, goals, projects, project_links) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $user_id,  $target_file, $bio, $achievements, $goals, $projects, $project_links);


            // Execute and check for success
            if ($stmt->execute()) {
                $success_message = "Profile created successfully!";
            } else {
                $error_message = "Error: " . $stmt->error; // Show error
            }

            // Close the statement
            $stmt->close();
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile</title>
    <style>
        /* Your existing CSS styles for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
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
            /* Allow vertical resizing for textarea */
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
    </style>
</head>

<body>
    <main>
        <div class="profile-form">
            <h2>Create Profile</h2>

            <!-- Display success or error messages -->
            <?php if (!empty($success_message)): ?>
                <p class="success"><?= $success_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?= $error_message; ?></p>
            <?php endif; ?>

            <form action="profile.php?user_id=<?= $user_id ?>" method="post" enctype="multipart/form-data">
                <label for="profile_pic">Profile Picture</label>
                <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" placeholder="Tell us about yourself" required></textarea>

                <label for="achievements">Achievements</label>
                <input type="text" id="achievements" name="achievements" placeholder="List your achievements" required>

                <label for="goals">Goals</label>
                <input type="text" id="goals" name="goals" placeholder="What are your goals?" required>

                <label for="projects">Projects</label>
                <input type="text" id="projects" name="projects" placeholder="Your projects" required>

                <label for="project_links">Project Links</label>
                <input type="text" id="project_links" name="project_links" placeholder="Links to your projects" required>

                <button type="submit">Create Profile</button>
            </form>
        </div>
    </main>
</body>

</html>