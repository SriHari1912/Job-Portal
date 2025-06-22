<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "sultan1369";
$dbname = "job_portal";

// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create a new PDO instance
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL to fetch the admin record based on the username
        $sql = "SELECT * FROM admins WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->execute();

        // Check if the user exists
        if ($stmt->rowCount() == 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verify the password
            if (password_verify($_POST['password'], $admin['password'])) {
                // Successful login
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username']; // Store username in session
                $_SESSION['email'] = $admin['email']; // Store email in session
                echo "Login successful. Welcome, " . htmlspecialchars($admin['username']) . "!";
                // Redirect to the admin dashboard or any other page
                header("Location: admindashboard.php");
                exit();
            } else {
                echo "Invalid password. Please try again.";
            }
        } else {
            echo "No admin found with that username.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>

<link rel="stylesheet" href="adminstyle.css">

<body>

    <form method="POST" action="">
        <h2>Admin Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
        <p>
            ! Not registered yet? <a href="adminsignup.php">Please register here!</a>
        </p>
    </form>

    <!-- Registration link -->

</body>


</html>