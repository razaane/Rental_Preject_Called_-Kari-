<?php
require_once __DIR__ . '/../../src/booking.php';
require_once __DIR__ . '/../../src/rental.php';
require_once __DIR__ . '/../../src/user.php';


$db = new Database();
$conn = $db->getConnection();
$ren = new Rental($conn);
$userObj = new User($conn);

// Get user profile
$userProfile = $userObj->findByEmail($_SESSION['email'] ?? '');

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

// Search criteria
$criteria = [];
if (!empty($_GET['city'])) $criteria['city'] = $_GET['city'];
if (!empty($_GET['min_price'])) $criteria['min_price'] = $_GET['min_price'];
if (!empty($_GET['max_price'])) $criteria['max_price'] = $_GET['max_price'];

// Get rentals with pagination
if (!empty($criteria)) {
    $rentals = $ren->search($criteria, $perPage, $offset);
    $totalRentals = $ren->countSearchResults($criteria);
} else {
    // For all rentals, we need to modify findAllPublic to support pagination
    $rentals = $ren->findAllPublic();
    $totalRentals = count($rentals);
    $rentals = array_slice($rentals, $offset, $perPage);
}

$totalPages = ceil($totalRentals / $perPage);

// Get user's bookings count
try {
    $booking = new Booking($conn);
    $userBookings = $booking->findUserBookings();
    $bookingsCount = count($userBookings);
} catch (Exception $e) {
    $bookingsCount = 0;
    $userBookings = [];
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purple Host - Traveler Dashboard</title>
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
    <style>
        body { min-height: 100vh; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 40px -8px rgba(146, 19, 236, 0.25); }
        .section { display: none; }
        .section.active { display: block; }
        .nav-btn.active { background: linear-gradient(135deg, #9213ec 0%, #7a0ec4 100%); color: white; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="hidden md:flex w-72 bg-surface-light dark:bg-surface-dark border-r border-slate-200 dark:border-white/5 flex-col fixed h-screen shadow-xl z-50">
            <!-- Logo -->
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

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto no-scrollbar">
                <button onclick="showSection('browse')" class="nav-btn active w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold">
                    <span class="material-symbols-outlined text-xl">dashboard</span>
                    <span>Dashboard</span>
                </button>
                
                <a href="bookings.php" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">calendar_month</span>
                    <span>My Bookings</span>
                    <span class="ml-auto bg-primary text-white text-xs font-bold px-2.5 py-1 rounded-full"><?= $bookingsCount ?></span>
                </a>

                <button onclick="showSection('favorites')" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">favorite</span>
                    <span>Favorites</span>
                    <span id="favCount" class="ml-auto bg-primary/10 text-primary text-xs font-bold px-2 py-1 rounded-lg">0</span>
                </button>

                <div class="pt-6 mt-4 border-t border-slate-200 dark:border-white/10">
                    <a href="/public/auth/logout.php" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all font-semibold text-slate-600 dark:text-slate-300 hover:text-red-600">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>

            <!-- Profile -->
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

        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-primary text-white rounded-xl shadow-lg">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72">
            <!-- Browse Rentals Section -->
            <div id="browseSection" class="section active">
                <!-- Search Header -->
                <div class="bg-gradient-to-r from-primary to-pink-500 text-white p-8 rounded-b-3xl shadow-xl">
                    <div class="max-w-6xl mx-auto">
                        <h1 class="text-3xl font-bold mb-4">Find Your Perfect Stay</h1>
                        
                        <!-- Search Form -->
                        <form method="GET" class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2">City</label>
                                    <input type="text" name="city" value="<?= htmlspecialchars($_GET['city'] ?? '') ?>" placeholder="Enter city" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Min Price (€)</label>
                                    <input type="number" name="min_price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>" placeholder="0" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Max Price (€)</label>
                                    <input type="number" name="max_price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>" placeholder="500" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full px-6 py-3 bg-white text-primary font-bold rounded-xl hover:bg-slate-100 transition-colors shadow-lg">
                                        <span class="material-symbols-outlined inline-block mr-2 align-middle">search</span>
                                        Search
                                    </button>
                                </div>
                            </div>
                            <?php if (!empty($criteria)): ?>
                                <div class="mt-4">
                                    <a href="?" class="text-sm text-white/80 hover:text-white underline">Clear filters</a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Rentals Grid -->
                <div class="p-6 max-w-6xl mx-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Available Rentals (<?= $totalRentals ?> found)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php if (empty($rentals)): ?>
                            <div class="col-span-full text-center py-16">
                                <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">home_work</span>
                                <p class="text-xl text-slate-500 dark:text-slate-400">No rentals found</p>
                                <a href="?" class="mt-4 inline-block px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition">
                                    Browse All Rentals
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($rentals as $rent): ?>
                                <?php
                                $rentalId = (int)$rent['rental_id'];
                                $price = number_format($rent['price_per_night'], 0, ',', ' ');
                                $title = htmlspecialchars($rent['title'] ?? 'Sans titre');
                                $desc = htmlspecialchars(substr($rent['descreption'] ?? '', 0, 85));
                                $image = htmlspecialchars($rent['image_url'] ?? '/assets/placeholder.jpg');
                                $city = htmlspecialchars($rent['city'] ?? 'Ville inconnue');
                                $capacity = (int)($rent['capacity'] ?? 0);
                                ?>
                                <div class="card-hover bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden shadow-md relative">
                                    <!-- Favorite Button -->
                                    <button type="button" onclick="event.stopPropagation(); toggleFavorite(<?= $rentalId ?>, '<?= addslashes($title) ?>', '<?= $image ?>', <?= $rent['price_per_night'] ?>);" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center bg-black/40 backdrop-blur-sm rounded-full text-white hover:bg-black/60 transition z-10 favorite-btn" data-rental-id="<?= $rentalId ?>">
                                        <span class="material-symbols-outlined text-2xl">favorite</span>
                                    </button>

                                    <!-- Image -->
                                    <a href="rental_details.php?id=<?= $rentalId ?>" class="block">
                                        <div class="relative h-56 overflow-hidden">
                                            <img src="<?= $image ?>" alt="<?= $title ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" onerror="this.src='/assets/placeholder.jpg'">
                                        </div>

                                        <!-- Content -->
                                        <div class="p-5">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-lg line-clamp-1 mb-1"><?= $title ?></h3>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-base">location_on</span>
                                                        <?= $city ?>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-4 line-clamp-2"><?= $desc ?></p>
                                            
                                            <div class="flex items-center gap-2 mb-4 text-sm text-slate-600 dark:text-slate-400">
                                                <span class="material-symbols-outlined text-base">people</span>
                                                <span><?= $capacity ?> guests</span>
                                            </div>

                                            <div class="flex justify-between items-center pt-4 border-t border-slate-200 dark:border-white/10">
                                                <div>
                                                    <span class="text-2xl font-bold text-primary"><?= $price ?> €</span>
                                                    <span class="text-sm text-slate-500">/ night</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    <!-- Book Now Button -->
                                    <div class="px-5 pb-5">
                                        <a href="add_reservation.php?rental_id=<?= $rentalId ?>" class="block w-full text-center px-4 py-2.5 bg-primary hover:bg-primary-dark text-white font-semibold rounded-xl transition shadow-md">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="flex justify-center items-center gap-2 mt-8">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?><?= !empty($criteria) ? '&' . http_build_query($criteria) : '' ?>" class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-lg hover:bg-purple-50 dark:hover:bg-white/5">
                                    <span class="material-symbols-outlined">chevron_left</span>
                                </a>
                            <?php endif; ?>

                            <span class="px-4 py-2 font-semibold">Page <?= $page ?> of <?= $totalPages ?></span>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?= $page + 1 ?><?= !empty($criteria) ? '&' . http_build_query($criteria) : '' ?>" class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-lg hover:bg-purple-50 dark:hover:bg-white/5">
                                    <span class="material-symbols-outlined">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Favorites Section -->
            <div id="favoritesSection" class="section">
                <div class="p-6 max-w-6xl mx-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">My Favorites</h2>
                        <button onclick="clearAllFavorites()" class="px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 font-semibold">
                            Clear All
                        </button>
                    </div>
                    <div id="favoritesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <p class="text-slate-500 col-span-full text-center py-12">No favorites yet. Start browsing!</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('hidden');
        });

        // Section navigation
        function showSection(sectionName) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            
            const section = document.getElementById(sectionName + 'Section');
            if (section) {
                section.classList.add('active');
                event.target.closest('.nav-btn')?.classList.add('active');
            }

            if (sectionName === 'favorites') {
                renderFavorites();
            }
        }

        // Favorites management
        function toggleFavorite(rentalId, title, image, price) {
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const index = favorites.findIndex(f => f.id === rentalId);
            const btn = document.querySelector(`.favorite-btn[data-rental-id="${rentalId}"] span`);
            
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
            updateFavoritesCount();
        }

        function updateFavoritesCount() {
            const favs = JSON.parse(localStorage.getItem('favorites') || '[]');
            document.getElementById('favCount').textContent = favs.length;
        }

        function renderFavorites() {
            const favs = JSON.parse(localStorage.getItem('favorites') || '[]');
            const grid = document.getElementById('favoritesGrid');
            
            if (favs.length === 0) {
                grid.innerHTML = '<p class="text-slate-500 col-span-full text-center py-12">No favorites yet. Start browsing!</p>';
                return;
            }

            grid.innerHTML = favs.map(fav => `
                <div class="card-hover bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden shadow-md">
                    <a href="rental_details.php?id=${fav.id}">
                        <img src="${fav.image}" alt="${fav.title}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2">${fav.title}</h3>
                            <p class="text-primary font-bold">${fav.price} € / night</p>
                        </div>
                    </a>
                    <div class="px-4 pb-4">
                        <button onclick="toggleFavorite(${fav.id}, '${fav.title}', '${fav.image}', ${fav.price})" class="w-full py-2 bg-red-500 text-white rounded-xl hover:bg-red-600">
                            Remove
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function clearAllFavorites() {
            if (confirm('Remove all favorites?')) {
                localStorage.setItem('favorites', '[]');
                updateFavoritesCount();
                renderFavorites();
                showToast('All favorites cleared');
            }
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-black/80 text-white px-6 py-3 rounded-xl z-50 shadow-2xl';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateFavoritesCount();
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            favorites.forEach(fav => {
                const btn = document.querySelector(`.favorite-btn[data-rental-id="${fav.id}"] span`);
                if (btn) {
                    btn.classList.add('text-red-500', 'fill');
                }
            });
        });
    </script>
</body>
</html>