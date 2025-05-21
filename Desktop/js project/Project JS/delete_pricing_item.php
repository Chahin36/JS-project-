<?php
require 'db_connect.php';
header('Content-Type: application/json');

// Start session and verify admin status
session_start();
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'message' => 'Unauthorized: Admin access required'
    ]));
}

// Validate input
if(!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'message' => 'Invalid item ID'
    ]));
}

$id = intval($_POST['id']);

try {
    // Check if item exists first
    $check = $conn->prepare("SELECT id FROM pricing_items WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    
    if($check->get_result()->num_rows === 0) {
        http_response_code(404);
        die(json_encode([
            'success' => false,
            'message' => 'Item not found'
        ]));
    }

    // Proceed with deletion
    $stmt = $conn->prepare("DELETE FROM pricing_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Item deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $conn->error
        ]);
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>