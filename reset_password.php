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
    </div>");
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid
        echo "
        <form action='update_password.php' method='POST' style='
            max-width: 400px; 
            margin: 50px auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            background-color: #f9f9f9; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            font-family: Arial, sans-serif;
        '>
            <h2 style='text-align: center; color: #333; margin-bottom: 20px;'>Reset Password</h2>
            <input type='hidden' name='token' value='$token'>
            <input type='password' name='password' placeholder='Enter new password' required style='
                width: 100%; 
                padding: 10px; 
                margin-bottom: 15px; 
                border: 1px solid #ddd; 
                border-radius: 5px; 
                font-size: 16px; 
                box-sizing: border-box;
            '>
            <button type='submit' style='
                width: 100%; 
                padding: 10px; 
                background-color: #007bff; 
                color: white; 
                border: none; 
                border-radius: 5px; 
                font-size: 16px; 
                cursor: pointer;
            '>Reset Password</button>
        </form>";
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
        </div>";
    }
} else {
    echo "
    <div style='
        max-width: 400px; 
        margin: 50px auto; 
        padding: 15px; 
        background-color: #fff3cd; 
        color: #856404; 
        border: 1px solid #ffeeba; 
        border-radius: 5px; 
        text-align: center;
        font-size: 16px;
        font-family: Arial, sans-serif;
    '>
        <strong>Notice:</strong> No token provided.
    </div>";
}
