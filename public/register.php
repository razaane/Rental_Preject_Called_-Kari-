<?php
require_once '../src/user.php';

session_start();
$error = "";
$db = new Database();
$pdo = $db->getConnection();
$user = new User($pdo);

$roles = $pdo->query("SELECT role_id, role_name FROM roles WHERE role_name != 'admin'")
    ->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'role' => $_POST['role'] ?? null
    ];
    if (!$data['role']) {
        $error = "Please select a role.";
    }

    if (!$user->register($data)) {
        $error = "This email already exists!";
    } else {
        header("Location: login.php");
        exit;
    }
}



?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Purple Desktop Register</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Configuration -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#9213ec",
                        "primary-dark": "#7a0ec4",
                        "background-light": "#f7f6f8",
                        "background-dark": "#1a1022",
                        "surface-light": "#ffffff",
                        "surface-dark": "#2a1f33",
                        "border-light": "#e9e1f0",
                        "border-dark": "#433054",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(146, 19, 236, 0.1)',
                        'glow': '0 0 15px rgba(146, 19, 236, 0.3)',
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(146, 19, 236, 0.2);
            border-radius: 20px;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .dark .glass-effect {
            background: rgba(26, 16, 34, 0.7);
        }

        /* Smooth fade for gradients */
        .gradient-mesh {
            background: radial-gradient(circle at 0% 0%, rgba(146, 19, 236, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(146, 19, 236, 0.1) 0%, transparent 50%);
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-[#160d1b] dark:text-white antialiased transition-colors duration-300">
    <div class="relative min-h-screen w-full flex flex-col overflow-x-hidden gradient-mesh">
        <!-- Top Navigation -->
        <!-- <div class="flex items-center p-4 justify-between sticky top-0 z-20 glass-effect border-b border-border-light dark:border-border-dark/30">
            <button class="flex size-10 items-center justify-center rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors text-gray-800 dark:text-white">
                <span class="material-symbols-outlined" style="font-size: 24px;">arrow_back</span>
            </button>
            <div class="flex items-center gap-2">
                <div class="size-6 rounded bg-primary flex items-center justify-center text-white font-bold text-xs">P</div>
                <h2 class="text-lg font-bold leading-tight tracking-tight text-gray-900 dark:text-white">Purple</h2>
            </div>
            <div class="size-10"></div> //Spacer for balance
        </div> -->
        <!-- Main Content -->
        <main class="flex-1 flex flex-col px-6 pt-6 pb-8 w-full max-w-md mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fade-in-up">
                <h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-white mb-2">Create your account</h1>
                <p class="text-gray-500 dark:text-gray-400 text-base font-normal">Join our community of explorers and hosts.</p>
            </div>
            <!-- Role Selection (Segmented Button) -->

            <!-- Registration Form -->
            <form method="POST" action="" class="flex flex-col gap-5">
                <?php if (!empty($error)): ?>
                    <div class="text-red-500 text-sm mb-2">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="mb-8">
                    <div class="flex h-12 w-full items-center justify-center rounded-xl bg-[#eee7f3] dark:bg-[#2a1f33] p-1 relative">
                        <?php foreach ($roles as $role): ?>
                            <label class="group flex cursor-pointer h-full flex-1 items-center justify-center rounded-lg relative z-10 transition-all duration-300">

                                <!-- Radio -->
                                <input
                                    class="peer sr-only"
                                    name="role"
                                    type="radio"
                                    value="<?= $role['role_id']; ?>"
                                    <?= $role['role_name'] === 'traveler' ? 'checked' : ''; ?> />

                                <!-- Background -->
                                <div class="absolute inset-0 bg-white dark:bg-[#3d2e47] rounded-lg shadow-sm
                        opacity-0 peer-checked:opacity-100 transition-all duration-300
                        ease-out transform scale-95 peer-checked:scale-100"></div>

                                <!-- Text -->
                                <span class="relative z-10 text-sm font-semibold text-gray-500 dark:text-gray-400
                         peer-checked:text-primary dark:peer-checked:text-primary
                         transition-colors flex items-center gap-2">

                                    <!-- Icon -->
                                    <span class="material-symbols-outlined text-[18px]">
                                        <?= $role['role_name'] === 'host' ? 'real_estate_agent' : 'explore'; ?>
                                    </span>

                                    <?= ucfirst(htmlspecialchars($role['role_name'])); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                </div>
                <!-- Full Name -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Full Name</label>
                    <div class="relative flex items-center group">
                        <input name="username" class="w-full h-12 pl-11 pr-4 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="Enter your full name" type="text" required />
                        <div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
                            <span class="material-symbols-outlined" style="font-size: 20px;">person</span>
                        </div>
                    </div>
                </div>
                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Email Address</label>
                    <div class="relative flex items-center group">
                        <input name="email" class="w-full h-12 pl-11 pr-4 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="name@example.com" type="email" required />
                        <div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
                            <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                        </div>
                    </div>
                </div>
                <!-- Password -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Password</label>
                    <div class="relative flex items-center group">
                        <input name="password" class="w-full h-12 pl-11 pr-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="Create a password" type="password" required />
                        <div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
                            <span class="material-symbols-outlined" style="font-size: 20px;">lock</span>
                        </div>
                        <button class="absolute right-3.5 text-gray-400 hover:text-primary transition-colors flex items-center cursor-pointer" type="button">
                            <span class="material-symbols-outlined" style="font-size: 20px;">visibility_off</span>
                        </button>
                    </div>
                </div>
                <!-- Confirm Password -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Confirm Password</label>
                    <div class="relative flex items-center group">
                        <input name="confirm_pass" class="w-full h-12 pl-11 pr-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="Confirm password" type="password" required />
                        <div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
                            <span class="material-symbols-outlined" style="font-size: 20px;">lock_clock</span>
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <button class="mt-4 w-full h-14 bg-gradient-to-br from-primary to-[#b04af0] hover:to-primary active:scale-[0.98] transition-all duration-200 rounded-xl text-white font-bold text-lg shadow-soft flex items-center justify-center gap-2 group/btn" type="submit">
                    <span>Create Account</span>
                    <span class="material-symbols-outlined group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-auto pt-8 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Already a member?
                    <a class="font-bold text-primary hover:text-primary-dark transition-colors" href='../public/login.php'>Log In</a>
                </p>
            </div>
        </main>
    </div>
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>

</html>