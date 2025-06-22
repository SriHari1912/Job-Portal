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

// Handle form submissions for resume management
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_resume'])) {
        $resume_id = $_POST['resume_id'];

        // Prepare a statement to delete the resume
        $stmt = $conn->prepare("DELETE FROM resumes WHERE id = ?");
        $stmt->bind_param("i", $resume_id); // "i" indicates that the parameter is an integer
        if ($stmt->execute()) {
            // Resume deleted successfully
            echo "Resume deleted successfully.";
        } else {
            // Handle the error
            echo "Error deleting resume: " . $stmt->error;
        }
        $stmt->close(); // Close the statement
    }
    // Add other CRUD operations (insert/update) as needed
}

// Fetch resumes from the database
$result = $conn->query("SELECT * FROM resumes");

if ($result->num_rows > 0) {
    $resumes = $result->fetch_all(MYSQLI_ASSOC); // Fetch all resumes as an associative array
} else {
    $resumes = []; // No resumes found
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resumes</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <h1>Manage Resumes</h1>
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
        <?php foreach ($resumes as $resume): ?>
            <tr>
                <td><?php echo $resume['id']; ?></td>
                <td><?php echo $resume['first_name'] . ' ' . $resume['last_name']; ?></td>
                <td><?php echo $resume['email']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="resume_id" value="<?php echo $resume['id']; ?>">
                        <button type="submit" name="delete_resume">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>