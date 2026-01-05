<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purple Host - Rental Platform</title>
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
        body {
            min-height: 100vh;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px -8px rgba(146, 19, 236, 0.25);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display">

    <div class="flex min-h-screen">
        <!-- Sidebar - Desktop -->
        <aside id="sidebar" class="hidden md:flex w-72 bg-surface-light dark:bg-surface-dark border-r border-slate-200 dark:border-white/5 flex-col fixed h-screen shadow-xl z-50">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-200 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-2xl">villa</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-900 dark:text-white">Purple Host</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Rental Platform</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto no-scrollbar">
                <button onclick="showSection('browse')" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">search</span>
                    <span>Browse Rentals</span>
                </button>

                <button onclick="showSection('favorites')" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">favorite</span>
                    <span>My Favorites</span>
                    <span id="favCount" class="ml-auto bg-primary/10 text-primary text-xs font-bold px-2 py-1 rounded-lg">0</span>
                </button>

                <button onclick="showSection('bookings')" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">calendar_month</span>
                    <span>My Bookings</span>
                </button>

                <button onclick="showSection('reviews')" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">rate_review</span>
                    <span>My Reviews</span>
                </button>

                <div class="pt-4 border-t border-slate-200 dark:border-white/5 mt-4">
                    <button onclick="showSection('notifications')" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Notifications</span>
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">2</span>
                    </button>
                </div>
            </nav>

            <!-- Profile -->
            <div class="p-4 border-t border-slate-200 dark:border-white/5">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-pink-500 flex items-center justify-center text-white font-bold">AW</div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">Alex Wilson</p>
                        <p class="text-xs text-slate-500">Guest Account</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <button id="mobileMenuBtn" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-primary text-white rounded-xl shadow-lg">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72">
            <!-- Browse Rentals Section -->
            <div id="browseSection" class="section">
                <!-- Search Header -->
                <div class="bg-gradient-to-r from-primary to-pink-500 text-white p-4 border rounded-b-xl">
                    <div class="max-w-6xl mx-auto">
                        <h1 class="text-3xl font-bold mb-4">Find Your Perfect Stay</h1>

                        <!-- Search Form -->
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2">City</label>
                                    <input type="text" id="searchCity" placeholder="Enter city" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Min Price</label>
                                    <input type="number" id="searchMinPrice" placeholder="$0" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Max Price</label>
                                    <input type="number" id="searchMaxPrice" placeholder="$500" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Guests</label>
                                    <input type="number" id="searchGuests" placeholder="2" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                                </div>
                                <button onclick="searchRentals()" class="mt-4 w-full md:w-auto px-4 py-3 bg-white text-primary font-bold rounded-xl hover:bg-slate-100 transition-colors">
                                    Search Rentals
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Rentals Grid -->
                <div class="p-6 max-w-6xl mx-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Available Rentals</h2>
                        <div class="flex items-center gap-2">
                            <button onclick="prevPage()" class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-lg hover:bg-purple-50 dark:hover:bg-white/5">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <span class="px-4 py-2 font-semibold">Page <span id="currentPage">1</span></span>
                            <button onclick="nextPage()" class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-lg hover:bg-purple-50 dark:hover:bg-white/5">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>

                    <div id="rentalsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Rentals will be rendered here -->
                    </div>
                </div>
            </div>

            <!-- Favorites Section -->
            <div id="favoritesSection" class="section hidden">
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

            <!-- Bookings Section -->
            <div id="bookingsSection" class="section hidden">
                <div class="p-6 max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6">My Bookings</h2>
                    <div id="bookingsList" class="space-y-4">
                        <p class="text-slate-500 text-center py-12">No bookings yet.</p>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div id="reviewsSection" class="section hidden">
                <div class="p-6 max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6">My Reviews</h2>
                    <div id="reviewsList" class="space-y-4">
                        <p class="text-slate-500 text-center py-12">No reviews yet.</p>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div id="notificationsSection" class="section hidden">
                <div class="p-6 max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6">Notifications</h2>
                    <div id="notificationsList" class="space-y-3">
                        <!-- Notifications will be rendered here -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Rental Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div id="detailContent"></div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold mb-4">Book This Rental</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Check-in Date</label>
                    <input type="date" id="checkinDate" class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Check-out Date</label>
                    <input type="date" id="checkoutDate" class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Number of Guests</label>
                    <input type="number" id="bookingGuests" min="1" value="2" class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary">
                </div>
                <div class="flex gap-3">
                    <button onclick="confirmBooking()" class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-colors">
                        Confirm Booking
                    </button>
                    <button onclick="closeBookingModal()" class="px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 font-bold rounded-xl transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold mb-4">Leave a Review</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Rating</label>
                    <div class="flex gap-2">
                        <button onclick="setRating(1)" class="rating-btn text-3xl">⭐</button>
                        <button onclick="setRating(2)" class="rating-btn text-3xl">⭐</button>
                        <button onclick="setRating(3)" class="rating-btn text-3xl">⭐</button>
                        <button onclick="setRating(4)" class="rating-btn text-3xl">⭐</button>
                        <button onclick="setRating(5)" class="rating-btn text-3xl">⭐</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Comment</label>
                    <textarea id="reviewComment" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary resize-none"></textarea>
                </div>
                <div class="flex gap-3">
                    <button onclick="submitReview()" class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-colors">
                        Submit Review
                    </button>
                    <button onclick="closeReviewModal()" class="px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 font-bold rounded-xl transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>