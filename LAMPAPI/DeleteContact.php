<?php

$inData = getRequestInfo();

$contactId = $inData["id"];
$userId = $inData["userId"];

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

    if (empty($contactId) || empty($userId)) {
        returnWithError("All fields are required.");
    }

    // Query the user first so we can return the info on a successful deletion
    $selectStmt = $conn->prepare("SELECT FirstName, LastName, Email, Phone, FavoriteSpot FROM Contacts WHERE ID = ? AND UserID = ?");
    $selectStmt->bind_param("ii", $contactId, $userId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $firstName = $row["FirstName"];
        $lastName = $row["LastName"];
        $email = $row["Email"];
        $phone = $row["Phone"];
        $favoriteSpot = $row["FavoriteSpot"];

        $deleteStmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ? AND UserID = ?");
        $deleteStmt->bind_param("ii", $contactId, $userId);

        if (!$deleteStmt->execute()) {
            returnWithError($deleteStmt->error);
        } else if ($deleteStmt->affected_rows > 0) {
            returnWithInfo($firstName, $lastName, $email, $phone, $favoriteSpot);
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
    $retValue = '{"id":0,"firstName":"","lastName":"","email":"","phone":"","favoriteSpot":"","error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($firstName, $lastName, $email, $phone, $favoriteSpot)
{
    $retValue = '{"message":"Contact deleted.","firstName":"' . $firstName . '","lastName":"' . $lastName . '","email":"' . $email . '","phone":"' . $phone . '","favoriteSpot":"' . $favoriteSpot . '","error":""}';
    sendResultInfoAsJson($retValue);
}
?>