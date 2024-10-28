<?php
include('db.php');
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // If password is correct, log in the user
        $_SESSION['user_id'] = $user['user_id'];
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-white mb-6">Login</h2>

        <?php if ($error): ?>
            <div class="bg-red-600 text-white text-center py-2 px-4 rounded mb-4">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-6">
            <div>
                <label for="email" data-cy="email" class="block text-sm font-medium text-gray-300">Email Address</label>
                <input type="email" name="email" id="email"
                    class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Enter your email" required>
            </div>
            <div>
                <label for="password" data-cy="password" class="block text-sm font-medium text-gray-300">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Enter your password" required>
            </div>
            <button type="submit" data-cy="submit_login"
                class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition duration-300 ease-in-out">Login</button>
        </form>

        <div class="text-center mt-6">
            <p data-cy="no_account" class="text-gray-400">Don't have an account? <a href="register.php" data-cy="sign" class="text-purple-400 hover:underline">Sign up</a></p>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>