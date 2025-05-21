<?php
// Start the session
session_start();

// Include database connection
require 'db_connect.php';

// Get user input from POST request
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the insert statement
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password, is_admin) VALUES (?, ?, ?, 0)");
$stmt->bind_param("sss", $full_name, $email, $hashed_password);

if ($stmt->execute()) {
    // Registration successful - now log the user in
    $user_id = $stmt->insert_id; // Get the ID of the newly registered user
    
    // Set session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['full_name'] = $full_name;
    $_SESSION['email'] = $email;
    
    // Redirect to home page
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>