<?php

$inData = getRequestInfo();

$name = "";
$email = "";
$phone = "";

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    $stmt = $conn->prepare("SELECT Name,Email,Phone FROM Users WHERE Name=? AND Email=? AND Phone=?");
    $stmt->bind_param("sss", $inData["name"], $inData["email"], $inData["phone"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        returnWithInfo($row['name'], $row['email'], $row['phone']);
    } else {
        returnWithError("No Records Found");
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
    $retValue = '{"Name":' . $name . ',"Email":"' . $email . ',"Phone":"' . $phone . '","error":""}';
    sendResultInfoAsJson($retValue);
}
