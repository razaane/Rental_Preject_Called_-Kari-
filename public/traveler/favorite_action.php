<?php
session_start();

require_once __DIR__ . '/../src/favorite.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /public/auth/login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$rentalId = (int) ($_POST['rental_id'] ?? 0);
$action = $_POST['action'] ?? '';

$db = new Database();
$conn = $db->getConnection();
$fav = new Favorite($conn);

if ($action === 'add') {
    $fav->add($userId, $rentalId);
}

if ($action === 'remove') {
    $fav->remove($userId, $rentalId);
}

// Redirect back to previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
