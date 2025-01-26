<?php

$inData = getRequestInfo();

// Database configuration
$servername = "localhost";
$username = "TheBeast";
$password = "WeLoveCOP4331";
$dbname = "COP4331";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
    exit();
} else {

    $search = isset($inData["search"]) ? trim($inData["search"]) : "";

    if ($search === "") {
        $stmt = $conn->prepare("SELECT ID, FirstName, LastName, Email, Phone FROM Contacts");
    } else {
        $stmt = $conn->prepare(
            "SELECT ID, FirstName, LastName, Email, Phone
         FROM Contacts
         WHERE FirstName LIKE ?
            OR LastName LIKE ?
            OR Email LIKE ?
            OR Phone LIKE ?"
        );
        $likeSearch = "%" . $search . "%";
        $stmt->bind_param("ssss", $likeSearch, $likeSearch, $likeSearch, $likeSearch);
    }

    if (!$stmt->execute()) {
        returnWithError($stmt->error);
        $stmt->close();
        $conn->close();
        exit();
    }

    $result = $stmt->get_result();

    $contacts = array();
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }

    $stmt->close();
    $conn->close();

    returnWithInfo($contacts);
}


function getRequestInfo()
{
    // Read raw JSON from input and decode into associative array
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError($err)
{
    $retValue = '{"results":[],"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($contacts)
{
    $retValue = '{"message":"Contacts queried.","results":' . json_encode($contacts) . ',"error":""}';
    sendResultInfoAsJson($retValue);
}

?>