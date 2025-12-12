<?php
session_start();
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

$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password_input = isset($_POST['password']) ? $_POST['password'] : '';
$user_id = NULL;

// Validate inputs
if (empty($fullname) || empty($email) || empty($phone) || empty($password_input)) {
    echo "All fields are required.<br>";
    exit();
}

// Insert into database
$sql = "INSERT INTO users (name, user_id, phone, password, email) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param("sisss", $fullname, $user_id, $phone, $password_input, $email);

if ($stmt->execute()) {
    echo "Record inserted successfully<br>";
    $last_id = $conn->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id . "<br>";
    echo "<a href='loginpage.html'>Click here to login</a><br>";
} else {
    echo "Error: " . htmlspecialchars($stmt->error) . "<br>";
}

// Select and display all users
echo "<h3>All Users:</h3>";
$sql = "SELECT name, phone, email FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "NAME: " . htmlspecialchars($row["name"]) . " - Phone: " . htmlspecialchars($row["phone"]) . " - Email: " . htmlspecialchars($row["email"]) . "<br>";
    }
} else {
    echo "0 results<br>";
}

// Search for specific user - FIXED: use prepared statement to prevent SQL injection
echo "<h3>Search User:</h3>";
if (!empty($phone) && !empty($password_input)) {
    $sql = "SELECT * FROM users WHERE phone = ? AND password = ?";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("ss", $phone, $password_input);
    $stmt2->execute();
    $result = $stmt2->get_result();
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "NAME: " . htmlspecialchars($row["name"]) . " - Phone: " . htmlspecialchars($row["phone"]) . " - Email: " . htmlspecialchars($row["email"]) . "<br>";
        }
    } else {
        echo "0 results<br>";
    }
    $stmt2->close();
}

$stmt->close();
$conn->close();
?>