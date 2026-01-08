<?php
require_once __DIR__ . "/../../src/rental.php";
require_once __DIR__ . "/../../src/Booking.php";
require_once __DIR__ . "/../../src/User.php";

$db = new Database();
$conn = $db->getConnection();
$userObj = new User($conn);

// Get user profile
$userProfile = $userObj->findByEmail($_SESSION['email'] ?? '');

// Get user bookings
$bookings = [];
$message = '';
try {
    $booking = new Booking($conn);
    $bookings = $booking->findUserBookings();
    
    // Get rental and host details for each booking
    foreach ($bookings as &$book) {
        $rentalSql = "SELECT r.*, u.username as host_name FROM rental r 
                      LEFT JOIN users u ON r.host_id = u.user_id 
                      WHERE r.rental_id = ?";
        $stmt = $conn->prepare($rentalSql);
        $stmt->execute([$book['rental_id']]);
        $book['rental'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $message = "Error loading bookings: " . $e->getMessage();
}

// Handle cancellation
if (isset($_POST['cancel_booking'])) {
    try {
        $bookingId = (int)$_POST['booking_id'];
        $booking = new Booking($conn);
        if ($booking->cancel($bookingId)) {
            $_SESSION['success'] = "Booking cancelled successfully!";
        } else {
            $_SESSION['error'] = "Failed to cancel booking";
        }
        header('Location: bookings.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header('Location: bookings.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Bookings - Purple Host</title>
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
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="hidden md:flex w-72 bg-surface-light dark:bg-surface-dark border-r border-slate-200 dark:border-white/5 flex-col fixed h-screen shadow-xl z-50">
            <div class="p-6 border-b border-slate-200 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-2xl">villa</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-900 dark:text-white">Purple Host</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Traveler Dashboard</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="dashboard.php" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">dashboard</span>
                    <span>Dashboard</span>
                </a>
                
                <a href="bookings.php" class="nav-btn active w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white font-bold transition-all">
                    <span class="material-symbols-outlined text-xl">calendar_month</span>
                    <span>My Bookings</span>
                    <span class="ml-auto bg-white text-primary text-xs font-bold px-2.5 py-1 rounded-full"><?= count($bookings) ?></span>
                </a>

                <div class="pt-6 mt-4 border-t border-slate-200 dark:border-white/10">
                    <a href="/public/auth/logout.php" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all font-semibold text-slate-600 dark:text-slate-300 hover:text-red-600">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-200 dark:border-white/5">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-pink-500 flex items-center justify-center text-white font-bold">
                        <?= strtoupper(substr($userProfile['username'] ?? 'U', 0, 2)) ?>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm"><?= htmlspecialchars($userProfile['username'] ?? 'User') ?></p>
                        <p class="text-xs text-slate-500">Traveler</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary to-pink-500 text-white p-8 rounded-b-3xl shadow-xl">
                <div class="max-w-6xl mx-auto">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">My Bookings</h1>
                            <p class="text-white/80">Manage all your reservations in one place</p>
                        </div>
                        <a href="dashboard.php" class="px-6 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl font-semibold transition flex items-center gap-2">
                            <span class="material-symbols-outlined">add</span>
                            New Booking
                        </a>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="max-w-6xl mx-auto px-6 mt-6">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="max-w-6xl mx-auto px-6 mt-6">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Bookings List -->
            <div class="p-6 max-w-6xl mx-auto">
                <?php if (empty($bookings)): ?>
                    <div class="text-center py-16">
                        <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">event_busy</span>
                        <h2 class="text-2xl font-bold mb-2">No Bookings Yet</h2>
                        <p class="text-slate-500 dark:text-slate-400 mb-6">Start exploring amazing properties and make your first booking!</p>
                        <a href="dashboard.php" class="inline-block px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition">
                            Browse Rentals
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($bookings as $book): ?>
                            <?php
                            $rental = $book['rental'];
                            $statusColors = [
                                'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            ];
                            $statusColor = $statusColors[$book['status']] ?? 'bg-slate-100 text-slate-800';
                            
                            $isPast = strtotime($book['end_date']) < time();
                            $canCancel = $book['status'] === 'confirmed' && !$isPast;
                            ?>
                            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6">
                                    <!-- Image -->
                                    <div class="md:col-span-1">
                                        <a href="rental_details.php?id=<?= $rental['rental_id'] ?>">
                                            <img src="<?= htmlspecialchars($rental['image_url'] ?? '/assets/placeholder.jpg') ?>" 
                                                 alt="<?= htmlspecialchars($rental['title']) ?>" 
                                                 class="w-full h-40 md:h-full object-cover rounded-xl hover:opacity-90 transition"
                                                 onerror="this.src='/assets/placeholder.jpg'">
                                        </a>
                                    </div>

                                    <!-- Details -->
                                    <div class="md:col-span-3 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex-1">
                                                    <a href="rental_details.php?id=<?= $rental['rental_id'] ?>" class="hover:text-primary transition">
                                                        <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($rental['title']) ?></h3>
                                                    </a>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center gap-1 mb-2">
                                                        <span class="material-symbols-outlined text-base">location_on</span>
                                                        <?= htmlspecialchars($rental['city']) ?>
                                                    </p>
                                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                                        Host: <span class="font-semibold"><?= htmlspecialchars($rental['host_name'] ?? 'Unknown') ?></span>
                                                    </p>
                                                </div>
                                                <span class="<?= $statusColor ?> px-3 py-1 rounded-lg text-xs font-bold uppercase">
                                                    <?= htmlspecialchars($book['status']) ?>
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-primary">event</span>
                                                    <div>
                                                        <p class="text-xs text-slate-500">Check-in</p>
                                                        <p class="font-semibold text-sm"><?= date('M d, Y', strtotime($book['start_date'])) ?></p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-primary">event</span>
                                                    <div>
                                                        <p class="text-xs text-slate-500">Check-out</p>
                                                        <p class="font-semibold text-sm"><?= date('M d, Y', strtotime($book['end_date'])) ?></p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-primary">nights_stay</span>
                                                    <div>
                                                        <p class="text-xs text-slate-500">Duration</p>
                                                        <p class="font-semibold text-sm">
                                                            <?php 
                                                            $nights = (strtotime($book['end_date']) - strtotime($book['start_date'])) / 86400;
                                                            echo (int)$nights . ' night' . ($nights > 1 ? 's' : '');
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-primary">euro</span>
                                                    <div>
                                                        <p class="text-xs text-slate-500">Total</p>
                                                        <p class="font-bold text-primary text-lg"><?= number_format($book['total_price'], 0, ',', ' ') ?> €</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-3">
                                            <a href="rental_details.php?id=<?= $rental['rental_id'] ?>" 
                                               class="flex-1 px-4 py-2 bg-purple-50 dark:bg-purple-900/20 text-primary font-semibold rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition text-center">
                                                View Details
                                            </a>
                                            
                                            <button onclick="openMessageModal(<?= $rental['rental_id'] ?>, '<?= addslashes($rental['host_name'] ?? 'Host') ?>')" 
                                                    class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                                                <span class="material-symbols-outlined text-sm align-middle mr-1">mail</span>
                                                Message Host
                                            </button>

                                            <?php if ($canCancel): ?>
                                                <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');" class="flex-1">
                                                    <input type="hidden" name="booking_id" value="<?= $book['booking_id'] ?>">
                                                    <button type="submit" name="cancel_booking" 
                                                            class="w-full px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                                        <span class="material-symbols-outlined text-sm align-middle mr-1">cancel</span>
                                                        Cancel
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Message Host</h3>
                <button onclick="closeMessageModal()" class="text-slate-500 hover:text-slate-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                        Send a message to <span id="hostName" class="font-semibold text-primary"></span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Subject</label>
                    <input type="text" id="msgSubject" placeholder="Question about booking" class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Message</label>
                    <textarea id="msgContent" rows="5" placeholder="Type your message..." class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary resize-none"></textarea>
                </div>
                <button onclick="sendHostMessage()" class="w-full px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-colors">
                    Send Message
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentRentalId = null;

        function openMessageModal(rentalId, hostName) {
            currentRentalId = rentalId;
            document.getElementById('hostName').textContent = hostName;
            document.getElementById('messageModal').classList.remove('hidden');
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
            document.getElementById('msgSubject').value = '';
            document.getElementById('msgContent').value = '';
        }

        function sendHostMessage() {
            const subject = document.getElementById('msgSubject').value;
            const content = document.getElementById('msgContent').value;
            
            if (!subject || !content) {
                showToast('Please fill all fields');
                return;
            }

            // TODO: Implement actual messaging system
            showToast('Message sent successfully!');
            closeMessageModal();
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-black/80 text-white px-6 py-3 rounded-xl z-50 shadow-2xl';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html>