<?php
require 'db_connect.php';
header('Content-Type: application/json');

// Verify admin status
session_start();
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$id = intval($_POST['id']);
$title = $_POST['title'];
$image_path = $_POST['image_path'];
$description = $_POST['description'];
$features = $_POST['features'];

if($id == 0) {
    // Insert new item
    $stmt = $conn->prepare("INSERT INTO pricing_items (title, image_path, description, features) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $image_path, $description, $features);
} else {
    // Update existing item
    $stmt = $conn->prepare("UPDATE pricing_items SET title=?, image_path=?, description=?, features=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $image_path, $description, $features, $id);
}

if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>