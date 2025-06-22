<?php
session_start();
include('includes/db.php'); // Database connection

// Initialize messages
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id_to_notify = $_POST['user_id'] ?? null; // Null for global notifications
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($subject) || empty($message)) {
        $error_message = "Both subject and message are required.";
    } else {
        try {
            if ($user_id_to_notify) {
                $stmt = $conn->prepare("INSERT INTO notifications (user_id, subject, message) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id_to_notify, $subject, $message);
            } else {
                $stmt = $conn->prepare("INSERT INTO notifications (user_id, subject, message) VALUES (NULL, ?, ?)");
                $stmt->bind_param("ss", $subject, $message);
            }

            if ($stmt->execute()) {
                $success_message = "Notification sent successfully.";
            } else {
                $error_message = "Error sending notification: " . $stmt->error;
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Send Notification</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #000;
            /* Black background */
            color: #fff;
            /* White text for contrast */
        }

        h1,
        h2 {
            color: #00ff00;
            /* Bright green for headings */
            text-align: center;
        }

        /* Form Styles */
        form {
            background-color: rgba(58, 58, 58, 0.888);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
            text-align: left;
        }

        label {
            color: #ff0000;
            /* Bright red for labels */
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #111;
            /* Dark background for input fields */
            color: #fff;
            /* White text for input fields */
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            border-color: #1abc9c;
            /* Change border color on focus */
            outline: none;
            /* Remove default outline */
        }

        button {
            background-color: #00ff00;
            /* Bright green for buttons */
            color: #000;
            /* Black text for button */
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00cc00;
            /* Darker green on hover */
        }

        p {
            color: #ff0000;
            /* Bright red for error messages */
        }

        .success {
            color: #00ff00;
            /* Green for success messages */
        }

        .back-btn {
            background-color: green;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 10px 15px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #008000;
        }

        /* Optional: Hover effects */
        label[for="user_id"],
        label[for="subject"],
        label[for="message"] {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <a href="javascript:history.back()" class="back-btn">
        &#8592; Go Back
    </a>
    <h1>Send Notification</h1>

    <?php if (!empty($success_message)) echo "<p class='success'>$success_message</p>"; ?>
    <?php if (!empty($error_message)) echo "<p>$error_message</p>"; ?>

    <form method="post" action="">


        <label for="user_id">Send To:</label>
        <select name="user_id" id="user_id">
            <option value="">All Users</option>
            <?php
            $result = $conn->query("SELECT id, username FROM users");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['username']}</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" required>
        <br><br>

        <label for="message">Message:</label>
        <textarea name="message" id="message" required></textarea>
        <br><br>

        <button type="submit">Send Notification</button>
    </form>
</body>

</html>