<?php
session_start();  // Start the session

if (!isset($_GET['format'])) {
    die("Format not specified.");
}

$format = $_GET['format'];
$user_id = $_SESSION['user_id'];  // Get the logged-in user ID

// Fetch resume data (similar to what you did in the main page)
$host = 'localhost';
$db = 'job_portal';
$user = 'root';
$password = 'sultan1369';
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$resumeQuery = "SELECT * FROM resumes WHERE user_id = ?";
$resumeStmt = $conn->prepare($resumeQuery);
$resumeStmt->bind_param("i", $user_id);
$resumeStmt->execute();
$resumeResult = $resumeStmt->get_result();

if ($resumeResult->num_rows > 0) {
    $resumeData = $resumeResult->fetch_assoc();
} else {
    die("No resume found.");
}

// Generate the resume based on format
if ($format == 'jpg' || $format == 'png') {
    // Create an image using PHP GD library
    $width = 600;  // Set image width
    $height = 800; // Set image height
    $image = imagecreatetruecolor($width, $height);

    // Set background color (white)
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $bgColor);

    // Set text color (black)
    $textColor = imagecolorallocate($image, 0, 0, 0);

    // Add the resume details to the image
    $fontPath = 'path/to/your/font.ttf';  // Use a TTF font file path
    $fontSize = 12;
    $y = 20;

    // Name
    imagettftext($image, $fontSize, 0, 10, $y, $textColor, $fontPath, 'Name: ' . $resumeData['first_name'] . ' ' . $resumeData['last_name']);
    $y += 30;

    // Summary
    imagettftext($image, $fontSize, 0, 10, $y, $textColor, $fontPath, 'Summary: ' . nl2br($resumeData['summary']));
    $y += 30;

    // Output the image to the browser
    header('Content-Type: image/' . $format);
    if ($format == 'jpg') {
        imagejpeg($image);  // Output JPG image
    } elseif ($format == 'png') {
        imagepng($image);  // Output PNG image
    }

    // Clean up
    imagedestroy($image);
}

$conn->close();
