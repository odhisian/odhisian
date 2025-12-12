<?php
// show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname   = "easy_prompt";
$port     = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}
echo "Connected successfully<br>";

// FIXED: Use prepared statements and proper syntax
$name = "lencer";
$user_id = NULL;
$phone = "0742307593";
$password = "12345";
$email = "angukajsjs@gmail.com";

$sql = "INSERT INTO users (name, user_id, phone, password, email) VALUES (?, ?, ?, ?, ?)";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisss", $name, $user_id, $phone, $password, $email);

if ($stmt->execute()) {
    echo "Record inserted successfully<br>";
    $last_id = $conn->insert_id;
  echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$sql = "SELECT name, phone, email FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "NAME: " . $row["name"]. " - Phone: " . $row["phone"]. " email" . $row["email"]. "<br>";
  }
} else {
  echo "0 results";
}

$sql="SELECT * FROM users WHERE phone='0742307593' AND password='12345'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "NAME: " . $row["name"]. " - Phone: " . $row["phone"]. " email" . $row["email"]. "<br>";
  }
} else {
  echo "0 results";
}

$stmt->close();
$conn->close();
?>