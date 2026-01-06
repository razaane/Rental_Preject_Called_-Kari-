<?php
require_once __DIR__ . '/../../src/rental.php';
$db = new Database;
$conn = $db->getConnection();
$rental = new Rental($conn);
$rentals = $rental->findAllByHost();

?>

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purple Host Dashboard</title>
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
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        "2xl": "1rem",
                        full: "9999px"
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(146,19,236,0.1)',
                        'glow': '0 0 15px rgba(146,19,236,0.3)'
                    },
                },
            },
        }
    </script>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .nav-item {
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: linear-gradient(180deg, #9213ec, #7a0ec4);
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .nav-item.active::before,
        .nav-item:hover::before {
            height: 70%;
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px -8px rgba(146, 19, 236, 0.25);
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slide-in 0.5s ease-out forwards;
        }

        .stat-card {
            animation-delay: calc(var(--index) * 0.1s);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden selection:bg-primary selection:text-white">

    <div class="flex min-h-screen">

        <!-- Enhanced Sidebar -->
        <aside class="w-72 bg-surface-light dark:bg-surface-dark border-r border-slate-200/50 dark:border-white/5 flex flex-col fixed h-screen shadow-xl">

            <!-- Logo & Brand -->
            <div class="p-6 border-b border-slate-200/50 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center shadow-glow">
                            <span class="material-symbols-outlined text-white text-2xl">villa</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-surface-light dark:border-surface-dark"></div>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-900 dark:text-white tracking-tight">Purple Host</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Property Management</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto no-scrollbar">
                <div class="mb-6">
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 px-3">Main Menu</p>

                    <button class="nav-item active w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-purple-50/70 dark:hover:bg-white/5 transition-all font-semibold text-slate-900 dark:text-white group">
                        <div class="p-2 bg-primary/10 dark:bg-primary/20 rounded-lg group-hover:bg-primary/20 dark:group-hover:bg-primary/30 transition-colors">
                            <span class="material-symbols-outlined text-primary text-xl">dashboard</span>
                        </div>
                        <span>Dashboard</span>
                    </button>

                    <button class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-purple-50/70 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300 group">
                        <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-primary/10 dark:group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 group-hover:text-primary text-xl">home_work</span>
                        </div>
                        <span>My Rentals</span>
                        <span class="ml-auto bg-primary/10 dark:bg-primary/20 text-primary text-xs font-bold px-2 py-1 rounded-lg">4</span>
                    </button>

                    <button class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-purple-50/70 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300 group">
                        <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-primary/10 dark:group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 group-hover:text-primary text-xl">calendar_month</span>
                        </div>
                        <span>Bookings</span>
                    </button>

                    <button class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-purple-50/70 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300 group">
                        <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-primary/10 dark:group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 group-hover:text-primary text-xl">payments</span>
                        </div>
                        <span>Earnings</span>
                    </button>
                </div>

                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 px-3">Actions</p>

                    <button onclick="window.location.href='../host/add_rental.php'"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl bg-gradient-to-r from-primary to-primary-dark hover:shadow-glow transition-all font-bold text-white group">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <span class="material-symbols-outlined text-white text-xl">add_circle</span>
                        </div>
                        <span>Add New Rental</span>
                    </button>
                    <button class="nav-item w-full flex items-center gap-3 px-3 py-3 mt-2 rounded-xl hover:bg-purple-50/70 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300 group">
                        <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-primary/10 dark:group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 group-hover:text-primary text-xl">analytics</span>
                        </div>
                        <span>Analytics</span>
                    </button>

                    <button class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-purple-50/70 dark:hover:bg-white/5 transition-all font-semibold text-slate-600 dark:text-slate-300 group">
                        <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:bg-primary/10 dark:group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 group-hover:text-primary text-xl">settings</span>
                        </div>
                        <span>Settings</span>
                    </button>
                </div>
            </nav>

            <!-- Profile Section -->
            <div class="p-4 border-t border-slate-200/50 dark:border-white/5">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-100 dark:border-purple-800/30">
                    <div class="relative">
                        <div class="bg-center bg-cover rounded-xl h-11 w-11 ring-2 ring-primary/20" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBnute3yUXbiHqqPLtvVxJvWKosYVuVY2BC-zUhkGJhtcvEcAZG39gfhukeRUttnDE9hkM2hktwD-kX46tCctjI7ttlcrDVueHORoeLsRZwKIJheQALQAK1SFuvNRSBdeTJFWZu3GRpwb-1KMz3QaMU22xlVSqIOWiG6v62oTkFtphQcW43OmSmhy5mljbXXlNGSFXvnLnTxBqYOJ43Zi2fgzQSLc_Sqbuh8KkFO1fdgi9iu-wgpcETTAk-wjaEnpvw-1TXqIEM96A");'></div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-surface-light dark:border-surface-dark"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 dark:text-white truncate text-sm">Alex Wilson</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Premium Host</p>
                    </div>
                    <button class="p-2 hover:bg-white/50 dark:hover:bg-white/10 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 text-xl">more_vert</span>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-72">
            <!-- Top App Bar -->
            <div class="sticky top-0 z-50 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-xl border-b border-slate-200/50 dark:border-white/5 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Welcome back, Alex! 👋</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Here's what's happening with your properties today</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Search -->
                        <div class="hidden md:flex items-center gap-2 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 w-64">
                            <span class="material-symbols-outlined text-slate-400 text-xl">search</span>
                            <input type="text" placeholder="Search properties..." class="bg-transparent border-none outline-none text-sm text-slate-900 dark:text-white placeholder:text-slate-400 w-full" />
                        </div>
                        <!-- Messages Button -->
                        <button id="messagesBtn" class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-surface-light dark:bg-surface-dark hover:bg-purple-50 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 transition-all active:scale-95">
                            <span class="material-symbols-outlined text-slate-700 dark:text-slate-200">message</span>
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-blue-500 ring-2 ring-background-light dark:ring-background-dark animate-pulse"></span>
                        </button>
                        <!-- Notifications Button -->
                        <button id="notificationsBtn" class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-surface-light dark:bg-surface-dark hover:bg-purple-50 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 transition-all active:scale-95">
                            <span class="material-symbols-outlined text-slate-700 dark:text-slate-200">notifications</span>
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-background-light dark:ring-background-dark animate-pulse"></span>
                        </button>

                        <!-- Messages Panel -->
                        <div id="messagesPanel" class="hidden absolute top-20 right-6 w-96 max-h-[500px] bg-surface-light dark:bg-surface-dark shadow-2xl rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 z-50">
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                                <h3 class="font-bold text-slate-900 dark:text-white">Messages</h3>
                                <span class="text-xs bg-primary/10 text-primary font-bold px-2 py-1 rounded-lg">3 New</span>
                            </div>
                            <div class="overflow-y-auto max-h-96">
                                <div class="p-4 flex items-start gap-3 hover:bg-purple-50/50 dark:hover:bg-white/5 cursor-pointer border-b border-slate-100 dark:border-white/5 transition-colors">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0">AM</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm font-bold text-slate-900 dark:text-white">Alice Morrison</p>
                                            <span class="text-xs text-slate-400">5m ago</span>
                                        </div>
                                        <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2">Hi! Is your downtown loft available for next weekend? I'd love to book it for...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Panel -->
                        <div id="notificationsPanel" class="hidden absolute top-20 right-6 w-96 max-h-[500px] bg-surface-light dark:bg-surface-dark shadow-2xl rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 z-50">
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                                <h3 class="font-bold text-slate-900 dark:text-white">Notifications</h3>
                                <button class="text-xs text-primary font-bold hover:text-primary-dark">Mark all read</button>
                            </div>
                            <div class="overflow-y-auto max-h-96">
                                <div class="p-4 hover:bg-purple-50/50 dark:hover:bg-white/5 cursor-pointer border-b border-slate-100 dark:border-white/5 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-slate-900 dark:text-white font-medium mb-1">Booking Confirmed</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Modern Downtown Loft booked for Oct 12-14</p>
                                            <span class="text-xs text-slate-400 mt-1 inline-block">2 hours ago</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Earnings Card -->
                    <div class="stat-card animate-slide-in card-hover bg-gradient-to-br from-primary via-purple-600 to-pink-500 rounded-2xl p-6 text-white relative overflow-hidden" style="--index: 0">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">trending_up</span> +12%
                                </span>
                            </div>
                            <p class="text-white/80 text-sm font-medium mb-1">Total Earnings</p>
                            <p class="text-4xl font-bold mb-2">$3,450</p>
                            <p class="text-white/70 text-xs">+$420 from last month</p>
                        </div>
                    </div>

                    <!-- Active Listings -->
                    <div class="stat-card animate-slide-in card-hover bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-2xl p-6" style="--index: 1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">home_work</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Active Listings</p>
                        <p class="text-4xl font-bold text-slate-900 dark:text-white mb-2">4</p>
                        <p class="text-slate-400 text-xs">2 pending approval</p>
                    </div>

                    <!-- Occupancy Rate -->
                    <div class="stat-card animate-slide-in card-hover bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-2xl p-6" style="--index: 2">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">pie_chart</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Occupancy Rate</p>
                        <p class="text-4xl font-bold text-slate-900 dark:text-white mb-2">85%</p>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 mt-3">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>

                    <!-- Total Reviews -->
                    <div class="stat-card animate-slide-in card-hover bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-white/10 rounded-2xl p-6" style="--index: 3">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-2xl">star</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Average Rating</p>
                        <div class="flex items-baseline gap-2 mb-2">
                            <p class="text-4xl font-bold text-slate-900 dark:text-white">4.8</p>
                            <span class="text-slate-400 text-sm">/ 5.0</span>
                        </div>
                        <p class="text-slate-400 text-xs">Based on 127 reviews</p>
                    </div>
                </div>
            </div>

            <!-- My Rentals Section -->
            <div class="px-6 pb-8">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">My Properties</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and track your rental listings</p>
                    </div>
                    <button onclick="window.location.href='../host/add_rental.php'" class="flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white font-semibold rounded-xl transition-all active:scale-95 shadow-lg shadow-primary/25">
                        <span class="material-symbols-outlined text-xl">add</span>
                        Add Property
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
                    <!-- Active Rental Card -->
                <?php if (!empty($rentals)) : ?>
                    <?php foreach ($rentals as $itm) : ?>
                        <div class="group card-hover bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 transition-all">
                            <div class="relative h-48 overflow-hidden">
                                <div class="absolute top-3 right-3 z-10 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1 shadow-lg">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    Active
                                </div>
                                <img src="<?php htmlspecialchars($itm['img_url']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            </div>
                            <div class="p-5">
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white mb-2">
                                    <?php htmlspecialchars($itm['title']) ?>
                                </h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                    📍 <?= htmlspecialchars($itm['city']) ?>
                                </p>
                                <!-- <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400 mb-4">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">bed</span> 2 Beds
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">bathtub</span> 2 Baths
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">wifi</span> WiFi
                                    </span>
                                </div> -->
                                <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-white/10">
                                    <div>
                                        <span class="text-2xl font-bold text-primary">
                                            <?php htmlspecialchars($itm['price_per_night'])?>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button href="../host/edit_rental.php?id=<?=  $itm['rental_id'] ?>" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                            Edit
                                        </button>
                                        <button href="../host/delete_rental.php?id<?=  $itm['rental_id'] ?>" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach ?>
                <?php else : ?>
                    <p class="text-slate-500 dark:text-slate-400">
                        No properties found.
                    </p>
                <?php endif; ?>



                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>