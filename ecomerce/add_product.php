<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $item_desc = $_POST['item_desc'];
    $item_price = $_POST['item_price'];
    $user_id = $_SESSION['user_id']; // The ID of the logged-in user

    // Optional: Image Upload handling (you can skip this if you want to handle images differently)
    $image_path = 'images/' . basename($_FILES['item_img']['name']);
    if (move_uploaded_file($_FILES['item_img']['tmp_name'], $image_path)) {
        // Insert product into the database
        $stmt = $pdo->prepare('INSERT INTO products (item_name, item_desc, item_price, image_path, user_id) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$item_name, $item_desc, $item_price, $image_path, $user_id])) {
            $success = 'Product added successfully!';
        } else {
            $error = 'Failed to add product.';
        }
    } else {
        $error = 'Failed to upload image.';
    }
}

include('includes/header.php');
?>

<div class="container mx-auto p-8">
    <h2 class="text-3xl font-bold text-center mb-6 text-white">Add New Product</h2>

    <?php if ($error): ?>
        <div class="bg-red-600 text-white p-3 rounded mb-4"><?= $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-3 rounded mb-4"><?= $success; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label for="item_name" class="block text-gray-300">Product Name</label>
            <input type="text" name="item_name" id="item_name" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
        </div>
        <div class="mb-4">
            <label for="item_desc" class="block text-gray-300">Description</label>
            <textarea name="item_desc" id="item_desc" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required></textarea>
        </div>
        <div class="mb-4">
            <label for="item_price" class="block text-gray-300">Price</label>
            <input type="number" name="item_price" id="item_price" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
        </div>
        <div class="mb-6">
            <label for="item_img" class="block text-gray-300">Product Image</label>
            <input type="file" name="item_img" id="item_img" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200">
        </div>
        <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg">Add Product</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
