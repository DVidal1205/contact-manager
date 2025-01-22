<?php
// add_contact.php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "WeLoveCOP4331";
$dbname = "TheBeast";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    // Check if fields are empty
    if (empty($name) || empty($email) || empty($phone)) {
        echo "All fields are required.";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Contact added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
