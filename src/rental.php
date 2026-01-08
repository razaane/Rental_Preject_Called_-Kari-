<?php
require_once __DIR__ . '/database.php';
session_start();
class Rental
{
    private int $rental_id;
    private int $host_id;
    private string $title;
    private string $descreption;
    private string $adress;
    private string $city;
    private float $price_per_night;
    private int $capacity;
    private string $img_url;
    private string $available_dates;
    private PDO $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function create(array $data)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] == 2)) {
            $this->host_id = (int) $_SESSION['user_id'];
            $sql = "INSERT INTO rental(host_id,title,descreption,adress,city,price_per_night,capacity,image_url,available_dates) VALUES(?,?,?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $this->host_id,
                $data['title'],
                $data['descreption'],
                $data['adress'],
                $data['city'],
                $data['price_per_night'],
                $data['capacity'],
                $data['image_url'],
                $data['available_dates']
            ]);
        } else {
            throw new Exception("Unauthorized: Host not logged in");
        }
    }

    public function update(int $rental_id, array $data)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 2) {
            throw new Exception("Unauthorized: Host not logged in");
        }
        $this->host_id = (int) $_SESSION['user_id'];
        $sql = "UPDATE rental SET title=?,descreption=?,adress=?,city=?,price_per_night=?,capacity=?,image_url=?,available=? WHERE rental_id=? AND host_id=?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['title'],
            $data['descreption'],
            $data['adress'],
            $data['city'],
            $data['price_per_night'],
            $data['capacity'],
            $data['image_url'],
            $data['available_dates'],
            $rental_id,
            $this->host_id,
        ]);
    }

    public function delete(int $rental_id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 2) {
            throw new Exception("Unauthorized: Host not logged in");
        }
        $this->host_id = (int) $_SESSION['user_id'];
        $sql = "DELETE FROM rental WHERE rental_id=? AND host_id=?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $rental_id,
            $this->host_id
        ]);
    }

    public function findById(int $rental_id, bool $onlyHost = false)
    {
        if ($onlyHost) {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
                throw new Exception("Unauthorized: Host only");
            }
            $host_id = (int) $_SESSION['user_id'];
            $sql = "SELECT * FROM rental WHERE rental_id=? AND host_id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$rental_id, $host_id]);
        } else {
            // Traveler/public: only check rental exists
            $sql = "SELECT * FROM rental WHERE rental_id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$rental_id]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function findAllByHost(int $host_id)
    {
        $sql = "SELECT * FROM rental 
        WHERE host_id=?
        ORDER BY rental_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$host_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function search(array $criteria, int $limit = 10, int $offset = 0)
    {
        $sql = "SELECT * FROM rental WHERE 1=1";
        $params = [];

        if (!empty($criteria['city'])) {
            $sql .= " AND city LIKE :city";
            $params[':city'] = "%" . $criteria['city'] . "%";
        }
        if (!empty($criteria['min_price'])) {
            $sql .= " AND price_per_night >= :min_price";
            $params[':min_price'] = $criteria['min_price'];
        }
        if (!empty($criteria['max_price'])) {
            $sql .= " AND price_per_night <= :max_price";
            $params[':max_price'] = $criteria['max_price'];
        }
        if (!empty($criteria['start_date']) && !empty($criteria['end_date'])) {
            $sql .= " AND available_dates BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $criteria['start_date'];
            $params[':end_date'] = $criteria['end_date'];
        }

        $sql .= " ORDER BY rental_id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearchResults(array $criteria)
    {
        $sql = "SELECT COUNT(*) as total FROM rental WHERE 1=1";
        $params = [];

        if (!empty($criteria['city'])) {
            $sql .= " AND city LIKE :city";
            $params[':city'] = "%" . $criteria['city'] . "%";
        }
        if (!empty($criteria['min_price'])) {
            $sql .= " AND price_per_night >= :min_price";
            $params[':min_price'] = $criteria['min_price'];
        }
        if (!empty($criteria['max_price'])) {
            $sql .= " AND price_per_night <= :max_price";
            $params[':max_price'] = $criteria['max_price'];
        }
        if (!empty($criteria['start_date']) && !empty($criteria['end_date'])) {
            $sql .= " AND available_dates BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $criteria['start_date'];
            $params[':end_date'] = $criteria['end_date'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
    public function findAllPublic()
    {
        $sql = "SELECT rental_id, title, city, price_per_night, image_url
            FROM rental
            ORDER BY rental_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
