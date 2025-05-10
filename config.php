<?php
$host = "127.0.0.1";
$user = "storeuser";
$pass = "UserPass!";
$db   = "store";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    exit("Connection failed: " . $conn->connect_error);
}
?>
