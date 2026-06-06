<?php

require_once dirname(__DIR__) . '/models/Database.php';

class User {
    private $conn;
    private $table_name = 'users';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function findById($id) {
        $query = "SELECT id, username, fullname, phone, role, password_hash, created_at
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByUsername($username) {
        $query = "SELECT id, username, fullname, phone, role, password_hash, created_at
                  FROM " . $this->table_name . "
                  WHERE username = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function createCustomer($username, $password_hash, $fullname, $phone) {
        $query = "INSERT INTO " . $this->table_name . " (username, password_hash, fullname, phone, role)
                  VALUES (?, ?, ?, ?, 'customer')";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$username, $password_hash, $fullname, $phone]);
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE role = 'customer'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getAllUsers() {
        $query = "SELECT id, username, fullname, phone, role, created_at FROM " . $this->table_name . " WHERE role = 'customer' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
