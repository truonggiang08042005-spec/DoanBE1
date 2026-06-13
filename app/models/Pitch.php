<?php

require_once dirname(__DIR__) . '/models/Database.php';

class Pitch {
    private $conn;
    private $table_name = 'pitches';
    private $hasStatusColumn = null;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    private function statusColumnExists() {
        if ($this->hasStatusColumn !== null) {
            return $this->hasStatusColumn;
        }

        $stmt = $this->conn->prepare(
            "SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = 'status'"
        );
        $stmt->execute([DB_NAME, $this->table_name]);
        $this->hasStatusColumn = ((int)$stmt->fetchColumn() > 0);
        return $this->hasStatusColumn;
    }

    public function getAllPitches() {
        if ($this->statusColumnExists()) {
            $query = "SELECT p.id, p.name, p.category_id, c.name AS type, p.price_per_hour, p.status
                      FROM " . $this->table_name . " p
                      LEFT JOIN categories c ON p.category_id = c.id
                      ORDER BY p.name ASC";
        } else {
            $query = "SELECT p.id, p.name, p.category_id, c.name AS type, p.price_per_hour, 'active' AS status
                      FROM " . $this->table_name . " p
                      LEFT JOIN categories c ON p.category_id = c.id
                      ORDER BY p.name ASC";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchActivePitches($keyword = '', $category_id = '', $location = '') {
        $keyword = trim((string)$keyword);
        $category_id = trim((string)$category_id);
        $location = trim((string)$location);

        $where = $this->statusColumnExists() ? "WHERE p.status = 'active'" : "WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $where .= " AND p.name LIKE ?";
            $params[] = '%' . $keyword . '%';
        }

        if ($location !== '') {
            $where .= " AND p.name LIKE ?";
            $params[] = '%' . $location . '%';
        }

        if ($category_id !== '' && is_numeric($category_id)) {
            $where .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        if ($this->statusColumnExists()) {
            $query = "SELECT p.id, p.name, p.category_id, c.name AS type, p.price_per_hour, p.status
                      FROM " . $this->table_name . " p
                      LEFT JOIN categories c ON p.category_id = c.id
                      " . $where . "
                      ORDER BY p.name ASC";
        } else {
            $query = "SELECT p.id, p.name, p.category_id, c.name AS type, p.price_per_hour, 'active' AS status
                      FROM " . $this->table_name . " p
                      LEFT JOIN categories c ON p.category_id = c.id
                      " . $where . "
                      ORDER BY p.name ASC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPitchById($id) {
        if ($this->statusColumnExists()) {
            $query = "SELECT p.id, p.name, p.category_id, c.name AS type, p.price_per_hour, p.status
                      FROM " . $this->table_name . " p
                      LEFT JOIN categories c ON p.category_id = c.id
                      WHERE p.id = ?
                      LIMIT 0,1";
        } else {
            $query = "SELECT p.id, p.name, p.category_id, c.name AS type, p.price_per_hour, 'active' AS status
                      FROM " . $this->table_name . " p
                      LEFT JOIN categories c ON p.category_id = c.id
                      WHERE p.id = ?
                      LIMIT 0,1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createPitch($name, $category_id, $price_per_hour, $status) {
        if (!in_array($status, ['active', 'maintenance'], true)) {
            return false;
        }

        if ($this->statusColumnExists()) {
            $query = "INSERT INTO " . $this->table_name . " (name, category_id, price_per_hour, status)
                      VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $category_id, $price_per_hour, $status]);
        }

        $query = "INSERT INTO " . $this->table_name . " (name, category_id, price_per_hour)
                  VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $category_id, $price_per_hour]);
    }

    public function updatePitch($id, $name, $category_id, $price_per_hour, $status) {
        if (!in_array($status, ['active', 'maintenance'], true)) {
            return false;
        }

        if ($this->statusColumnExists()) {
            $query = "UPDATE " . $this->table_name . "
                      SET name = ?, category_id = ?, price_per_hour = ?, status = ?
                      WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $category_id, $price_per_hour, $status, $id]);
        }

        $query = "UPDATE " . $this->table_name . "
                  SET name = ?, category_id = ?, price_per_hour = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $category_id, $price_per_hour, $id]);
    }

    public function deletePitch($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getTotalPitches() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
