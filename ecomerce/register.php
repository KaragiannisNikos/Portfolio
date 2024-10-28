<?php
include('db.php');
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert user into the database
    try {
        $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');
        $stmt->execute([$first_name, $last_name, $email, $password]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Email already exists.';
    }
}

include('includes/header.php');
?>

<div class="container mx-auto p-8">
    <h2 class="text-3xl font-bold text-center text-gray-300 mb-6">Register</h2>

    <?php if ($error): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php" class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label for="first_name" data-cy="firstName" class="block text-gray-300">First Name:</label>
            <input type="text" name="first_name" id="first_name" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
        </div>
        <div class="mb-4">
            <label for="last_name" data-cy="lastName" class="block text-gray-300">Last Name:</label>
            <input type="text" name="last_name" id="last_name" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
        </div>
        <div class="mb-4">
            <label for="email" data-cy="r_email" class="block text-gray-300">Email:</label>
            <input type="email" name="email" id="email" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
        </div>
        <div class="mb-6">
            <label for="password" data-cy="r_password" class="block text-gray-300">Password:</label>
            <input type="password" name="password" id="password" class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200" required>
        </div>
        <button type="submit" data-cy="submit_register" class="w-full bg-purple-600 py-2 rounded-lg text-white">Register</button>
    </form>

    <p data-cy="account" class="text-center mt-6 text-gray-400">Already have an account? <a href="login.php" data-cy="login" class="text-purple-400 hover:underline">Login</a></p>
</div>

<?php include('includes/footer.php'); ?>