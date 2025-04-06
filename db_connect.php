<?php
$host = 'localhost';
$user = 'root';
$password = 'v3j17041';
$database = 'freelancer_platform';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>