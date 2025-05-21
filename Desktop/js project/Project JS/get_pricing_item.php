<?php
require 'db_connect.php';
header('Content-Type: application/json');

// Verify admin status
session_start();
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die(json_encode(['error' => 'Unauthorized']));
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM pricing_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'Item not found']);
}
?>