<?php
$host = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "multiple_sms"; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
