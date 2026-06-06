<?php

require_once dirname(__DIR__) . '/models/Database.php';

class Booking {
    private $conn;
    private $table_name = 'bookings';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function isPitchBooked($pitch_id, $booking_date, $start_time, $end_time) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " 
                  WHERE pitch_id = ? 
                    AND booking_date = ? 
                    AND status IN ('PENDING', 'CONFIRMED')
                    AND (start_time < ? AND end_time > ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$pitch_id, $booking_date, $end_time, $start_time]);
        return $stmt->fetchColumn() > 0;
    }

    public function createBooking($user_id, $pitch_id, $customer_name, $customer_phone, $booking_date, $start_time, $end_time, $total_price) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, pitch_id, customer_name, customer_phone, booking_date, start_time, end_time, total_price, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PENDING')";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $pitch_id, $customer_name, $customer_phone, $booking_date, $start_time, $end_time, $total_price]);
    }

    public function getBookingsByUserId($user_id) {
        $query = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status, b.created_at,
                         p.name AS pitch_name, p.type AS pitch_type
                  FROM " . $this->table_name . " b
                  INNER JOIN pitches p ON p.id = b.pitch_id
                  WHERE b.user_id = ?
                  ORDER BY b.booking_date DESC, b.start_time DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getAllBookings() {
        $query = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status, b.created_at,
                         b.customer_name, b.customer_phone,
                         p.name AS pitch_name, p.type AS pitch_type,
                         u.username AS user_username
                  FROM " . $this->table_name . " b
                  INNER JOIN pitches p ON p.id = b.pitch_id
                  LEFT JOIN users u ON u.id = b.user_id
                  ORDER BY b.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus($booking_id, $status) {
        if (!in_array($status, ['CONFIRMED', 'CANCELLED', 'PENDING'], true)) {
            return false;
        }
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $booking_id]);
    }
}
