<?php

require_once __DIR__ . '/vendor/autoload.php';

// Use statements for imported classes
use PHPMailer\PHPMailer\PHPMailer;
use YourNamespace\YourClass; // Ensure the use statement is correct

try {
    // Test PHPMailer
    $mail = new PHPMailer();
    echo "PHPMailer initialized successfully.\n";

    // Test your custom class
    $yourClassInstance = new YourClass(); // This should work if everything is correct
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
