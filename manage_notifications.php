<?php
session_start();
include('includes/db.php'); // Database connection

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    // If not logged in as admin, redirect to login page
    header("Location: login.php");
    exit;
}

// Handle delete notification
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_notifications.php');
    exit;
}

// Handle edit notification
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sql = "UPDATE notifications SET subject = ?, message = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $subject, $message, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_notifications.php');
    exit;
}

// Fetch all notifications along with user emails, including those for all users (user_id IS NULL)
$sql = "
    SELECT notifications.*, users.email 
    FROM notifications
    LEFT JOIN users ON notifications.user_id = users.id
";
$result = $conn->query($sql);

// Fetch single notification for editing
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    $edit_notification = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notifications</title>
    <style>
        /* Provided styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #000;
            color: #fff;
        }

        h1 {
            color: #00ff00;
            text-align: center;
        }

        nav {
            margin-bottom: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
            color: #ff0000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #111;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #222;
            color: #00ff00;
        }

        td {
            color: #fff;
        }

        tr:hover {
            background-color: #333;
        }

        .back-arrow {
            color: #ff0000;
            font-size: 24px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .back-arrow:hover {
            text-decoration: underline;
        }

        .btn {
            background-color: #00ff00;
            color: #000;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #00cc00;
        }

        .form-container {
            background-color: rgba(58, 58, 58, 0.888);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: left;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            background-color: #000000;
            color: #ecf0f1;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <!-- Back Arrow to Go Back -->
    <span class="back-arrow" onclick="window.history.back();">&larr; Back</span>

    <h1>Manage Notifications</h1>

    <?php if (isset($edit_notification)): ?>
        <!-- Edit Notification Form -->
        <div class="form-container">
            <h2>Edit Notification</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $edit_notification['id']; ?>">
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" value="<?php echo htmlspecialchars($edit_notification['subject']); ?>" required>
                <label for="message">Message</label>
                <textarea name="message" id="message" required><?php echo htmlspecialchars($edit_notification['message']); ?></textarea>
                <button type="submit" name="edit" class="btn">Save Changes</button>
            </form>
        </div>
    <?php else: ?>
        <!-- Notifications Table -->
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>User Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td>
                            <?php
                            // Display user email or "All Users" if the notification is for all users
                            if ($row['user_id'] == NULL) {
                                echo "All Users";
                            } else {
                                echo htmlspecialchars($row['email']);
                            }
                            ?>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="manage_notifications.php?edit_id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                            <a href="manage_notifications.php?delete=<?php echo $row['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>