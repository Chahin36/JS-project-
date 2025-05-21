<?php
require 'db_connect.php';
header('Content-Type: application/json');

// Verify admin status
session_start();
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'message' => 'Unauthorized: Admin access required'
    ]));
}

// Validate input
$required = ['title', 'image_path', 'features'];
foreach($required as $field) {
    if(empty($_POST[$field])) {
        http_response_code(400);
        die(json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]));
    }
}

// Process features (convert newlines to <br> for database storage)
$features = str_replace("\n", "<br>", trim($_POST['features']));

try {
    $title = $_POST['title'];
$image_path = $_POST['image_path'];
$description = isset($_POST['description']) ? $_POST['description'] : '';
$features = str_replace("\n", "<br>", trim($_POST['features']));

$stmt = $conn->prepare("INSERT INTO pricing_items (title, image_path, description, features) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $image_path, $description, $features);

    
    if($stmt->execute()) {
        $new_id = $conn->insert_id;
        
        // Return the full new item data for frontend rendering
        $result = $conn->query("SELECT * FROM pricing_items WHERE id = $new_id");
        $new_item = $result->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'message' => 'Item added successfully',
            'item' => $new_item
        ]);
    } else {
        throw new Exception($conn->error);
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>