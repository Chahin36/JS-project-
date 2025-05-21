<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "app";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is set
    if (!isset($_POST['email'])) {
        die(json_encode(['status' => 'error', 'message' => 'Email field is missing']));
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid email format']));
    }

    try {
        // Check if email exists
        $check = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            die(json_encode(['status' => 'exists', 'message' => 'You are already registered']));
        }
        
        // Insert new subscriber
        $stmt = $conn->prepare("INSERT INTO subscribers (email, subscription_date) VALUES (?, NOW())");
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you for your registration, you will not miss any updates.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again.']);
        }
    } catch (Exception $e) {
        error_log("Subscription error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
    } finally {
        if (isset($check)) $check->close();
        if (isset($stmt)) $stmt->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>