<?php
require "dbbase.php"; // include database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);

    // Prepare secure SQL
    $sql = "SELECT * FROM users WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $message = "<p style='color:green;'>Login successful! Welcome.</p>";
            // You can redirect:
            // header("Location: dashboard.php");
            // exit;
        } else {
            $message = "<p style='color:red;'>Incorrect password.</p>";
        }
    } else {
        $message = "<p style='color:red;'>Phone number not found.</p>";
    }
}
?>