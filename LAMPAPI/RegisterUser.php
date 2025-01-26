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
} else {
    $stmt = $conn->prepare("INSERT into Users(firstName, lastName, Login, Password) VALUES (?,?,?,?)");
    $stmt->bind_param(
        "ssss",
        $inData["firstName"],
        $inData["lastName"],
        $inData["login"],
        $inData["password"]
    );

    if (!$stmt->execute()) {
        returnWithError($stmt->error);
        $stmt->close();
        $conn->close();
        exit();
    }

    if ($stmt->affected_rows > 0) {
        $newUserId = $conn->insert_id;

        returnWithInfo($inData["firstName"], $inData["lastName"], $newUserId);
    } else {
        returnWithError("User not Registered");
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

function returnWithInfo($firstName, $lastName, $id)
{
    $retValue = '{"message":"User registered.","id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
    sendResultInfoAsJson($retValue);
}
?>