<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u691586039_interior";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error. Please try again later.");
}

// Set charset
$conn->set_charset("utf8mb4");
?>
