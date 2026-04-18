<?php
$servername = "localhost";
$username = "u691586039_interior";
$password = "Interiors@1234"; // change this
$dbname = "u691586039_interior";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error. Please try again later.");
}

// Set charset
$conn->set_charset("utf8mb4");

// Helper function for safe output
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>