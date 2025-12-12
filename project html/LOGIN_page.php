<?php
// show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

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



// Get form data
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validation
if (empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
    $_SESSION['error'] = "Invalid phone number format.";
    header("Location: project%20html/loginpage.html");
    exit();
}

if (empty($password) || strlen($password) < 4) {
    $_SESSION['error'] = "Invalid password format.";
    header("Location: project%20html/loginpage.html");
    exit();
}

// Query database 
$sql = "SELECT * FROM users WHERE phone = '$phone' AND password = '$password'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Login successful
    $user = $result->fetch_assoc();
    $_SESSION["name"]= $user["name"];
    $_SESSION["phone"] = $user["phone"];
    session_write_close(); 
    header("Location: landingpage.html");
    exit();
} else {
    // Login failed
    echo "credentials not found, login or register";
   echo '<br><a href="loginpage.html">Go back to Login Page</a>';
   echo '<br><a href="REGISTRATION.html">Go to Registration Page</a>';
    exit();
}

$stmt->close();
$conn->close();
?>
