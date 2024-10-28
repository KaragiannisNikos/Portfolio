<?php
include('db.php');
session_start();

// Handle Add to Cart action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = 1; // Add one item at a time

    // Check if the product is already in the cart
    $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = ? AND item_id = ?');
    $stmt->execute([$user_id, $item_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        // If the item is already in the cart, increase the quantity
        $stmt = $pdo->prepare('UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND item_id = ?');
        $stmt->execute([$user_id, $item_id]);
    } else {
        // If the item is not in the cart, add it with quantity 1
        $stmt = $pdo->prepare('INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, 1)');
        $stmt->execute([$user_id, $item_id]);
    }

    // Reload the current page to reflect the updated cart count
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit();
}

// Fetch all products from the database
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
$logged_in = isset($_SESSION['user_id']); // Check if the user is logged in
?>

<?php include('includes/header.php'); ?>

<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold text-center text-white mb-8">Explore Our Products</h1>
<!-- Show Add Product button if the user is logged in -->
<?php if ($logged_in): ?>
        <div class="flex justify-center mb-8">
            <a href="add_product.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg font-bold">Add New Product</a>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <?php foreach ($products as $product): ?>
            <div class="bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <!-- Product Image -->
                <img src="<?= $product['image_path'] ?: 'https://source.unsplash.com/random/400x300?product,' . urlencode($product['item_name']); ?>" 
                     alt="<?= $product['item_name']; ?>" 
                     class="w-full h-48 object-cover mb-4 rounded-lg">

                <!-- Product Details -->
                <h3 class="text-2xl font-bold text-gray-300 mb-2"><?= htmlspecialchars($product['item_name']); ?></h3>
                <p class="text-xl font-bold text-purple-400 mb-2">$<?= htmlspecialchars($product['item_price']); ?></p>

                <!-- If user is logged in and does NOT own the product, show Add to Cart -->
                <?php if ($logged_in && $_SESSION['user_id'] != $product['user_id']): ?>
                    <form method="POST" action="<?= $_SERVER['REQUEST_URI']; ?>">
                        <input type="hidden" name="item_id" value="<?= $product['item_id']; ?>">
                        <input type="hidden" name="add_to_cart" value="1">
                        <button type="submit" class="bg-purple-600 py-2 px-4 rounded-lg hover:bg-purple-700 text-white">
                            Add to Cart
                        </button>
                    </form>
                <?php elseif ($logged_in && $_SESSION['user_id'] == $product['user_id']): ?>
                    <!-- Edit/Delete Buttons for User's Own Products -->
                    <div class="mt-4 flex justify-between">
                        <a href="edit_product.php?item_id=<?= $product['item_id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Edit</a>
                        <a href="delete_product.php?item_id=<?= $product['item_id']; ?>" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
