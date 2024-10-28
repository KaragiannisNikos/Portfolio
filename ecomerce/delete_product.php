<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$item_id = $_GET['item_id'];

// Delete the product only if it belongs to the logged-in user
$stmt = $pdo->prepare('DELETE FROM products WHERE item_id = ? AND user_id = ?');
if ($stmt->execute([$item_id, $_SESSION['user_id']])) {
    header('Location: index.php?message=Product deleted successfully');
} else {
    header('Location: index.php?error=Failed to delete product');
}
exit();
