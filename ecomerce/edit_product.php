<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$item_id = $_GET['item_id'];
$error = '';
$success = '';

// Fetch product data for the user to edit
$stmt = $pdo->prepare('SELECT * FROM products WHERE item_id = ? AND user_id = ?');
$stmt->execute([$item_id, $_SESSION['user_id']]);
$product = $stmt->fetch();

if (!$product) {
    $error = 'Product not found or you don’t have permission to edit it.';
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $item_name = $_POST['item_name'];
        $item_desc = $_POST['item_desc'];
        $item_price = $_POST['item_price'];

        // Update product in the database
        $stmt = $pdo->prepare('UPDATE products SET item_name = ?, item_desc = ?, item_price = ? WHERE item_id = ? AND user_id = ?');
        if ($stmt->execute([$item_name, $item_desc, $item_price, $item_id, $_SESSION['user_id']])) {
            $success = 'Product updated successfully!';
        } else {
            $error = 'Failed to update product.';
        }
    }
}

include('includes/header.php');
?>

<div class="container mx-auto p-8">
    <h2 class="text-3xl font-bold text-center mb-6 text-white">Edit Product</h2>

    <?php if ($error): ?>
        <div class="bg-red-600 text-white p-3 rounded mb-4"><?= $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-3 rounded mb-4"><?= $success; ?></div>
    <?php endif; ?>

    <?php if ($product): ?>
        <form method="POST" class="max-w-lg mx-auto bg-gray-800 p-6 rounded-lg">
            <div class="mb-4">
                <label for="item_name" class="block text-gray-300">Product Name</label>
                <input type="text" name="item_name" id="item_name" value="<?= htmlspecialchars($product['item_name']); ?>" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
            </div>
            <div class="mb-4">
                <label for="item_desc" class="block text-gray-300">Description</label>
                <textarea name="item_desc" id="item_desc" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required><?= htmlspecialchars($product['item_desc']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="item_price" class="block text-gray-300">Price</label>
                <input type="number" name="item_price" id="item_price" value="<?= htmlspecialchars($product['item_price']); ?>" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
            </div>
            <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg">Update Product</button>
        </form>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
