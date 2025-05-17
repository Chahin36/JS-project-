<?php
require 'db_connect.php';
header('Content-Type: application/json');

// Verify admin status
session_start();
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$id = intval($_POST['id']);
$stmt = $conn->prepare("DELETE FROM pricing_items WHERE id = ?");
$stmt->bind_param("i", $id);

if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>