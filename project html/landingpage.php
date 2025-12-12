<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: LOGIN_page.php");
    exit();
}
$phone = htmlspecialchars($_SESSION["phone"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Landing Page</title>
</head>
<body>
  <h1>Welcome</h1>
  <p>Logged in as: <?php echo $phone; ?></p>
  <p><a href="logout.php">Logout</a></p>
</body>
</html>