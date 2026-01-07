<?php
// require_once __DIR__ . '/../../src/rental.php';


// $rental_id = (int) $_GET['rental_id'];

// $db = new Database;
// $conn =$db->getConnection();
// $rental = new Rental($conn);
// if($rental->delete($rental_id)){
    
// header("Location: dashboard.php");
// exit;
// }

require_once __DIR__ . '/../../src/rental.php';
require_once __DIR__ . '/../../src/database.php';
session_start(); // MUST be here

// Check if host is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../../public/login.php");
    exit;
}

// Make sure rental_id exists
if (!isset($_GET['rental_id'])) {
    header("Location: dashboard.php");
    exit;
}

$rental_id = (int) $_GET['rental_id'];

$db = new Database();
$conn = $db->getConnection();
$rental = new Rental($conn);

// Attempt delete
$deleted = $rental->delete($rental_id);

if ($deleted) {
    $_SESSION['success'] = "Rental deleted successfully!";
} else {
    $_SESSION['error'] = "Rental could not be deleted. Check if it belongs to you.";
}

// Redirect back
header("Location: dashboard.php");
exit;

?>
