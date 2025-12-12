<?php
session_start();
?>
<?php
echo"values printed are". $_SESSION["name"]."<br>";
echo"next is phone number". $_SESSION["phone"];
?>