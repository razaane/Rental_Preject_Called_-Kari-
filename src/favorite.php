<?php
require_once __DIR__ . '/../src/database.php';

class Favorite
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Ajouter aux favoris
    public function add(int $userId, int $rentalId): bool
    {
        $sql = "INSERT IGNORE INTO favorites (user_id, rental_id)
                VALUES (:user_id, :rental_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'rental_id' => $rentalId
        ]);
    }

    // Retirer des favoris
    public function remove(int $userId, int $rentalId): bool
    {
        $sql = "DELETE FROM favorites
                WHERE user_id = :user_id AND rental_id = :rental_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'rental_id' => $rentalId
        ]);
    }

    // Vérifier si favori
    public function isFavorite(int $userId, int $rentalId): bool
    {
        $sql = "SELECT 1 FROM favorites
                WHERE user_id = :user_id AND rental_id = :rental_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'rental_id' => $rentalId
        ]);

        return (bool) $stmt->fetchColumn();
    }

    // Lister les favoris de l'utilisateur
    public function findUserFavorites(int $userId): array
    {
        $sql = "
            SELECT r.*
            FROM favorites f
            JOIN rentals r ON r.id = f.rental_id
            WHERE f.user_id = :user_id
            ORDER BY f.created_at DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
