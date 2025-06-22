<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "sultan1369";
$dbname = "job_portal";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define the expected reference ID
    $expected_reference_id = "#13s6f9";

    // Check if the provided reference ID matches the expected one
    if ($_POST['reference_id'] !== $expected_reference_id) {
        echo "Error: The reference ID is incorrect.<br>";
    } else {
        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // SQL to insert a new admin
            $insertAdminSQL = "
                INSERT INTO admins (full_name, email, phone_number, username, password, reference_id) 
                VALUES (:full_name, :email, :phone_number, :username, :password, :reference_id)
            ";

            // Prepare the statement
            $stmt = $conn->prepare($insertAdminSQL);
            $stmt->bindParam(':full_name', $_POST['full_name']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':phone_number', $_POST['phone_number']);
            $stmt->bindParam(':username', $_POST['username']);
            // Hash the password before storing it
            $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hashed);
            $stmt->bindParam(':reference_id', $_POST['reference_id']); // Use the input reference ID

            // Execute the statement
            $stmt->execute();
            echo "New admin record created successfully.<br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Close the connection
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
</head>

<link rel="stylesheet" href="adminstyle.css">

<body>

    <form method="POST" action="">
        <h2>Admin Registration</h2>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="reference_id">Reference ID:</label>
        <input type="text" id="reference_id" name="reference_id" required><br><br>

        <button type="submit">Register Admin</button>
        <p>Already registered? <a href="adminlogin.php">Go to login page</a>.</p>
    </form>

</body>

</html>