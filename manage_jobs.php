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

// Handle form submissions for job management
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle job deletion
    if (isset($_POST['delete_job'])) {
        $job_id = $_POST['job_id'];

        // Prepare a statement to delete the job
        $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $job_id); // "i" indicates that the parameter is an integer
        if ($stmt->execute()) {
            // Job deleted successfully
            echo "Job deleted successfully.";
        } else {
            // Handle the error
            echo "Error deleting job: " . $stmt->error;
        }
        $stmt->close(); // Close the statement
    }

    // Handle job addition
    if (isset($_POST['add_job'])) {
        $title = $_POST['title'];
        $company = $_POST['company'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $salary = $_POST['salary'];

        // Prepare a statement to insert a new job
        $stmt = $conn->prepare("INSERT INTO jobs (title, description, company, location, salary) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $title, $description, $company, $location, $salary); // "ssssd" indicates types: 2 strings, 1 string, 1 string, 1 decimal
        if ($stmt->execute()) {
            // Job added successfully
            echo "Job added successfully.";
        } else {
            // Handle the error
            echo "Error adding job: " . $stmt->error;
        }
        $stmt->close(); // Close the statement
    }
}

// Fetch jobs from the database
$result = $conn->query("SELECT * FROM jobs");

if ($result->num_rows > 0) {
    $jobs = $result->fetch_all(MYSQLI_ASSOC); // Fetch all jobs as an associative array
} else {
    $jobs = []; // No jobs found
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <h1>Manage Jobs</h1>
    <a href="javascript:history.back()" style="text-decoration: none;">
        <button style="background-color: green; color: white; border: none; padding: 10px 15px; cursor: pointer;">
            &#8592; Go Back
        </button>
    </a>


    <form method="POST">
        <h2>Add Job</h2>
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Job Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="company">Company:</label>
        <input type="text" id="company" name="company" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" step="0.01" required>

        <button type="submit" name="add_job">Add Job</button>
    </form>

    <h2>Current Jobs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Company</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?php echo $job['id']; ?></td>
                <td><?php echo $job['title']; ?></td>
                <td><?php echo $job['description']; ?></td>
                <td><?php echo $job['company']; ?></td>
                <td><?php echo $job['location']; ?></td>
                <td><?php echo number_format($job['salary'], 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                        <button type="submit" name="delete_job">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>