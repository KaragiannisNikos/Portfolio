<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all items in the user's cart
$stmt = $pdo->prepare('SELECT products.*, cart.quantity FROM cart JOIN products ON cart.item_id = products.item_id WHERE cart.user_id = ?');
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Handle quantity update or removal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Update the quantity
        foreach ($_POST['quantities'] as $item_id => $quantity) {
            $stmt = $pdo->prepare('UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?');
            $stmt->execute([$quantity, $user_id, $item_id]);
        }
    } elseif (isset($_POST['remove'])) {
        // Remove the item from the cart
        $item_id = $_POST['item_id'];
        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ? AND item_id = ?');
        $stmt->execute([$user_id, $item_id]);
    }
    header('Location: cart.php');
    exit();
}

include('includes/header.php');
?>

<div class="container mx-auto p-8">
      <h2 class="text-3xl font-bold text-center text-white mb-6">Your Cart</h2>
    <?php if ($cart_items): ?>
        <form method="POST" action="cart.php">
            <table class="w-full bg-gray-800 rounded-lg text-white">
                <thead>
                    <tr>
                        <th class="py-3 px-4">Product</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Quantity</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr class="border-b border-gray-700">
                            <td class="py-4 px-4"><?= htmlspecialchars($item['item_name']); ?></td>
                            <td class="py-4 px-4">$<?= number_format($item['item_price'], 2); ?></td>
                            <td class="py-4 px-4">
                                <input type="number" name="quantities[<?= $item['item_id']; ?>]" value="<?= $item['quantity']; ?>" min="1" class="w-16 bg-gray-900 border border-gray-700 text-gray-200 rounded-lg text-center">
                            </td>
                            <td class="py-4 px-4">$<?= number_format($item['item_price'] * $item['quantity'], 2); ?></td>
                            <td class="py-4 px-4">
                                <button type="submit" name="remove" value="<?= $item['item_id']; ?>" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">Remove</button>
                                <input type="hidden" name="item_id" value="<?= $item['item_id']; ?>">
                            </td>
                        </tr>
                        <?php $total += $item['item_price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-right py-4 px-4 font-bold">Total:</td>
                        <td class="py-4 px-4 font-bold">$<?= number_format($total, 2); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-6 flex justify-end">
                <button type="submit" name="update" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-6 rounded-lg mr-4">Update Quantities</button>
                <a href="checkout.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg">Proceed to Checkout</a>
            </div>
        </form>
    <?php else: ?>
        <p class="text-center text-gray-400">Your cart is empty.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
