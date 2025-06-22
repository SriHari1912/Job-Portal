<?php
session_start();

$servername = "localhost";
$username = "root"; // Update with your database username
$password = "sultan1369"; // Update with your database password
$dbname = "job_portal";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions for user management
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        // Prepare a statement to delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id); // "i" indicates that the parameter is an integer
        $stmt->execute();
        $stmt->close(); // Close the statement
    }
    // Add other CRUD operations (insert/update) as needed
}

// Fetch users from the database
$result = $conn->query("SELECT * FROM users");

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC); // Fetch all users as an associative array
} else {
    $users = []; // No users found
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <h1>Manage Users</h1>

    <a href="javascript:history.back()" style="text-decoration: none;">
        <button style="background-color: green; color: white; border: none; padding: 10px 15px; cursor: pointer;">
            &#8592; Go Back
        </button>
    </a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>


</html>