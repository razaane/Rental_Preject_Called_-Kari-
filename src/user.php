<?php

use PgSql\Result;

require_once __DIR__ . "/UserInterface.php";
require_once __DIR__ . '/database.php';

class User implements UserInterface
{
    private PDO $conn;
    private string $username;
    private string $email;
    private string $password;
    private int $role;

    public function __construct(PDO $pdo)
    {
        $this->conn = $pdo;
    }

    public function register(array $data): bool
    {
        if (empty($data['role'])) {
            return false;
        }
        if ($this->findByEmail($data['email'])) {
            return false;
        }

        $this->username = trim($data['username']);
        $this->email = trim($data['email']);
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->role = (int) $data['role'];

        $sql = "INSERT INTO users (username, email, password, role_id)
                VALUES (:username, :email, :password, :role_id)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'role_id' => $this->role
        ]);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role_name'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.user_id, u.username, u.email, u.password, r.role_name
                FROM users u
                JOIN roles r ON u.role_id = r.role_id
                WHERE u.email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function updateProfile(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (!empty($data['username'])) {
            $fields[] = "username = :username";
            $params[':username'] = htmlspecialchars($data['username']);
        }
        if (!empty($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = htmlspecialchars($data['email']);
        }
        if (!empty($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function getRole(int $id): ?string
    {
        $sql = "SELECT r.role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.role_id
        WHERE u.user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['rola_name '] : null;
    }
}
