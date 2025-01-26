<?php

$inData = getRequestInfo();

$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$email = $inData["email"];
$phone = $inData["phone"];

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
    $stmt = $conn->prepare("INSERT into Contacts(FirstName, LastName, Email, Phone) VALUES (?,?,?,?)");
    // Bind the parameters correctly
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $phone);

    // Try executing and handle any errors
    if (!$stmt->execute()) {
        returnWithError($stmt->error);
    } else {
        $stmt->close();
        $conn->close();
        returnWithInfo("$firstName $lastName", $email, $phone);
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
    $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($name, $email, $phone)
{
    $retValue = '{"Name":' . $name . ',"Email":"' . $email . '","Phone":"' . $phone . '","error":""}';
    sendResultInfoAsJson($retValue);
}
