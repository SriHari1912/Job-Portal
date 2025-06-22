<?php
$host = 'localhost';
$db   = 'job_portal';
$user = 'root';
$pass = 'sultan1369';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<div style='
            max-width: 400px; 
            margin: 50px auto; 
            padding: 15px; 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            border-radius: 5px; 
            text-align: center;
            font-size: 16px;
            font-family: Arial, sans-serif;
        '>
        <strong>Error!</strong> Connection failed: " . $conn->connect_error . "
        <br><a href='login.php' style='
    display: inline-block; 
    margin-top: 10px; 
    text-decoration: none; 
    color: white; 
    background-color: black; /* Changed to black */
    padding: 8px 12px; 
    border-radius: 5px;
'>Go to Login</a>

    </div>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verify token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Update user's password
        $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->bind_param("ss", $newPassword, $email);
        if ($update->execute()) {
            echo "
            <div style='
                max-width: 400px; 
                margin: 50px auto; 
                padding: 15px; 
                background-color: #d4edda; 
                color: #155724; 
                border: 1px solid #c3e6cb; 
                border-radius: 5px; 
                text-align: center;
                font-size: 16px;
                font-family: Arial, sans-serif;
            '>
                <strong>Success!</strong> Password has been reset successfully.
                <br><a href='login.php' style='
    display: inline-block; 
    margin-top: 10px; 
    text-decoration: none; 
    color: white; 
    background-color: black; /* Changed to black */
    padding: 8px 12px; 
    border-radius: 5px;
'>Go to Login</a>

            </div>";
        } else {
            echo "
            <div style='
                max-width: 400px; 
                margin: 50px auto; 
                padding: 15px; 
                background-color: #f8d7da; 
                color: #721c24; 
                border: 1px solid #f5c6cb; 
                border-radius: 5px; 
                text-align: center;
                font-size: 16px;
                font-family: Arial, sans-serif;
            '>
                <strong>Error!</strong> Failed to reset password.
                <br><a href='login.php' style='
    display: inline-block; 
    margin-top: 10px; 
    text-decoration: none; 
    color: white; 
    background-color: black; /* Changed to black */
    padding: 8px 12px; 
    border-radius: 5px;
'>Go to Login</a>

            </div>";
        }
    } else {
        echo "
        <div style='
            max-width: 400px; 
            margin: 50px auto; 
            padding: 15px; 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            border-radius: 5px; 
            text-align: center;
            font-size: 16px;
            font-family: Arial, sans-serif;
        '>
            <strong>Error!</strong> Invalid or expired token.
            <br><a href='login.php' style='
    display: inline-block; 
    margin-top: 10px; 
    text-decoration: none; 
    color: white; 
    background-color: black; /* Changed to black */
    padding: 8px 12px; 
    border-radius: 5px;
'>Go to Login</a>

        </div>";
    }
}
