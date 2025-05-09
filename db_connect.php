<?php
$host = "localhost";
$username = "root";
$password = "";  // Default XAMPP MySQL password is empty
$database = "app";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to utf8
$conn->set_charset("utf8");
?>
