<?php
// search_contact.php

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

$results = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    // Sanitize input
    $query = htmlspecialchars(trim($_GET['query']));

    // Check if query is empty
    if (!empty($query)) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("SELECT id, name, email, phone FROM contacts WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?");
        $likeQuery = "%$query%";
        $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        } else {
            $message = "No contacts found.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $message = "Please enter a search query.";
    }
}

$conn->close();
