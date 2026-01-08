<?php
require_once __DIR__ . '/../../src/booking.php';

if (!isset($_GET['rental_id'], $_GET['price'])) {
    die("Missing rental data");
}
$db = new Database();
$conn = $db->getConnection();

$booking = new Booking($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $booking->create($_POST);

        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$rental_id = (int) $_GET['rental_id'];
$price_per_night = (float) $_GET['price'];




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <!-- hidden data -->
        <input type="hidden" name="rental_id" value="<?= $rental_id ?>">
        <input type="hidden" name="price_per_night" value="<?= $price_per_night ?>">

        <div>
            <label>Check-in Date</label>
            <input type="date" name="start_date" required>
        </div>

        <div>
            <label>Check-out Date</label>
            <input type="date" name="end_date" required>
        </div>

        <div>
            <label>Number of Guests</label>
            <input type="number" name="guests" min="1" value="1">
        </div>

        <button type="submit" name="submit">
            Confirm Booking
        </button>
    </form>

</body>

</html>