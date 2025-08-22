<?php
$host     = 'localhost';
$user     = 'root';      // update with your DB username
$password = 'root';      // update with your DB password
$dbname   = 'uupcs';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
