<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$inData = getRequestInfo();

$id = (int) $inData["id"];
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
    if (empty($id) || empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($favoriteSpot) || $userId === null) {
        returnWithError("All fields are required.");
    }

    $stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName = ?, Email = ?, Phone = ?, FavoriteSpot = ? WHERE ID = ? AND UserID = ?");
    $stmt->bind_param("sssssii", $firstName, $lastName, $email, $phone, $favoriteSpot, $id, $userId);

    if (!$stmt->execute()) {
        returnWithError($stmt->error);
    } else if ($stmt->affected_rows > 0) {
        returnWithInfo($id, $firstName, $lastName, $email, $phone, $favoriteSpot);
    } else {
        returnWithError("No changes were made or contact not found.");
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
    $retValue = '{"id":0,"firstName":"","lastName":"","email":"","phone":"","favoriteSpot":"","error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($id, $firstName, $lastName, $email, $phone, $favoriteSpot)
{
    $retValue = '{"message":"Contact updated.","id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","email":"' . $email . '","phone":"' . $phone . '","favoriteSpot":"' . $favoriteSpot . '","error":""}';
    sendResultInfoAsJson($retValue);
}

?>