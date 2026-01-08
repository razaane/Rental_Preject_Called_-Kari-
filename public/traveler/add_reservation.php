<?php
session_start();
require_once __DIR__ . "/../../src/rental.php";
require_once __DIR__ . "/../../src/Booking.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: /public/auth/login.php');
    exit;
}

if (!isset($_GET['rental_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$ren = new Rental($conn);

$rentalId = (int)$_GET['rental_id'];
$rental = $ren->findById($rentalId, false);

if (!$rental) {
    header('Location: index.php?error=not_found');
    exit;
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_booking'])) {
    try {
        $booking = new Booking($conn);
        $bookingData = [
            'rental_id' => $rentalId,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date']
        ];
        
        if ($booking->create($bookingData)) {
            $_SESSION['success'] = "Booking created successfully!";
            header('Location: bookings.php');
            exit;
        } else {
            $error = "Failed to create booking. Please try again.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book <?= htmlspecialchars($rental['title']) ?> - Purple Host</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#9213ec",
                        "primary-dark": "#7a0ec4",
                        "background-light": "#fdfcff",
                        "background-dark": "#1a1022",
                        "surface-light": "#ffffff",
                        "surface-dark": "#2d1b36",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display">
    <!-- Header -->
    <header class="bg-surface-light dark:bg-surface-dark border-b border-slate-200 dark:border-white/5 sticky top-0 z-40 backdrop-blur-lg bg-opacity-90">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="rental_details.php?id=<?= $rentalId ?>" class="flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-primary transition">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="font-semibold">Back to Property</span>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-white">villa</span>
                </div>
                <span class="font-bold text-lg">Purple Host</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h1 class="text-4xl font-bold mb-2">Complete Your Booking</h1>
            <p class="text-slate-600 dark:text-slate-400">Just a few more details and you're all set!</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <form method="POST" class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl">
                    <h2 class="text-2xl font-bold mb-6">Booking Details</h2>
                    
                    <div class="space-y-6">
                        <!-- Date Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    <span class="material-symbols-outlined text-sm align-middle mr-1">event</span>
                                    Check-in Date
                                </label>
                                <input type="date" name="start_date" id="startDate" required 
                                       min="<?= date('Y-m-d') ?>"
                                       class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    <span class="material-symbols-outlined text-sm align-middle mr-1">event</span>
                                    Check-out Date
                                </label>
                                <input type="date" name="end_date" id="endDate" required 
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                       class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <!-- Price Calculation -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                            <h3 class="font-bold mb-3">Price Breakdown</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span><?= number_format($rental['price_per_night'], 0, ',', ' ') ?> € × <span id="nightsCount">0</span> nights</span>
                                    <span id="subtotal" class="font-semibold">0 €</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-purple-200 dark:border-purple-800">
                                    <span class="font-bold text-base">Total</span>
                                    <span id="totalPrice" class="font-bold text-lg text-primary">0 €</span>
                                </div>
                            </div>
                        </div>

                        <!-- Important Info -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                                <div class="text-sm text-blue-800 dark:text-blue-200">
                                    <p class="font-semibold mb-2">Important Information</p>
                                    <ul class="space-y-1 list-disc list-inside">
                                        <li>You won't be charged until the host confirms</li>
                                        <li>Free cancellation up to 24 hours before check-in</li>
                                        <li>Please review the house rules before booking</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms" required class="mt-1 w-5 h-5 text-primary border-slate-300 rounded focus:ring-primary">
                            <label for="terms" class="text-sm text-slate-600 dark:text-slate-300">
                                I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and 
                                <a href="#" class="text-primary hover:underline">Cancellation Policy</a>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="create_booking" id="submitBtn" disabled
                                class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold text-lg rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>

            <!-- Property Summary -->
            <div class="lg:col-span-1">
                <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl sticky top-24">
                    <h3 class="font-bold mb-4">Your Booking</h3>
                    
                    <div class="mb-4">
                        <img src="<?= htmlspecialchars($rental['image_url']) ?>" 
                             alt="<?= htmlspecialchars($rental['title']) ?>" 
                             class="w-full h-40 object-cover rounded-xl"
                             onerror="this.src='/assets/placeholder.jpg'">
                    </div>

                    <h4 class="font-bold text-lg mb-2"><?= htmlspecialchars($rental['title']) ?></h4>
                    
                    <div class="space-y-2 text-sm text-slate-600 dark:text-slate-300 mb-4">
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">location_on</span>
                            <?= htmlspecialchars($rental['city']) ?>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">people</span>
                            Up to <?= (int)$rental['capacity'] ?> guests
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-white/10">
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-primary"><?= number_format($rental['price_per_night'], 0, ',', ' ') ?> €</span>
                            <span class="text-sm text-slate-500">/ night</span>
                        </div>
                    </div>

                    <a href="rental_details.php?id=<?= $rentalId ?>" 
                       class="block mt-4 text-center text-sm text-primary hover:underline">
                        View full details
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        const pricePerNight = <?= $rental['price_per_night'] ?>;
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const submitBtn = document.getElementById('submitBtn');
        const termsCheckbox = document.getElementById('terms');

        function calculatePrice() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate && endDate && endDate > startDate) {
                const nights = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                const total = nights * pricePerNight;

                document.getElementById('nightsCount').textContent = nights;
                document.getElementById('subtotal').textContent = total.toLocaleString() + ' €';
                document.getElementById('totalPrice').textContent = total.toLocaleString() + ' €';

                checkFormValidity();
            } else {
                document.getElementById('nightsCount').textContent = '0';
                document.getElementById('subtotal').textContent = '0 €';
                document.getElementById('totalPrice').textContent = '0 €';
                submitBtn.disabled = true;
            }
        }

        function checkFormValidity() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const termsChecked = termsCheckbox.checked;

            submitBtn.disabled = !(startDate && endDate && endDate > startDate && termsChecked);
        }

        startDateInput.addEventListener('change', () => {
            // Update minimum end date
            const minEndDate = new Date(startDateInput.value);
            minEndDate.setDate(minEndDate.getDate() + 1);
            endDateInput.min = minEndDate.toISOString().split('T')[0];
            
            // Clear end date if it's now invalid
            if (endDateInput.value && new Date(endDateInput.value) <= new Date(startDateInput.value)) {
                endDateInput.value = '';
            }
            
            calculatePrice();
        });

        endDateInput.addEventListener('change', calculatePrice);
        termsCheckbox.addEventListener('change', checkFormValidity);

        // Initialize
        calculatePrice();
    </script>
</body>
</html>