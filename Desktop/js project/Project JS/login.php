<?php
// Start the session
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "app";

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email_username = mysqli_real_escape_string($conn, $_POST['email_username']);
    $user_password = $_POST['password'];

    // Query to find user by email OR username
    $sql = "SELECT * FROM users WHERE email='$email_username' OR full_name='$email_username'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($user_password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];

            header("Location: index.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email or username.";
    }
}

mysqli_close($conn);
?>