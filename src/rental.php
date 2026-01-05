<?php
require_once __DIR__ . '/database.php';
session_start();
class Rental {
    private int $rental_id;
    private int $host_id;
    private string $title;
    private string $descreption ;
    private string $adress;
    private string $city ;
    private float $price_per_night ;
    private int $capacity ;
    private string $img_url;
    private string $available_dates;
    private PDO $conn;

    public function __construct($conn){
        $this->conn=$conn;

        if(!isset($_SESSION['user_id']) || $_SESSION['role']!=2){}

        $this->host_id=$_SESSION['user_id'];
    }

    public function create(array $data){
        $sql = "INSERT INTO rental(host_id,title,descreption,adress,city,price_per_night,capacity,image_url,available_dates) VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt=$this->conn->prepare($sql);
        return $stmt->execute([
            $this->host_id,
            $data['title'],
            $data['descreption'],
            $data['adress'],
            $data['city'],
            $data['price_per_night'],
            $data['capacity'],
            $data['img_url'],
            $data['available_dates']
        ]);
    }

    public function update(int $rental_id,array $data){
        $sql = "UPDATE rental SET title=?,descreption=?,adress=?,city=?,price_per_night=?,capacity=?,img_url=?,available=? WHERE rental_id=? AND host_id=?";
        $stmt=$this->conn->prepare($sql);

        return $stmt->execute([
            $data['title'],
            $data['descreption'],
            $data['adress'],
            $data['city'],
            $data['price_per_night'],
            $data['capacity'],
            $data['img_url'],
            $data['available_dates'],
            $rental_id,
            $this->host_id,
        ]);
    }

    public function delete(int $rental_id){
        $sql = "DELETE FROM rental WHERE rental_id=? AND host_id=?";
        $stmt=$this->conn->prepare($sql);

        return $stmt->execute([
            $rental_id,
            $this->host_id
        ]);
    }

    public function findById(int $rental_id){
        $sql="SELECT * FROM rental WHERE rental_id=? AND host_id=?";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute([
            $rental_id,
            $this->host_id,
        ]);
        $rental=$stmt->fetch(PDO::FETCH_ASSOC);
        return $rental ?: null;

    }

    public function findAllByHost(){
        $sql="SELECT * FROM rental 
        WHERE host_id=?
        ORDER BY rental_id DESC";

        $stmt=$this->conn->prepare($sql);

        $stmt->execute([
            $rental_id;
            $this->host_id;
        ])
    }


}