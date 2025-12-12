<?php
session_start();
// show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection settings
$servername = "localhost";
$username = "root";       
$password = "";  
$dbname = "easy_prompt";         
$port     = 3307;

// Create connection - pass port as 5th parameter, no DB name for creating database
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

$phoneno=isset($_POST['phone1']) ? trim($_POST['phone1']) : '';;
$amount=isset($_POST['amount1']) ? trim($_POST['amount1']) : '';;
$tid=NULL;

//validate inputs
if (empty($phoneno) || empty($amount)) {
    echo "All fields are required.<br>";
    exit();
}
//insert into database
$sql = "INSERT INTO sendmoney (transaction_id, phone_No, amount) VALUES (?, ?, ?);";

// Prepare and bind
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("iss", $tid,$phoneno,$amount);

if ($stmt->execute()) {
    echo "Record inserted successfully<br>";
    echo "click this link to go back to home page <a href='landingpage.html'>HOME PAGE</a><br>";
    $last_id = $conn->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id . "<br>";
    echo"click this link to make another transaction <a href='sendmoney.html'>SEND MONEY</a><br>";
} else {
    echo "Error: " . htmlspecialchars($stmt->error) . "<br>";
}




$stmt->close();
$conn->close();
?>