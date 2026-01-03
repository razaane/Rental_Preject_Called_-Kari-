<?php
class Database
{
    private $host = "localhost";
    private $dbname = "kari";
    private $username = "root";
    private $password = "";
    private PDO $conn;

    public function getConnection()
    {
        try {
            $pdo = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $this->conn = new PDO($pdo, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $erreur) {
            echo "Connexion Failed !" . $erreur->getMessage();
        }
        return $this->conn;
    }
}
