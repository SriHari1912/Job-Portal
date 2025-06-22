<?php
include('includes/db.php');  // Database connection
include('includes/session.php'); // Session management
include('includes/functions.php'); // Additional functions

// Start output buffering to manage headers later if needed
ob_start();

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Ensure the user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $content = $_POST['content'] ?? '';

        // Initialize variables for media upload
        $mediaPath = '';
        if (isset($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['media']['tmp_name'];
            $fileName = $_FILES['media']['name'];
            $fileSize = $_FILES['media']['size'];
            $fileType = $_FILES['media']['type'];
            $fileNameParts = pathinfo($fileName);
            $newFileName = uniqid() . '.' . $fileNameParts['extension'];
            $uploadFileDir = 'uploads/'; // Ensure this directory exists and is writable

            // Check for allowed file types (images and videos)
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'video/mov'];
            if (in_array($fileType, $allowedFileTypes)) {
                // Move the file to the uploads directory
                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $mediaPath = $uploadFileDir . $newFileName; // Set media path for DB
                } else {
                    $response['message'] = 'Error moving the uploaded file.';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['message'] = 'Invalid file type. Please upload an image or video.';
                echo json_encode($response);
                exit();
            }
        }

        // Insert the post into the database
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, media, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $content, $mediaPath);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['media'] = $mediaPath; // Return the media path for UI update
            $response['message'] = 'Post created successfully!';
        } else {
            $response['message'] = 'Error creating the post: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'User not logged in.';
    }

    // Close the connection here for AJAX response
    $conn->close();
    echo json_encode($response);
    exit(); // Exit to prevent further execution
}

// Fetch posts with user profile information, including the profile picture from the user_profiles table
$sql = "
SELECT p.id, p.content, p.media, p.created_at, 
       u.username, up.profile_picture
FROM posts p
JOIN users u ON p.user_id = u.id
JOIN user_profiles up ON u.id = up.user_id
ORDER BY RAND()"; // Randomize post order


$result = $conn->query($sql);
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

$conn->close(); // Close the connection here, after fetching posts
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Bridge Dashboard</title>
    <style>
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



        .dashboard-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .posts-container {
            margin-bottom: 20px;
        }

        .post {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .post-header {
            display: flex;
            align-items: center;
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .timestamp {
            font-size: 0.8em;
            color: #777;
        }

        .post-content {
            margin: 10px 0;
        }

        .post-media {
            max-width: 50%;
            border-radius: 5px;
        }

        .comment-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .create-post-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 24px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

    <div class="dashboard-container">


        <div class="posts-container">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-header">
                        <img src="<?php echo $post['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
                        <div class="user-info">
                            <strong><?php echo $post['username']; ?></strong>
                            <span class="timestamp"><?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></span>
                        </div>
                    </div>
                    <div class="post-content">
                        <p><?php echo $post['content']; ?></p>
                        <?php if ($post['media']): ?>
                            <?php
                            $fileType = pathinfo($post['media'], PATHINFO_EXTENSION);
                            if (in_array($fileType, ['mp4', 'avi', 'mov'])):
                            ?>
                                <video controls class="post-media" style="max-width: 50%;">
                                    <source src="<?php echo $post['media']; ?>" type="video/<?php echo $fileType; ?>">
                                    Your browser does not support the video tag.
                                </video>
                            <?php else: ?>
                                <img src="<?php echo $post['media']; ?>" alt="Post Media" class="post-media">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <button class="create-post-button">+</button>
    </div>

    <!-- Modal for Creating Post -->
    <div id="createPostModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Create Post</h2>
            <form id="postForm" enctype="multipart/form-data">
                <div>
                    <label for="postContent">Post Content:</label>
                    <textarea id="postContent" name="content" rows="4" style="width: 100%;"></textarea>
                </div>
                <div>
                    <label for="postMedia">Upload Media:</label>
                    <input type="file" id="postMedia" name="media" accept="image/*,video/*">
                </div>
                <button type="submit">Submit Post</button>
            </form>
            <div id="postResponse" style="margin-top: 10px;"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get modal elements
            const modal = document.getElementById('createPostModal');
            const btn = document.querySelector('.create-post-button');
            const span = document.getElementsByClassName('close')[0];

            // Show the modal
            btn.onclick = function() {
                modal.style.display = 'block';
            }

            // Close the modal
            span.onclick = function() {
                modal.style.display = 'none';
            }

            // Close the modal if clicking outside of it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            // Handle form submission
            const form = document.getElementById('postForm');
            form.onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch('dashboard.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('postResponse').innerText = data.message;
                        if (data.status === 'success') {
                            modal.style.display = 'none';
                            location.reload(); // Reload the page to show the new post
                        }
                    })
                    .catch(error => {
                        document.getElementById('postResponse').innerText = 'Error: ' + error.message;
                    });
            }
        });
        // Toggle the post form
        document.getElementById('floating-button').addEventListener('click', function() {
            var form = document.getElementById('post-form');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        });

        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.display = sidebar.style.display === 'none' || sidebar.style.display === '' ? 'flex' : 'none';
        }
    </script>
</body>

</html>