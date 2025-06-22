<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "sultan1369";
$dbname = "job_portal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch jobs from the database
$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Job Listings</title>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<style>
    /* Reset some default styles */
    body,
    h1,
    h2,
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



    .sidebar-menu1 {
        cursor: pointer;
        font-size: 24px;
        margin-right: 10px;
        transition: transform 0.3s;
    }

    .sidebar-menu1:hover {
        transform: rotate(90deg);
        color: #5cb85c;
    }

    .sidebar1 {
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

    .sidebar1 a {
        color: white;
        padding: 10px 0;
        text-decoration: none;
        transition: background-color 0.3s, padding-left 0.3s;
        border-radius: 5px;
    }

    .sidebar1 a:hover {
        background-color: #575757;
        padding-left: 10px;
        color: #5cb85c;
        border-radius: 5px;
    }

    /* Body styles */


    /* Main heading styles */
    h1 {
        text-align: center;
        margin: 20px 0;
        color: #4CAF50;
        /* Green color */
    }

    /* Job listing container */
    div {
        background: #fff;
        /* White background for each job */
        border: 1px solid #ddd;
        /* Light grey border */
        border-radius: 8px;
        /* Rounded corners */
        margin: 20px;
        /* Space around each job */
        padding: 15px;
        /* Space inside each job */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Subtle shadow for depth */
    }

    /* Job title styles */
    h2 {
        color: #333;
        /* Darker title color */
    }

    /* Paragraph styles */
    p {
        margin: 5px 0;
        /* Space between paragraphs */
    }

    /* Link styles */
    a {
        display: inline-block;
        margin-top: 10px;
        /* Space above the link */
        padding: 10px 15px;
        /* Padding inside the button */
        background-color: #4CAF50;
        /* Green button background */
        color: white;
        /* White text color */
        text-decoration: none;
        /* Remove underline from links */
        border-radius: 4px;
        /* Rounded corners for the button */
    }

    a:hover {
        background-color: #45a049;
        /* Darker green on hover */
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
    <h1>Job Listings</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
            echo "<p>Company: " . htmlspecialchars($row['company']) . "</p>";
            echo "<p>Location: " . htmlspecialchars($row['location']) . "</p>";
            echo "<p>Salary: $" . number_format($row['salary'], 2) . "</p>";
            echo "<p>Posted on: " . $row['posted_date'] . "</p>";
            echo "<p>Description: " . nl2br(htmlspecialchars($row['description'])) . "</p>";
            echo '<a href="job_apply.php?job_id=' . $row['id'] . '">Apply Now</a>';
            echo "</div><hr>";
        }
    } else {
        echo "<p>No job listings available.</p>";
    }
    ?>
</body>

</html>

<?php
$conn->close();
?>