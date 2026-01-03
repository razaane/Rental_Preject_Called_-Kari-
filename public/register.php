<?php 
require_once '../src/user.php';

if($_SERVER['REQUEST_METHOD' === 'POST']){
    $db = new Database();
    $pdo = $db->getConnection();
    $user = new User($pdo);

    $data = [
        'username'=>$_POST['username'],
        'email'=>$_POST['email'],
        'hash_pass'=>$_POST['password'],
        'role'=>$_POST['role']
    ];
}

?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Purple Desktop Register</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
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
<div class="flex items-center p-4 justify-between sticky top-0 z-20 glass-effect border-b border-border-light dark:border-border-dark/30">
<button class="flex size-10 items-center justify-center rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors text-gray-800 dark:text-white">
<span class="material-symbols-outlined" style="font-size: 24px;">arrow_back</span>
</button>
<div class="flex items-center gap-2">
<div class="size-6 rounded bg-primary flex items-center justify-center text-white font-bold text-xs">P</div>
<h2 class="text-lg font-bold leading-tight tracking-tight text-gray-900 dark:text-white">Purple</h2>
</div>
<div class="size-10"></div> <!-- Spacer for balance -->
</div>
<!-- Main Content -->
<main class="flex-1 flex flex-col px-6 pt-6 pb-8 w-full max-w-md mx-auto">
<!-- Header Section -->
<div class="mb-8 animate-fade-in-up">
<h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-white mb-2">Create your account</h1>
<p class="text-gray-500 dark:text-gray-400 text-base font-normal">Join our community of explorers and hosts.</p>
</div>
<!-- Role Selection (Segmented Button) -->
<div class="mb-8">
<div class="flex h-12 w-full items-center justify-center rounded-xl bg-[#eee7f3] dark:bg-[#2a1f33] p-1 relative">
<!-- Traveler Radio -->
<label class="group flex cursor-pointer h-full flex-1 items-center justify-center rounded-lg relative z-10 transition-all duration-300">
<input checked="" class="peer sr-only" name="role" type="radio" value="Traveler"/>
<div class="absolute inset-0 bg-white dark:bg-[#3d2e47] rounded-lg shadow-sm opacity-0 peer-checked:opacity-100 transition-all duration-300 ease-out transform scale-95 peer-checked:scale-100"></div>
<span class="relative z-10 text-sm font-semibold text-gray-500 dark:text-gray-400 peer-checked:text-primary dark:peer-checked:text-primary transition-colors flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">explore</span>
                            Traveler
                        </span>
</label>
<!-- Host Radio -->
<label class="group flex cursor-pointer h-full flex-1 items-center justify-center rounded-lg relative z-10 transition-all duration-300">
<input class="peer sr-only" name="role" type="radio" value="Host"/>
<div class="absolute inset-0 bg-white dark:bg-[#3d2e47] rounded-lg shadow-sm opacity-0 peer-checked:opacity-100 transition-all duration-300 ease-out transform scale-95 peer-checked:scale-100"></div>
<span class="relative z-10 text-sm font-semibold text-gray-500 dark:text-gray-400 peer-checked:text-primary dark:peer-checked:text-primary transition-colors flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">real_estate_agent</span>
                            Host
                        </span>
</label>
</div>
</div>
<!-- Registration Form -->
<form class="flex flex-col gap-5">
<!-- Full Name -->
<div class="flex flex-col gap-1.5">
<label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Full Name</label>
<div class="relative flex items-center group">
<input class="w-full h-12 pl-11 pr-4 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="Enter your full name" type="text"/>
<div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
<span class="material-symbols-outlined" style="font-size: 20px;">person</span>
</div>
</div>
</div>
<!-- Email -->
<div class="flex flex-col gap-1.5">
<label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Email Address</label>
<div class="relative flex items-center group">
<input class="w-full h-12 pl-11 pr-4 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="name@example.com" type="email"/>
<div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
<span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
</div>
</div>
</div>
<!-- Password -->
<div class="flex flex-col gap-1.5">
<label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Password</label>
<div class="relative flex items-center group">
<input class="w-full h-12 pl-11 pr-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="Create a password" type="password"/>
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
<input class="w-full h-12 pl-11 pr-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="Confirm password" type="password"/>
<div class="absolute left-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none flex items-center">
<span class="material-symbols-outlined" style="font-size: 20px;">lock_clock</span>
</div>
</div>
</div>
<!-- Submit Button -->
<button class="mt-4 w-full h-14 bg-gradient-to-br from-primary to-[#b04af0] hover:to-primary active:scale-[0.98] transition-all duration-200 rounded-xl text-white font-bold text-lg shadow-soft flex items-center justify-center gap-2 group/btn" type="button">
<span>Create Account</span>
<span class="material-symbols-outlined group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
</button>
</form>
<!-- Divider -->
<div class="flex items-center gap-4 my-8">
<div class="h-px bg-border-light dark:bg-border-dark flex-1"></div>
<span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Or continue with</span>
<div class="h-px bg-border-light dark:bg-border-dark flex-1"></div>
</div>
<!-- Social Login -->
<div class="flex gap-4 justify-center">
<button class="flex-1 h-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-[#32253d] transition-colors flex items-center justify-center gap-2 shadow-sm">
<svg class="w-5 h-5" fill="none" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
<path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
<path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
<path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
</svg>
<span class="sr-only">Google</span>
</button>
<button class="flex-1 h-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-[#32253d] transition-colors flex items-center justify-center gap-2 shadow-sm">
<svg aria-hidden="true" class="w-5 h-5 text-black dark:text-white" fill="currentColor" viewbox="0 0 24 24">
<path clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" fill-rule="evenodd"></path>
</svg>
<span class="sr-only">GitHub</span>
</button>
<button class="flex-1 h-12 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-[#32253d] transition-colors flex items-center justify-center gap-2 shadow-sm">
<svg aria-hidden="true" class="w-5 h-5 text-blue-600" fill="currentColor" viewbox="0 0 24 24">
<path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path>
</svg>
<span class="sr-only">Facebook</span>
</button>
</div>
<!-- Footer -->
<div class="mt-auto pt-8 text-center">
<p class="text-sm text-gray-600 dark:text-gray-400">
                    Already a member? 
                    <a class="font-bold text-primary hover:text-primary-dark transition-colors" href="#">Log In</a>
</p>
</div>
</main>
</div>
<!-- Decorative floating blobs for background -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
<div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 animate-blob"></div>
<div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-indigo-300 dark:bg-indigo-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
<div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
</div>
<style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
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
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body></html>