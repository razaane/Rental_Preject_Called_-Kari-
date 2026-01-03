<?php
session_start();
require_once "database.php";
require_once "UserInterface.php";

class User implements UserInterface{
    private PDO $conn;
    private int $id;
    private string $username;
    private string $email;
    private string $hach_pass;
    private string $role;
    
    //for automatic connexion 
    public function __construct(PDO $pdo) {
        $this->conn = $pdo;
    }

    //for register 
    public function register(array $data): bool {
        //cheks if email already exists ou non 
        if($this->findByEmail($data['email'])){
            return false;
        }
        //recuperer les données 
        $this->username =htmlspecialchars($data['username']);
        $this->email = htmlspecialchars($data['email']);
        $this->role = htmlspecialchars($data['role']);
        //pouir hacher le pass 
        $this->hach_pass = password_hash($data['password'],PASSWORD_DEFAULT);
        
    }


}
    

