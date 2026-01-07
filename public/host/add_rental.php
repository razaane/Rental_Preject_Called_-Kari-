<?php
require_once __DIR__ . '/../../src/rental.php';
require_once __DIR__ . '/../../src/database.php';

// Check if host is logged in
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
//     header("Location: ../../public/login.php");
//     exit;
// }

$db = new Database();
$conn = $db->getConnection();
$rental = new Rental($conn);

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'descreption' => $_POST['descreption'],
        'adress' => $_POST['adress'],
        'city' => $_POST['city'],
        'price_per_night' => $_POST['price_per_night'],
        'capacity' => $_POST['capacity'],
        'image_url' => $_POST['image_url'],
        'available_dates' => $_POST['available_dates'],
    ];

    if ($rental->create($data)) {
        $success = "Property added successfully!";
    } else {
        $error = "Failed to add property.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Rental</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
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
                "xl": "0.75rem"
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(146, 19, 236, 0.1)',
                'glow': '0 0 15px rgba(146, 19, 236, 0.3)',
            }
        },
    }
}
</script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#160d1b] dark:text-white antialiased p-8">

<div class="max-w-2xl mx-auto bg-surface-light dark:bg-surface-dark p-8 rounded-xl shadow-soft">
    <h1 class="text-2xl font-bold text-primary mb-4">Add New Rental</h1>

    <?php if($success): ?>
        <p class="text-green-600 mb-4"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if($error): ?>
        <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <!-- Title -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-primary mb-1">Property Title</label>
            <input required type="text" name="title" placeholder="Modern Downtown Loft"
                class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
        </div>

        <!-- Description -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-primary mb-1">Description</label>
            <textarea required name="descreption" rows="4" placeholder="Describe the property details..."
                class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"></textarea>
        </div>

        <!-- Address & City -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-primary mb-1">Address</label>
                <input required type="text" name="adress" placeholder="123 Luxury Ave"
                    class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-primary mb-1">City</label>
                <input required type="text" name="city" placeholder="San Francisco"
                    class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
            </div>
        </div>

        <!-- Price & Capacity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-primary mb-1">Price Per Night ($)</label>
                <input required type="number" name="price_per_night" min="0"
                    class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-primary mb-1">Capacity (Guests)</label>
                <input required type="number" name="capacity" min="1"
                    class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
            </div>
        </div>

        <!-- Image URL -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-primary mb-1">Image URL</label>
            <input required type="url" name="image_url" placeholder="https://..."
                class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
        </div>

        <!-- Available Dates -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-primary mb-1">Available Dates</label>
            <input required type="text" name="available_dates" placeholder="2024-10-12 to 2024-10-20"
                class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl p-3 focus:ring-2 focus:ring-primary focus:border-primary transition"/>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4 pt-4 border-t border-border-light dark:border-border-dark">
            <a href="dashboard.php"
               class="px-6 py-2.5 font-bold text-primary border border-primary rounded-xl hover:bg-primary hover:text-white transition">
               Cancel
            </a>
            <button type="submit"
                class="px-8 py-2.5 bg-gradient-to-r from-primary to-primary-dark text-white font-bold rounded-xl shadow-glow hover:shadow-soft transition">
                Add Property
            </button>
        </div>
    </form>
</div>

</body>
</html>
