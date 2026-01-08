<?php
require_once __DIR__ . "/../../src/rental.php";
require_once __DIR__ . "/../../src/User.php";

$db = new Database();
$conn = $db->getConnection();
$ren = new Rental($conn);
$userObj = new User($conn);

$rentalId = (int)$_GET['id'];
$rental = $ren->findById($rentalId, false);

if (!$rental) {
    header('Location: dashboard.php?error=not_found');
    exit;
}

// Get host information
$hostInfo = null;
$hostSql = "SELECT u.user_id, u.username, u.email FROM users u WHERE u.user_id = ?";
$hostStmt = $conn->prepare($hostSql);
$hostStmt->execute([$rental['host_id']]);
$hostInfo = $hostStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($rental['title']) ?> - Purple Host</title>
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
            <a href="dashboard.php" class="flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-primary transition">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="font-semibold">Back to Dashboard</span>
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
    <main class="max-w-6xl mx-auto px-6 py-8">
        <!-- Title & Actions -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-4xl font-bold mb-2"><?= htmlspecialchars($rental['title']) ?></h1>
                    <div class="flex items-center gap-4 text-slate-600 dark:text-slate-400">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined">location_on</span>
                            <?= htmlspecialchars($rental['city']) ?>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined">people</span>
                            <?= (int)$rental['capacity'] ?> guests
                        </span>
                    </div>
                </div>
                <button onclick="toggleFavorite(<?= $rentalId ?>, '<?= addslashes($rental['title']) ?>', '<?= htmlspecialchars($rental['image_url']) ?>', <?= $rental['price_per_night'] ?>)" class="favorite-btn-detail px-6 py-3 bg-surface-light dark:bg-surface-dark border-2 border-slate-200 dark:border-white/10 rounded-xl hover:border-primary transition flex items-center gap-2" data-rental-id="<?= $rentalId ?>">
                    <span class="material-symbols-outlined text-2xl">favorite</span>
                    <span class="font-semibold">Save</span>
                </button>
            </div>
        </div>

        <!-- Image -->
        <div class="mb-8 rounded-3xl overflow-hidden shadow-2xl">
            <img src="<?= htmlspecialchars($rental['image_url']) ?>" alt="<?= htmlspecialchars($rental['title']) ?>" class="w-full h-96 object-cover" onerror="this.src='/assets/placeholder.jpg'">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Description -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">description</span>
                        Description
                    </h2>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        <?= htmlspecialchars($rental['descreption'] ?? 'No description available.') ?>
                    </p>
                </div>

                <!-- Property Details -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Property Details
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Address</p>
                                <p class="font-semibold"><?= htmlspecialchars($rental['adress'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <span class="material-symbols-outlined text-primary">people</span>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Capacity</p>
                                <p class="font-semibold"><?= (int)$rental['capacity'] ?> guests</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <span class="material-symbols-outlined text-primary">euro</span>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Price per night</p>
                                <p class="font-semibold"><?= number_format($rental['price_per_night'], 0, ',', ' ') ?> €</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <span class="material-symbols-outlined text-primary">event_available</span>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Availability</p>
                                <p class="font-semibold">Check dates</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Host Information -->
                <?php if ($hostInfo): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Your Host
                    </h2>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-pink-500 flex items-center justify-center text-white font-bold text-2xl">
                            <?= strtoupper(substr($hostInfo['username'], 0, 2)) ?>
                        </div>
                        <div>
                            <p class="font-bold text-lg"><?= htmlspecialchars($hostInfo['username']) ?></p>
                            <p class="text-sm text-slate-500">Property Host</p>
                        </div>
                    </div>
                    <button onclick="openContactModal()" class="mt-4 w-full px-6 py-3 bg-purple-50 dark:bg-purple-900/20 text-primary font-semibold rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">mail</span>
                        Contact Host
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Booking Card -->
            <div class="lg:col-span-1">
                <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl sticky top-24 border-2 border-primary/20">
                    <div class="mb-6">
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-4xl font-bold text-primary"><?= number_format($rental['price_per_night'], 0, ',', ' ') ?> €</span>
                            <span class="text-slate-500">/ night</span>
                        </div>
                    </div>

                    <a href="booking_form.php?rental_id=<?= $rentalId ?>" class="block w-full py-4 bg-gradient-to-r from-primary to-pink-500 text-white font-bold text-lg rounded-xl hover:shadow-lg transition-all text-center">
                        Book This Property
                    </a>

                    <p class="text-xs text-center text-slate-500 dark:text-slate-400 mt-4">
                        You won't be charged yet
                    </p>

                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/10">
                        <h3 class="font-bold mb-3">Quick Info</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500 text-base">check_circle</span>
                                <span>Instant booking</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500 text-base">check_circle</span>
                                <span>Free cancellation</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500 text-base">check_circle</span>
                                <span>24/7 support</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Contact Host Modal -->
    <div id="contactModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Contact Host</h3>
                <button onclick="closeContactModal()" class="text-slate-500 hover:text-slate-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Subject</label>
                    <input type="text" id="messageSubject" placeholder="Question about the property" class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Message</label>
                    <textarea id="messageContent" rows="5" placeholder="Hi, I'm interested in booking..." class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary resize-none"></textarea>
                </div>
                <button onclick="sendMessage()" class="w-full px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-colors">
                    Send Message
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleFavorite(rentalId, title, image, price) {
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const index = favorites.findIndex(f => f.id === rentalId);
            const btn = document.querySelector(`.favorite-btn-detail[data-rental-id="${rentalId}"] span:first-child`);
            
            if (index === -1) {
                favorites.push({ id: rentalId, title, image, price });
                btn.classList.add('text-red-500', 'fill');
                showToast("Added to favorites! ❤️");
            } else {
                favorites.splice(index, 1);
                btn.classList.remove('text-red-500', 'fill');
                showToast("Removed from favorites");
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        function openContactModal() {
            document.getElementById('contactModal').classList.remove('hidden');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
        }

        function sendMessage() {
            const subject = document.getElementById('messageSubject').value;
            const content = document.getElementById('messageContent').value;
            
            if (!subject || !content) {
                showToast('Please fill all fields');
                return;
            }

            // TODO: Implement actual message sending via AJAX
            showToast('Message sent successfully!');
            closeContactModal();
            document.getElementById('messageSubject').value = '';
            document.getElementById('messageContent').value = '';
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-black/80 text-white px-6 py-3 rounded-xl z-50 shadow-2xl';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Check if already favorited
        document.addEventListener('DOMContentLoaded', () => {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const rentalId = <?= $rentalId ?>;
            if (favorites.find(f => f.id === rentalId)) {
                const btn = document.querySelector(`.favorite-btn-detail[data-rental-id="${rentalId}"] span:first-child`);
                if (btn) {
                    btn.classList.add('text-red-500', 'fill');
                }
            }
        });
    </script>
</body>
</html>