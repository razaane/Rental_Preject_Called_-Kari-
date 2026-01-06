<?php
session_start();
require_once '../src/user.php';

$error = '';
$db = new Database();
$pdo = $db->getConnection();
$user = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $currentUser = $user->login($email, $password);

    if ($currentUser !== false) {
        //  Set session variables here
        $_SESSION['user_id'] = $currentUser['user_id'];
        $_SESSION['username'] = $currentUser['username'];
        $_SESSION['role'] = $currentUser['role_id']; // or role_name if you prefer

        if($_SESSION['role'] ===2){
            header("Location: ../public/host/dashboard.php");
            exit;
        }
        if($_SESSION['role'] === 3){
            header("Location: ..public/traveler/dashbord.php");
            exit;

        }
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
        
            <!-- Footer -->
            <div class="mt-auto pt-8 text-center">
                <p class="text-gray-400 text-sm">
                    Don't have an account?
                    <a class="text-primary-light font-bold hover:text-white transition-colors ml-1" href='../public/register.php'>Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>