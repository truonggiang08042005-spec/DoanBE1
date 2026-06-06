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
            $query = "SELECT id, name, type, price_per_hour, status
                      FROM " . $this->table_name . "
                      ORDER BY name ASC";
        } else {
            $query = "SELECT id, name, type, price_per_hour, 'active' AS status
                      FROM " . $this->table_name . "
                      ORDER BY name ASC";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchActivePitches($keyword = '', $type = '', $location = '') {
        $keyword = trim((string)$keyword);
        $type = trim((string)$type);
        $location = trim((string)$location);

        $where = $this->statusColumnExists() ? "WHERE status = 'active'" : "WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $where .= " AND name LIKE ?";
            $params[] = '%' . $keyword . '%';
        }

        if ($location !== '') {
            $where .= " AND name LIKE ?";
            $params[] = '%' . $location . '%';
        }

        if ($type !== '' && in_array($type, ['Sân 5', 'Sân 7', 'Sân 11'], true)) {
            $where .= " AND type = ?";
            $params[] = $type;
        }

        if ($this->statusColumnExists()) {
            $query = "SELECT id, name, type, price_per_hour, status
                      FROM " . $this->table_name . "
                      " . $where . "
                      ORDER BY name ASC";
        } else {
            $query = "SELECT id, name, type, price_per_hour, 'active' AS status
                      FROM " . $this->table_name . "
                      " . $where . "
                      ORDER BY name ASC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPitchById($id) {
        if ($this->statusColumnExists()) {
            $query = "SELECT id, name, type, price_per_hour, status
                      FROM " . $this->table_name . "
                      WHERE id = ?
                      LIMIT 0,1";
        } else {
            $query = "SELECT id, name, type, price_per_hour, 'active' AS status
                      FROM " . $this->table_name . "
                      WHERE id = ?
                      LIMIT 0,1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createPitch($name, $type, $price_per_hour, $status) {
        if (!in_array($type, ['Sân 5', 'Sân 7', 'Sân 11'], true)) {
            return false;
        }
        if (!in_array($status, ['active', 'maintenance'], true)) {
            return false;
        }

        if ($this->statusColumnExists()) {
            $query = "INSERT INTO " . $this->table_name . " (name, type, price_per_hour, status)
                      VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $type, $price_per_hour, $status]);
        }

        $query = "INSERT INTO " . $this->table_name . " (name, type, price_per_hour)
                  VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $type, $price_per_hour]);
    }

    public function updatePitch($id, $name, $type, $price_per_hour, $status) {
        if (!in_array($type, ['Sân 5', 'Sân 7', 'Sân 11'], true)) {
            return false;
        }
        if (!in_array($status, ['active', 'maintenance'], true)) {
            return false;
        }

        if ($this->statusColumnExists()) {
            $query = "UPDATE " . $this->table_name . "
                      SET name = ?, type = ?, price_per_hour = ?, status = ?
                      WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $type, $price_per_hour, $status, $id]);
        }

        $query = "UPDATE " . $this->table_name . "
                  SET name = ?, type = ?, price_per_hour = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $type, $price_per_hour, $id]);
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
