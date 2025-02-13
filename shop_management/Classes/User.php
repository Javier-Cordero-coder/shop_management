<?php
require_once __DIR__ . "/Database.php";

class User {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function register($username, $password) {
        $conn = $this->db->getConnection();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    public function login($username, $password) {
        $conn = $this->db->getConnection();
        
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}
?>