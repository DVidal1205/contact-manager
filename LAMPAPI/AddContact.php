<?php

$inData = getRequestInfo();

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO Contacts (Name, Email, Phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $inData["name"], $inData["email"], $inData["phone"]);

    if ($stmt->execute()) {
        returnWithSuccess("Contact added successfully!");
    } else {
        returnWithError("Failed to add contact: " . $stmt->error);
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
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithSuccess($message)
{
    $retValue = '{"message":"' . $message . '","error":""}';
    sendResultInfoAsJson($retValue);
}
