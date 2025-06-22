<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'vendor/autoload.php'; // Ensure this points to your autoload file

$host = 'localhost';
$db   = 'job_portal';
$user = 'root';
$pass = 'sultan1369';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $userCheck = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $userCheck->bind_param("s", $email);
    $userCheck->execute();
    $result = $userCheck->get_result();

    if ($result->num_rows > 0) {
        // Generate unique token
        $token = bin2hex(random_bytes(32));

        // Set expiration time to 1 hour from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert token into password_resets table with expiration
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires_at);
        $stmt->execute();


        // Send reset email
        $resetLink = "http://localhost/job_portal/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'skillbridge13@gmail.com'; // Your Gmail address
            $mail->Password = 'alqu ookc eyaa itcl'; // Use the generated App Password here
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('email@gmail.com', 'Skill Bridge');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
<div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;'>
    <h1 style='text-align: center; color: #333;'>Reset Your Password</h1>
    <p style='font-size: 16px; line-height: 1.6; color: #555;'>
        Hello, <br><br>
        We received a request to reset your password. Click on the button below to proceed with resetting your password.
    </p>
    <div style='text-align: center; margin: 20px 0;'>
        <a href='$resetLink' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;'>Reset Password</a>
    </div>
    <p style='font-size: 14px; color: #999;'>
        If you did not request this, please ignore this email.
    </p>
    <p style='font-size: 14px; color: #adff2f; text-align: center;'>
        © Skill Bridge.
    </p>
</div>";


            $mail->send();
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
        <strong>Success!</strong> Password reset email has been sent!
    </div>";
        } catch (Exception $e) {
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
        <strong>Error!</strong> Message could not be sent. Mailer Error: {$mail->ErrorInfo}
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
        <strong>Notice:</strong> No account found with this email.
    </div>";
    }
}
?>

<!-- Password Reset Request Form -->
<form action="send_reset.php" method="POST" style="
    max-width: 400px; 
    margin: 50px auto; 
    padding: 20px; 
    border: 1px solid #ddd; 
    border-radius: 10px; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
    background-color: #f9f9f9;
    position: relative;
">
    <!-- Back Arrow Button -->
    <button type="button" onclick="history.back();" style="
        background: none; 
        border: none; 
        font-size: 24px; 
        position: absolute; 
        top: 10px; 
        left: 10px; 
        cursor: pointer; 
    ">
        ←
    </button>

    <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Reset Password</h2>

    <input type="email" name="email" placeholder="Enter your email" required style="
        width: 100%; 
        padding: 10px; 
        margin-bottom: 15px; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
        font-size: 16px; 
        box-sizing: border-box;
    ">

    <button type="submit" style="
        width: 100%; 
        padding: 10px; 
        background-color: #007bff; 
        color: white; 
        border: none; 
        border-radius: 5px; 
        font-size: 16px; 
        cursor: pointer;
    ">
        Send Reset Link
    </button>
</form>