<?php
include('includes/db.php');  // Database connection
include('includes/session.php'); // Session management
include('includes/functions.php'); // Additional functions

// Initialize messages
$success_message = '';
$error_message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $qualification = $_POST['qualification'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $check_email_stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();

    // If the email exists, show an error message
    if ($check_email_stmt->num_rows > 0) {
        $error_message = "This email is already registered. Please use a different email.";
    } else {
        // Prepare SQL statement for insertion
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, dob, gender, email, phone, qualification, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $first_name, $last_name, $dob, $gender, $email, $phone, $qualification, $username, $hashed_password);

        // Execute and check for success
        if ($stmt->execute()) {
            // Registration successful, redirect to profile details form
            $user_id = $stmt->insert_id; // Get the newly created user ID
            header("Location: profile.php?user_id=$user_id"); // Redirect to profile page
            exit();
        } else {
            $error_message = "Error: " . $stmt->error; // Show error
        }

        // Close the statement
        $stmt->close();
    }

    // Close the email check statement
    $check_email_stmt->close();
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Bridge Signup</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f0f0f0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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

        main {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .signup-form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
            text-align: left;
        }

        .signup-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .signup-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .signup-form input[type="text"],
        .signup-form input[type="password"],
        .signup-form input[type="email"],
        .signup-form input[type="date"],
        .signup-form input[type="tel"],
        .signup-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .signup-form input:focus,
        .signup-form select:focus {
            border-color: #1166b0;
            outline: none;
        }

        .signup-form button {
            width: 100%;
            padding: 10px;
            background-color: #1166b0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .signup-form button:hover {
            background-color: #286090;
            transform: translateY(-5px);
        }

        footer {
            display: flex;
            justify-content: center;
            background-color: #000000;
            color: white;
            padding: 10px 0;
            animation: slideUp 1s ease-in-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        footer a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            transition: color 0.3s, transform 0.3s;
        }

        footer a:hover {
            text-decoration: underline;
            color: #5cb85c;
            transform: translateY(-3px);
        }

        .already:hover {
            color: #5cb85c;
        }
    </style>
</head>

<body>
    <header>
        <div class="sidebar-menu" onclick="toggleMenu()">☰</div>
        <h1 class="title">Sign Up</h1>
        <div class="auth-buttons">
            <button onclick="location.href='register.php'">Sign Up</button>
            <button onclick="location.href='login.php'">Log In</button>
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Sign Up</a>
    </div>

    <main>
        <div class="signup-form">
            <h2>Sign Up</h2>

            <!-- Display success or error messages -->
            <?php if (!empty($success_message)): ?>
                <p style="color: green;"><?= $success_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?= $error_message; ?></p>
            <?php endif; ?>

            <form id="register" action="register.php" method="post">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required>

                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" required>

                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>

                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

                <label for="qualification">Qualification</label>
                <input type="text" id="qualification" name="qualification" placeholder="Enter your qualification" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Choose a password" required>

                <button type="submit">Sign Up</button>
            </form>
            <p class="already">Already have an account? <a href="login.php">Log In</a></p>
        </div>
    </main>

    <footer>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact Us</a>
    </footer>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.display = sidebar.style.display === 'none' || sidebar.style.display === '' ? 'flex' : 'none';
        }
    </script>
</body>

</html>