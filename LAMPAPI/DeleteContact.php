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

    // Query the user first so we can return the info on a successful deletion
    $selectStmt = $conn->prepare("SELECT FirstName, LastName, Email, Phone FROM Contacts WHERE ID = ?");
    $selectStmt->bind_param("i", $contactId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $firstName = $row["FirstName"];
        $lastName = $row["LastName"];
        $email = $row["Email"];
        $phone = $row["Phone"];

        $deleteStmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
        $deleteStmt->bind_param("i", $contactId);

        if (!$deleteStmt->execute()) {
            returnWithError($deleteStmt->error);
        } else if ($deleteStmt->affected_rows > 0) {
            returnWithInfo($firstName, $lastName, $email, $phone);
        } else {
            returnWithError("No Contact Found");
        }

        $deleteStmt->close();
    } else {
        returnWithError("No Contact Found");
    }

    $selectStmt->close();
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

function returnWithInfo($firstName, $lastName, $email, $phone)
{
    $retValue = '{"firstName":"' . $firstName . '","lastName":"' . $lastName . '","email":"' . $email . '","phone":"' . $phone . '","error":""}';
    sendResultInfoAsJson($retValue);
}
?>