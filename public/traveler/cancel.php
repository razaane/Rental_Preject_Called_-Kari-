<?php
require_once __DIR__ . '/../src/Booking.php';

$db = new Database();
$conn = $db->getConnection();
$booking = new Booking($conn);

$booking->cancel(
    (int)$_GET['booking_id'],
    $_SESSION['user_id'],
    $_SESSION['role'] === 3 ? 'admin' : 'user'
);

header("Location: dashboard.php");
exit;
