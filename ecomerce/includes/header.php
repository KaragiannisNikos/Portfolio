<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Fetch the cart count from the database
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT SUM(quantity) as total_quantity FROM cart WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $cart_count = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.0/dist/full.css" rel="stylesheet">
    <script>
        const path = window.location.pathname;
        // show pathName to title

        document.title = path === '/' ? 'Home' : path.replace('/', '').replace('.php', '').replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    </script>
</head>

<body class="bg-gray-900 text-gray-200">

    <header class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" role="button">
                <h1 class="text-xl font-bold text-white">My E-Commerce Site</h1>
            </a>
            <nav class="flex space-x-4">
                <a href="index.php" id="home" class="text-gray-300 hover:text-white transition">Home</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="cart.php" class="relative text-gray-300 hover:text-white transition">
                        Cart
                        <?php if ($cart_count > 0): ?>
                            <span class="absolute -top-2 -right-4 bg-red-600 text-white rounded-full px-2 text-xs"><?= $cart_count ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="logout.php" class="text-gray-300 hover:text-white transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-300 hover:text-white transition">Login</a>
                    <a href="register.php" class="text-gray-300 hover:text-white transition">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>