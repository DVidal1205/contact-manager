<?php

$inData = getRequestInfo();

$name = "";
$email = "";
$phone = "";

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO Contacts (Name, Email, Phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $inData["name"], $inData["email"], $inData["phone"]);

    if ($stmt->execute()) {
        returnWithInfo($row['name'], $row['email'], $row['phone']);
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
    $retValue = '{"An error occurred!":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($name, $email, $phone)
{
    $retValue = '{"Contact successfully created. It is -> Name":"' . $name . '","Email":"' . $email . '","Phone":"' . $phone . '","error":""}';
    sendResultInfoAsJson($retValue);
}
