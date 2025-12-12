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

$paybll=isset($_POST['paybill']) ? trim($_POST['paybill']) : '';;
$accNo=isset($_POST['accnumber']) ? trim($_POST['accnumber']) : '';;
$amount=isset($_POST['cash']) ? trim($_POST['cash']) : '';;
$tid=NULL;
$sql = "INSERT INTO PAYBILL (paybill_id, paybill_No, payroll_Acc, Amount) VALUES (?, ?, ?, ?);";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $tid,$paybll,$accNo,$amount);

if ($stmt->execute()) {
    echo "Record inserted successfully<br>";
    $last_id = $conn->insert_id;
  echo "New record created successfully. Last inserted ID is: " . $last_id;
  echo "click this link to go back to home page <a href='landingpage.html'>HOME PAGE</a><br>";
    echo "click this link to make another transaction <a href='paybill.html'>PAY BILL</a><br>";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}



$stmt->close();
$conn->close();
?>