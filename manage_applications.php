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

// Handle form submissions for application management
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        $application_id = $_POST['application_id'];
        $status = $_POST['status'];

        // Prepare and execute update statement
        $stmt = $conn->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $application_id); // "si" indicates the first parameter is a string and the second is an integer
        if ($stmt->execute()) {
            // Status updated successfully
            echo "Application status updated successfully.";
        } else {
            // Handle the error
            echo "Error updating application status: " . $stmt->error;
        }
        $stmt->close(); // Close the statement
    }
    // Add other CRUD operations (insert/update) as needed
}

// Fetch job applications from the database
$result = $conn->query("SELECT ja.*, u.first_name, u.last_name, j.title 
    FROM job_applications ja
    JOIN users u ON ja.user_id = u.id
    JOIN jobs j ON ja.job_id = j.id");

if ($result->num_rows > 0) {
    $applications = $result->fetch_all(MYSQLI_ASSOC); // Fetch all applications as an associative array
} else {
    $applications = []; // No applications found
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <h1>Manage Applications</h1>
    <a href="javascript:history.back()" style="text-decoration: none;">
        <button style="background-color: green; color: white; border: none; padding: 10px 15px; cursor: pointer;">
            &#8592; Go Back
        </button>
    </a>
    <table>
        <tr>
            <th>ID</th>
            <th>Applicant</th>
            <th>Job Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($applications as $application): ?>
            <tr>
                <td><?php echo $application['id']; ?></td>
                <td><?php echo $application['first_name'] . ' ' . $application['last_name']; ?></td>
                <td><?php echo $application['title']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                        <select name="status">
                            <option value="Applied" <?php echo $application['status'] == 'Applied' ? 'selected' : ''; ?>>Applied</option>
                            <option value="Under Review" <?php echo $application['status'] == 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                            <option value="Interview Scheduled" <?php echo $application['status'] == 'Interview Scheduled' ? 'selected' : ''; ?>>Interview Scheduled</option>
                            <option value="Interviewing" <?php echo $application['status'] == 'Interviewing' ? 'selected' : ''; ?>>Interviewing</option>
                            <option value="Offer Extended" <?php echo $application['status'] == 'Offer Extended' ? 'selected' : ''; ?>>Offer Extended</option>
                            <option value="Offer Accepted" <?php echo $application['status'] == 'Offer Accepted' ? 'selected' : ''; ?>>Offer Accepted</option>
                            <option value="Rejected" <?php echo $application['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>