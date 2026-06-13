<?php

require_once dirname(__DIR__) . '/models/Database.php';

class Review {
    private $conn;
    private $table_name = 'reviews';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function createReview($user_id, $pitch_id, $booking_id, $comment, $image_path) {
        // Check if a review already exists for this booking
        if ($this->hasReviewedBooking($booking_id)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " (user_id, pitch_id, booking_id, comment, image_path, status) 
                  VALUES (?, ?, ?, ?, ?, 'PENDING')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $pitch_id, $booking_id, $comment, $image_path]);
    }

    public function hasReviewedBooking($booking_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE booking_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$booking_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getPendingReviews() {
        $query = "SELECT r.*, u.fullname, u.username, p.name AS pitch_name, b.booking_date 
                  FROM " . $this->table_name . " r
                  INNER JOIN users u ON r.user_id = u.id
                  INNER JOIN pitches p ON r.pitch_id = p.id
                  INNER JOIN bookings b ON r.booking_id = b.id
                  WHERE r.status = 'PENDING'
                  ORDER BY r.created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingReviewsCount() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE status = 'PENDING'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getReviewsByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function replyReview($id, $admin_reply) {
        $query = "UPDATE " . $this->table_name . " SET admin_reply = ?, status = 'REPLIED' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$admin_reply, $id]);
    }
}
