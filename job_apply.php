<?php
// Start the PHP session
session_start();

// Database connection details
$servername = "localhost"; // Update with your server name
$username = "root"; // Update with your database username
$password = "sultan1369"; // Update with your database password
$dbname = "job_portal"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize a message variable
$message = "";

// Fetch user ID from session (assuming user is logged in)
$user_id = $_SESSION['user_id']; // Ensure user_id is stored in session when user logs in

// Fetch the latest resume ID for the logged-in user
$resume_id = null;
if ($user_id) {
    // Fetch the latest resume ID from the database
    $result = $conn->query("SELECT id FROM resumes WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $resume_id = $row['id'];
    } else {
        $message = "No resume found for this user.";
    }
}

// Fetch job title and job ID based on job ID from the URL
$job_id = null;
$job_title = null;
if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $job_result = $conn->query("SELECT title FROM jobs WHERE id = $job_id");

    if ($job_result->num_rows > 0) {
        $job_row = $job_result->fetch_assoc();
        $job_title = $job_row['title'];
    } else {
        $message = "No job found with the provided ID.";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if resume_id and job_id are available
    if ($resume_id && $job_id) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO job_applications (user_id, resume_id, job_id, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $user_id, $resume_id, $job_id, $email, $phone); // include job_id

        // Execute the statement
        if ($stmt->execute()) {
            $message = "Application submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $message = "Cannot submit application. No resume ID or job ID available.";
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<style>
    /* Reset some default styles */
    body,
    h2,
    h3,
    p {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
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

    /* Body styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    /* Container for the form */
    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Heading styles */
    h2 {
        text-align: center;
        color: #4CAF50;
        /* Green color for the title */
    }

    h3 {
        margin-bottom: 20px;
        color: #555;
    }

    /* Form group styles */
    .form-group {
        margin-bottom: 15px;
    }

    /* Label styles */
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    /* Input field styles */
    input[type="email"],
    input[type="text"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    /* Change the border color on focus */
    input[type="email"]:focus,
    input[type="text"]:focus {
        border-color: #4CAF50;
        /* Green border on focus */
        outline: none;
        /* Remove default outline */
    }

    /* Submit button styles */
    input[type="submit"] {
        background-color: #4CAF50;
        /* Green background */
        color: white;
        /* White text */
        border: none;
        /* Remove border */
        cursor: pointer;
        /* Change cursor on hover */
    }

    input[type="submit"]:hover {
        background-color: #45a049;
        /* Darker green on hover */
    }

    /* Message styles */
    .message {
        margin-top: 20px;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
        /* Light background for messages */
        border: 1px solid #ddd;
        color: #d9534f;
        /* Bootstrap danger color */
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        font-family: Arial, sans-serif;
        font-size: 16px;
        padding: 10px 15px;
        background-color: green;
        /* Background color */
        color: white;
        /* Text color */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    /* Style for the back arrow icon */
    .back-button i {
        margin-right: 8px;
        /* Space between the icon and the text */
    }

    /* Hover effect */
    .back-button:hover {
        background-color: red;
        /* Darker shade on hover */
    }
</style>

<body>
    <button class="back-button" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> Go Back
    </button>
    <div class="container">
        <h2>Job Application Form</h2>

        <?php if ($resume_id && $job_title): ?>
            <h3>Applying for: <?php echo htmlspecialchars($job_title); ?></h3>
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Apply">
                </div>
            </form>
        <?php else: ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>