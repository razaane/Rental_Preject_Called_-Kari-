<?php
session_start();

require_once __DIR__ . '/../src/database.php';
class Booking
{
    private int $booking_id;
    private int $rental_id;
    private int $user_id;
    private int $role_id;
    private string $start_date;
    private string $end_date;
    private float $total_price;
    private string $status;
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;

        if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
            throw new Exception("Unauthorized access");
        }

        $this->user_id = (int) $_SESSION['user_id'];
        $this->role_id = (int) $_SESSION['role'];
    }

    public function create(array $data): bool
    {
        $this->rental_id  = (int) $data['rental_id'];
        $this->start_date = $data['start_date'];
        $this->end_date   = $data['end_date'];

        $this->checkAvailability(
            $this->rental_id,
            $this->start_date,
            $this->end_date
        );

        $nights = (strtotime($this->end_date) - strtotime($this->start_date)) / 86400;
        if ($nights <= 0) {
            throw new Exception("Invalid booking dates");
        }

        $this->total_price = $nights * (float) $data['price_per_night'];
        $this->status = 'confirmed';


        $sql = "INSERT INTO bookings 
                (rental_id, user_id, start_date, end_date, total_price, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $price_per_night= $this->getRentalPrice($this->rental_id);

        return $stmt->execute([
            $this->rental_id,
            $this->user_id,
            $this->start_date,
            $this->end_date,
            $this->total_price = $this->calculateTotalPrice(
                $this->start_date,
                $this->end_date,
                $price_per_night
            ),
            $this->status
        ]);
    }


    public function checkAvailability(
        int $rentalId,
        string $startDate,
        string $endDate
    ): bool {
        $sql = "
            SELECT COUNT(*) 
            FROM bookings
            WHERE rental_id = ?
              AND status = 'confirmed'
              AND (
                    start_date <= ?
                AND end_date >= ?
              )
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$rentalId, $endDate, $startDate]);

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Rental already booked for selected dates");
        }

        return true;
    }

    public function cancel(int $booking_id): bool
    {
        if ($this->role_id === 3) {
            $sql = "UPDATE bookings 
                    SET status='cancelled'
                    WHERE booking_id=?";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$booking_id]);
        }

        $sql = "UPDATE bookings 
                SET status='cancelled'
                WHERE booking_id=? AND user_id=?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$booking_id, $this->user_id]);
    }

    public function findUserBookings(): array
    {
        $sql = "SELECT * 
                FROM bookings
                WHERE user_id=?
                ORDER BY booking_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findRentalBookings(int $rental_id): array
    {
        $sql = "SELECT * FROM bookings WHERE rental_id=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$rental_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function calculateTotalPrice(string $start_date, string $end_date, float $price_per_night): float
    {
        $nights = (strtotime($end_date) - strtotime($start_date)) / 86400;

        if ($nights <= 0) {
            throw new Exception("Invalid booking duration");
        }

        return $nights * $price_per_night;
    }
    public function getRentalPrice(int $rental_id): float
    {
        $sql = "SELECT price_per_night FROM rental WHERE rental_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$rental_id]);

        $price = $stmt->fetchColumn();

        if (!$price) {
            throw new Exception("Rental not found");
        }

        return (float) $price;
    }
}
