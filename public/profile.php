<?php
session_start();
require_once '../src/user.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$pdo = $db->getConnection();
$user = new User($pdo);

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'] // if blank, keep old password inside updateProfile
    ];

    if ($user->updateProfile($_SESSION['user_id'], $data)) {
        $success = "Profile updated successfully!";
        // Refresh the current user info
        $stmt->execute([$_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = "Failed to update profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex items-center justify-center">
<div class="bg-white dark:bg-gray-800 rounded-xl p-8 w-full max-w-md shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Update Your Profile</h1>

    <?php if ($error): ?>
        <div class="text-red-500 mb-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="text-green-500 mb-2"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="flex flex-col gap-4">
        <input type="text" name="username" placeholder="Full Name" required
            value="<?= htmlspecialchars($currentUser['username']) ?>"
            class="w-full px-4 py-3 rounded border bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">

        <input type="email" name="email" placeholder="Email Address" required
            value="<?= htmlspecialchars($currentUser['email']) ?>"
            class="w-full px-4 py-3 rounded border bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">

        <input type="password" name="password" placeholder="New Password (leave blank to keep)"
            class="w-full px-4 py-3 rounded border bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">

        <button type="submit" class="mt-2 w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg">
            Update Profile
        </button>
    </form>
</div>
</body>
</html>
