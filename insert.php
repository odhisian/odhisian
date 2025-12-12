<?php

// Show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easy_prompt";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}
echo "Connected successfully<br>";

// Get form data 
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$id=null
// Validation
$errors = array();

if (empty($fullname)) {
    $errors[] = "Name is required.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required.";
}

if (empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
    $errors[] = "Phone number must be 10-15 digits.";
}

if (empty($password) || strlen($password) < 4) {
    $errors[] = "Password must be at least 4 characters.";
}

// If validation fails, redirect back with errors
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: homepage.php");
    exit();
}

// Check if phone already exists
$checkStmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
$checkStmt->bind_param("s", $phone);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $_SESSION['error'] = "Phone number already registered.";
    header("Location: homepage.php");
    exit();
}

// Insert into database

$sql = "INSERT INTO users (name, user_id, phone, password, email) VALUES (?, ?, ?, ?, ?)";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisss", $fullname, $id, $phone, $password, $email);

if ($stmt->execute()) {
    echo "Record inserted successfully<br>";
    $last_id = $conn->insert_id;
  echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$insertStmt = $conn->prepare("INSERT INTO users (name, user_id, phone, password, email) VALUES (?,?, ?, ?, ?)");
$insertStmt->bind_param("sisss", $fullname,$id, $email, $phone, $password);

if ($insertStmt->execute()) {
    $_SESSION['success'] = "Registration successful! Please login.";
    header("Location: loginpage.php");
    exit();
} else {
    $_SESSION['error'] = "Error: " . htmlspecialchars($conn->error);
    header("Location: homepage.php");
    exit();
}

$insertStmt->close();
$checkStmt->close();
$conn->close();
?>