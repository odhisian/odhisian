<?php
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

$phone=isset($_POST['1phone']) ? trim($_POST['1phone']) : '';;
$amount=isset($_POST['1amount']) ? trim($_POST['1amount']) : '';;
$tid=NULL;
$sql = "INSERT INTO sendmoney (transaction_id, phone_No, amount) VALUES (?, ?,?);";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $tid,$phone, $amount);

if ($stmt->execute()) {
    echo "Record inserted successfully<br>";
    $last_id = $conn->insert_id;
  echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}



$stmt->close();
$conn->close();
?>