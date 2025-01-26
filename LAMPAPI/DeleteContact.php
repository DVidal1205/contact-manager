<?php

$inData = getRequestInfo();

$contactId = $inData["id"];

// Database configuration
$servername = "localhost";
$username = "TheBeast";
$password = "WeLoveCOP4331";
$dbname = "COP4331";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    $stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
    $stmt->bind_param("i", $contactId);

    if (!$stmt->execute()) {
        returnWithError($stmt->error);
    } else if ($stmt->affected_rows > 0) {
        returnWithInfo($contactId);
    } else {
        returnWithError("No Contact Found");
    }

    $stmt->close();
    $conn->close();
}

function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError($err)
{
    $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($ID)
{
    $retValue = '{"contactDeleted":' . $ID . ',"error":""}';
    sendResultInfoAsJson($retValue);
}
?>