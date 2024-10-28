<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the cart items for the user
$stmt = $pdo->prepare('SELECT products.*, cart.quantity FROM cart JOIN products ON cart.item_id = products.item_id WHERE cart.user_id = ?');
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Calculate the total price
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['item_price'] * $item['quantity'];
}

// Handle the checkout process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert the order into the orders table
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total) VALUES (?, ?)');
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    // Insert each product into the order_contents table
    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare('INSERT INTO order_contents (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)');
        $stmt->execute([$order_id, $item['item_id'], $item['quantity'], $item['item_price']]);
    }

    // Clear the cart after successful checkout
    $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
    $stmt->execute([$user_id]);

    header('Location: index.php?checkout=success');
    exit();
}

include('includes/header.php');
?>

<div class="container mx-auto p-8">
    <h2 class="text-3xl font-bold text-center text-gray-300 mb-6">Checkout</h2>

    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <p class="text-lg font-bold text-gray-300 mb-4">Total: $<?= number_format($total, 2); ?></p>

        <form method="POST" action="checkout.php">
            <button type="submit" class="w-full bg-purple-600 py-2 rounded-lg hover:bg-purple-700 text-white">Confirm Purchase</button>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
