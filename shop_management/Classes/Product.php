<?php
require_once __DIR__ . "/Database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Product {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    private function executeQuery($query, $params = []) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt;
    }

    public function create($name, $description, $price) {
        $query = "INSERT INTO products (name, description, price) VALUES (:name, :desc, :price)";
        return $this->executeQuery($query, [
            ':name' => $name,
            ':desc' => $description,
            ':price' => $price
        ]);
    }

    public function read() {
        $query = "SELECT * FROM products ORDER BY id DESC";
        $stmt = $this->executeQuery($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $description, $price) {
        $query = "UPDATE products SET name = :name, description = :desc, price = :price WHERE id = :id";
        return $this->executeQuery($query, [
            ':id' => $id,
            ':name' => $name,
            ':desc' => $description,
            ':price' => $price
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM products WHERE id = :id";
        return $this->executeQuery($query, [':id' => $id]);
    }
}
?>