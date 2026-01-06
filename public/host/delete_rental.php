<?php
require_once __DIR__ . '/../../src/rental.php';
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
//     header("Location: ../login.php");
//     exit;
// }

$rental_id = (int) $_GET['rental_id'];

$db = new Database;
$conn =$db->getConnection();
$rental = new Rental($conn);
$rental->delete($rental_id);

header("Location: dashboard.php");
exit;