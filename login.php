<?php
include('includes/db.php');       // Database connection
include('includes/session.php');  // Session management
include('includes/functions.php'); // Helper functions

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already active
}

// Initialize message variable
$logout_message = '';

// Check if there is a logout message
if (isset($_SESSION['logout_message'])) {
    $logout_message = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']); // Clear the message after displaying
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    // Call a function to handle user authentication (this function should be defined in functions.php)
    $login_successful = login($username, $password); // You'll need to implement this function

    if ($login_successful) {
        // Redirect to dashboard or another page on successful login
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Bridge Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
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

    .login-form {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: 0 auto;
        text-align: left;
    }

    .login-form h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .login-form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: #333;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .login-form input[type="text"]:focus,
    .login-form input[type="password"]:focus {
        border-color: #1166b0;
        outline: none;
    }

    .login-form .forgot-password {
        display: block;
        margin-bottom: 20px;
        color: #1166b0;
        text-decoration: none;
    }

    .login-form .forgot-password:hover {
        text-decoration: underline;
        color: #5cb85c;
    }

    .login-form button {
        width: 100%;
        padding: 10px;
        background-color: #1166b0;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
    }

    .login-form button:hover {
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

    .password-container {
        position: relative;
        width: 90%;
    }

    .password-container input[type="password"] {
        padding-right: 40px;
    }

    .password-toggle {
        position: absolute;
        top: 40%;
        right: 5px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #ccc;
    }

    .password-toggle:hover {
        color: #1166b0;
    }

    .success {
        color: green;
    }
</style>

<body>
    <header>
        <div class="sidebar-menu" onclick="toggleMenu()">☰</div>
        <h1 class="title">Login</h1>
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
        <?php if (isset($error_message)): ?>
            <div style="color: red;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="login-form">
            <h2>Login</h2>
            <?php if (!empty($logout_message)): ?>
                <p class="success"><?= $logout_message; ?></p>
            <?php endif; ?>
            <form id="login" action="login.php" method="post">
                <label for="user_name">Username</label>
                <input type="text" id="user_name" name="user_name" placeholder="Enter your username" required>

                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                </div>

                <a href="send_reset.php" class="forgot-password">Forgot Password?</a>

                <button type="submit">Log In</button>
            </form>
        </div>
    </main>

    <footer>
        <a href="#">Facebook</a>
        <a href="#">Twitter</a>
        <a href="#">Instagram</a>
        <a href="#">WhatsApp</a>
    </footer>

    <script>
        function toggleMenu() {
            var sidebar = document.getElementById("sidebar");
            if (sidebar.style.display === "none" || sidebar.style.display === "") {
                sidebar.style.display = "flex";
            } else {
                sidebar.style.display = "none";
            }
        }

        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var passwordToggle = document.querySelector('.password-toggle');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>