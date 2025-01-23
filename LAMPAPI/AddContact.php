<?php

$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$email = $inData["email"];
$phone = $inData["phone"];

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT into Contacts(FirstName, LastName, Email,Phone) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $phone);
    $stmt->execute();
    http_response_code(200);

    $searchResutls = json_encode(["Contact Added" => "$firstName $lastName", "Phone" => $phone, "Email" => $email]);

    $stmt->close();
    $conn->close();
    sendResultInfoAsJson($searchResutls);
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
    $retValue = '{"Name":' . $name . ',"Email":"' . $email . ',"Phone":"' . $phone . '","error":""}';
    sendResultInfoAsJson($retValue);
}
