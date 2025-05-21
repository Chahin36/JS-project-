<?php
session_start();
// Verify admin status
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}

include 'db_connect.php';

$user_id = intval($_GET['id']);

// Prevent self-demotion
if($user_id == $_SESSION['user_id']) {
    header("Location: admin_panel.php?error=self");
    exit();
}

// Toggle admin status
$conn->query("UPDATE users SET is_admin = NOT is_admin WHERE id = $user_id");

header("Location: admin_panel.php?success=1");
exit();
?>