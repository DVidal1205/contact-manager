<?php

$inData = getRequestInfo();

$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$email = $inData["email"];
$phone = $inData["phone"];
$favoriteSpot = $inData["favoriteSpot"];
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
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($favoriteSpot) || $userId == null) {
        returnWithError("All fields are required.");
    }

    $stmt = $conn->prepare("INSERT into Contacts(FirstName, LastName, Email, Phone, FavoriteSpot, UserID) VALUES (?,?,?,?,?, ?)");
    $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phone, $favoriteSpot, $userId);

    if (!$stmt->execute()) {
        returnWithError($stmt->error);
    } else {
        $newId = $conn->insert_id;
        $stmt->close();
        $conn->close();
        returnWithInfo($newId, "$firstName $lastName", $email, $phone, $favoriteSpot);
    }
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

function returnWithInfo($id, $name, $email, $phone, $favoriteSpot)
{
    $retValue = '{"message":"Contact added.","id":' . $id . ',"name":"' . $name . '","email":"' . $email . '","phone":"' . $phone . '","favoriteSpot":"' . $favoriteSpot . '","error":""}';
    sendResultInfoAsJson($retValue);
}
?>