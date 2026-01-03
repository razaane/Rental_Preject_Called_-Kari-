<?php
session_start();
require_once '../src/user.php';
require_once '../src/database.php';

$db = new Database();
$pdo = $db->getConnection();
$user = new User($pdo);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($user->login($email, $password)) {
        // Redirect to dashboard based on role
        if ($_SESSION['role'] === 'host') {
            header("Location: host_dashboard.php");
        } else {
            header("Location: traveler_dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Purple Desktop Login - Mobile</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#9213ec",
                        "primary-hover": "#7a0fc6",
                        "primary-light": "#b96df2",
                        "background-light": "#f7f6f8",
                        "background-dark": "#1a1022", // Deep charcoal purple
                        "surface-dark": "#2d1b36", // Lighter purple for cards/inputs
                        "border-purple": "#513267",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        "glow": "0 0 15px rgba(146, 19, 236, 0.3)",
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1022;
        }

        ::-webkit-scrollbar-thumb {
            background: #513267;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9213ec;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-background-dark text-white font-display antialiased min-h-screen flex flex-col items-center justify-center selection:bg-primary selection:text-white relative overflow-x-hidden">
    <!-- Ambient Background Gradients -->
    <div class="fixed top-[-20%] left-[-10%] w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="w-full max-w-md w-full relative z-10 flex flex-col min-h-screen sm:min-h-0 sm:my-10">
        <!-- Header Image Area -->
        <div class="w-full h-64 sm:rounded-t-3xl overflow-hidden relative shrink-0">
            <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/60 to-transparent z-10"></div>
            <img alt="Modern geometric purple architecture with abstract angles" class="w-full h-full object-cover opacity-80" data-alt="Modern geometric purple architecture with abstract angles" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcdVUnp03mfCvtKu7w9qqq7aL3DCYht3blLcIG9fjYW6qICWCnZNMhhx_gVOJlHWSni3eMEChC4ywdnuZroUJWjgb05jPA3RDRCvmwC_UywaBvxk9GdYW_2p1iFZ7Jegm5HhdH1nhib5iyFObWVFiyeNQHZ9jp5F_Kf6ICuGgPXLHVbPzWWRwFrhkP-yhYviAyCQ6OQqVrx5_wY_7PMKzHddwGEFsl5ccGTE5YLg2--qxak12lAf-5ci90IDsfkesOHvWjaRlEB5s" />
            <div class="absolute bottom-0 left-0 w-full p-6 z-20">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-glow">
                        <span class="material-symbols-outlined text-xl">home_work</span>
                    </div>
                    <span class="text-primary-light font-bold tracking-wider text-sm uppercase">StaySpace</span>
                </div>
                <h1 class="text-3xl font-bold leading-tight text-white tracking-tight">Welcome Back</h1>
                <p class="text-gray-400 text-sm mt-1">Please enter your details to sign in.</p>
            </div>
        </div>
        <!-- Main Content Area -->
        <div class="flex-1 bg-background-dark sm:bg-background-dark/50 sm:backdrop-blur-xl sm:border sm:border-white/5 sm:rounded-b-3xl px-6 pb-8 pt-2 flex flex-col">
            <!-- Form Fields -->
            <form method="POST" action="" class="space-y-5">
                <?php if (!empty($error)): ?>
                    <div class="text-red-500 text-sm mb-2">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <!-- Email Input -->
                <div class="space-y-2 group">
                    <label class="text-sm font-medium text-gray-300 ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-primary-light">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <input name="email" class="block w-full rounded-xl border-border-purple bg-surface-dark pl-11 pr-4 py-3.5 text-white placeholder-gray-500 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors duration-200 shadow-sm" placeholder="hello@example.com" type="email" required />
                    </div>
                </div>
                <!-- Password Input -->
                <div class="space-y-2 group">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-sm font-medium text-gray-300">Password</label>
                        <a class="text-xs font-medium text-primary-light hover:text-white transition-colors" href="#">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-primary-light">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <input name="password" class="block w-full rounded-xl border-border-purple bg-surface-dark pl-11 pr-12 py-3.5 text-white placeholder-gray-500 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors duration-200 shadow-sm" placeholder="Enter your password" type="password" required />
                        <button class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-white transition-colors" type="button">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </div>
                </div>
                <!-- Primary Action -->
                <button class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-4 rounded-xl shadow-glow transform transition-all active:scale-[0.98] mt-4 flex items-center justify-center gap-2" type="submit">
                    <span>Log In</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </form>
            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-background-dark px-3 text-gray-500">Or continue with</span>
                </div>
            </div>
            <!-- Social Logins -->
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center gap-2 px-4 py-3 border border-white/10 rounded-xl bg-surface-dark/50 hover:bg-surface-dark hover:border-white/20 transition-all group" type="button">
                    <svg aria-hidden="true" class="h-5 w-5 fill-white group-hover:scale-110 transition-transform" viewbox="0 0 24 24">
                        <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Google</span>
                </button>
                <button class="flex items-center justify-center gap-2 px-4 py-3 border border-white/10 rounded-xl bg-surface-dark/50 hover:bg-surface-dark hover:border-white/20 transition-all group" type="button">
                    <svg aria-hidden="true" class="h-5 w-5 fill-white group-hover:scale-110 transition-transform" fill="currentColor" viewbox="0 0 24 24">
                        <path d="M13.0729 1.94297C13.6826 1.1666 14.1169 0.0892976 13.9984 -7.23518e-05C13.0642 0.0402206 11.9336 0.655845 11.2581 1.47285C10.6559 2.18685 10.1373 3.3241 10.2673 4.38243C11.3148 4.4673 12.4326 3.85626 13.0729 1.94297ZM6.96328 10.9419C6.96328 14.1565 9.77359 15.6833 9.81438 15.7022C9.79094 15.7828 9.35266 17.3175 8.28688 18.8856C7.36984 20.2319 6.42391 21.5702 4.90969 21.6031C3.38312 21.6366 2.91031 20.6975 1.18563 20.6975C-0.539062 20.6975 -0.963281 21.5878 0.514375 21.6366C-2.02234 21.7188 -3.07344 20.3644 -4.1375 18.8359C-5.22859 17.2662 -6.07125 14.5028 -6.07125 11.6669C-6.07125 9.31343 -4.15047 7.78718 -2.00766 7.75437C-0.542813 7.72124 0.540156 8.74249 1.45547 8.74249C2.35516 8.74249 3.73828 7.5403 5.48016 7.70421C6.21625 7.73468 8.0175 8.00343 9.29344 9.87562C9.18563 9.94499 6.96328 11.2337 6.96328 10.9419Z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Apple</span>
                </button>
            </div>
            <!-- Footer -->
            <div class="mt-auto pt-8 text-center">
                <p class="text-gray-400 text-sm">
                    Don't have an account?
                    <a class="text-primary-light font-bold hover:text-white transition-colors ml-1" href="#">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>