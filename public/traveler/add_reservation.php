
<?php
require_once __DIR__ . '/../../src/booking.php';
require_once __DIR__ . '/../../src/rental.php';

// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     header('Location: dashboard.php');
//     exit;
// }

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
//     die('Unauthorized');
// }
// if (
//     empty($_POST['rental_id']) ||
//     empty($_POST['start_date']) ||
//     empty($_POST['end_date'])
// ) {
//     die('Invalid booking data');
// }

$db = new Database();
$conn = $db->getConnection();

$rentalModel = new Rental($conn);
$rental = $rentalModel->findById((int) $_POST['rental_id']);

if (!$rental) {
    die('Rental not found');
}

$booking = new Booking($conn);

try {
    $booking->create([
        'rental_id' => (int) $_POST['rental_id'],
        'user_id' => (int) $_SESSION['user_id'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'price_per_night' => (float) $rental['price_per_night'],
        'guests' => (int) ($_POST['guests'] ?? 1)
    ]);

    header("Location: dashboard.php?success=booked");
    exit;

} catch (Exception $e) {
    header("Location: dashboard.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<script>
function openBookingModal(rental) {
    // rental = {id, title, price_per_night, ...}

    document.getElementById('modalRentalId').value = rental.id;
    document.getElementById('modalHiddenPrice').value = rental.price_per_night;
    document.getElementById('modalPricePerNight').textContent = '$' + rental.price_per_night;

    // Optional: set min date for check-in to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('modalCheckinDate').min = today;

    // Show modal
    document.getElementById('bookingModal').classList.remove('hidden');
    
    // Optional: auto-focus check-in
    document.getElementById('modalCheckinDate').focus();
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Real-time total price calculation (very nice UX)
document.addEventListener('DOMContentLoaded', () => {
    const checkin = document.getElementById('modalCheckinDate');
    const checkout = document.getElementById('modalCheckoutDate');
    const priceEl = document.getElementById('modalPricePerNight');
    const nightsEl = document.getElementById('modalNightsCount');
    const totalEl = document.getElementById('modalTotalPrice');
    const hiddenPrice = document.getElementById('modalHiddenPrice');

    function calculateTotal() {
        if (!checkin.value || !checkout.value) return;

        const start = new Date(checkin.value);
        const end = new Date(checkout.value);
        const diffTime = Math.abs(end - start);
        const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (nights <= 0) {
            nightsEl.textContent = '—';
            totalEl.textContent = '—';
            return;
        }

        const price = parseFloat(hiddenPrice.value) || 0;
        const total = price * nights;

        nightsEl.textContent = nights;
        totalEl.textContent = '$' + total.toLocaleString();
    }

    checkin.addEventListener('change', () => {
        checkout.min = checkin.value;
        calculateTotal();
    });
    
    checkout.addEventListener('change', calculateTotal);
});
</script>
<body>
    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 1): ?>
<!-- <form method="POST" class="mt-6 space-y-4">
    <input type="hidden" name="rental_id" value="<?= $itm['rental_id'] ?>">
    <input type="hidden" name="price_per_night" value="<?= $itm['price_per_night'] ?>">

    <div>
        <label>Start date</label>
        <input type="date" name="start_date" required class="border p-2 w-full">
    </div>

    <div>
        <label>End date</label>
        <input type="date" name="end_date" required class="border p-2 w-full">
    </div>

    <button class="bg-primary text-white px-4 py-2 rounded">
        Book Now
    </button>
</form> -->
<!-- Booking Modal -->
<div id="bookingModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-lg w-full shadow-glow overflow-hidden border border-border-light dark:border-border-dark">
        
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-primary to-primary-dark p-6 text-white">
            <h3 class="text-2xl font-bold">Reserve Your Stay</h3>
            <p class="text-white/80 mt-1 text-sm">Complete your booking for this beautiful place</p>
        </div>

        <!-- Form content -->
        <div class="p-6 space-y-6">
            <!-- Price summary (very important for trust) -->
            <!-- <div class="bg-purple-50/50 dark:bg-purple-950/30 p-4 rounded-xl border border-purple-200/50 dark:border-purple-800/40">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Price per night</span>
                    <span class="text-xl font-bold text-primary" id="modalPricePerNight">$145</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Total for <span id="modalNightsCount">5</span> nights</span>
                    <span class="font-bold text-slate-800 dark:text-white" id="modalTotalPrice">$725</span>
                </div>
            </div>  -->

            <form id="bookingForm" method="POST" action="" class="space-y-5">
                <input type="hidden" name="rental_id" id="modalRentalId">
                <input type="hidden" name="price_per_night" id="modalHiddenPrice">

                <!-- Dates -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="flex flex-col">
                        <label class="text-sm font-semibold text-primary mb-2">Check-in</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="modalCheckinDate"
                            required 
                            class="w-full px-4 py-3 rounded-xl bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:ring-2 focus:ring-primary focus:border-primary transition"
                        >
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-semibold text-primary mb-2">Check-out</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="modalCheckoutDate"
                            required 
                            class="w-full px-4 py-3 rounded-xl bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:ring-2 focus:ring-primary focus:border-primary transition"
                        >
                    </div>
                </div>

                <!-- Guests -->
                <div class="flex flex-col">
                    <label class="text-sm font-semibold text-primary mb-2">Number of Guests</label>
                    <input 
                        type="number" 
                        name="guests" 
                        min="1" 
                        value="2" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:ring-2 focus:ring-primary focus:border-primary transition"
                    >
                </div>

                <!-- Action buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-border-light dark:border-border-dark">
                    <button 
                        type="button"
                        onclick="closeBookingModal()"
                        class="flex-1 py-3 px-6 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 font-semibold rounded-xl transition-colors"
                    >
                        Cancel
                    </button>
                    
                    <button 
                        type="submit"
                        class="flex-1 py-3 px-6 bg-gradient-to-r from-primary to-primary-dark text-white font-bold rounded-xl shadow-glow hover:shadow-soft transition-all transform hover:scale-[1.02]"
                    >
                        Confirm Booking
                    </button>
                </div>
            </form>

            <!-- Small trust note -->
            <p class="text-xs text-center text-slate-500 dark:text-slate-400 mt-4">
                You won't be charged yet • Free cancellation available until check-in -2 days
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

</body>
</html>