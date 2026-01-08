<?php
session_start();

require_once __DIR__ . '/../src/favorite.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /public/auth/login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$fav = new Favorite($conn);

$favorites = $fav->findUserFavorites($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mes Favoris</title>
</head>
<body>

<h1>Mes logements favoris</h1>

<?php if (empty($favorites)): ?>
    <p>Aucun favori.</p>
<?php else: ?>
    <ul>
        <?php foreach ($favorites as $rental): ?>
            <li>
                <strong><?= htmlspecialchars($rental['title']) ?></strong>
                — <?= htmlspecialchars($rental['city']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

</body>
</html>
