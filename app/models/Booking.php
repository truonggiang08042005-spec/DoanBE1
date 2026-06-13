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

    public function createBooking($user_id, $pitch_id, $customer_name, $customer_phone, $booking_date, $start_time, $end_time, $total_price, $voucher_id = null, $discount_amount = 0) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, pitch_id, customer_name, customer_phone, booking_date, start_time, end_time, total_price, voucher_id, discount_amount, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING')";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $pitch_id, $customer_name, $customer_phone, $booking_date, $start_time, $end_time, $total_price, $voucher_id, $discount_amount]);
    }

    public function getBookingsByUserId($user_id) {
        $query = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status, b.created_at,
                         p.name AS pitch_name, c.name AS pitch_type
                  FROM " . $this->table_name . " b
                  INNER JOIN pitches p ON p.id = b.pitch_id
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE b.user_id = ?
                  ORDER BY b.booking_date DESC, b.start_time DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getAllBookings() {
        $query = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status, b.created_at,
                         b.customer_name, b.customer_phone,
                         p.name AS pitch_name, c.name AS pitch_type,
                         u.username AS user_username
                  FROM " . $this->table_name . " b
                  INNER JOIN pitches p ON p.id = b.pitch_id
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN users u ON u.id = b.user_id
                  ORDER BY b.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActiveBookings() {
        $query = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status, b.created_at,
                         b.customer_name, b.customer_phone,
                         p.name AS pitch_name, c.name AS pitch_type,
                         u.username AS user_username
                  FROM " . $this->table_name . " b
                  INNER JOIN pitches p ON p.id = b.pitch_id
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN users u ON u.id = b.user_id
                  WHERE b.status != 'PAID'
                  ORDER BY b.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRecentBookings($limit = 5) {
        $query = "SELECT b.id, b.booking_date, b.start_time, b.end_time, b.total_price, b.status, b.created_at,
                         b.customer_name, b.customer_phone,
                         p.name AS pitch_name, c.name AS pitch_type,
                         u.username AS user_username
                  FROM " . $this->table_name . " b
                  INNER JOIN pitches p ON p.id = b.pitch_id
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN users u ON u.id = b.user_id
                  ORDER BY b.created_at DESC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($booking_id, $status) {
        if (!in_array($status, ['CONFIRMED', 'CANCELLED', 'PENDING', 'PAID'], true)) {
            return false;
        }
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $booking_id]);
    }

    public function getBookingByIdAndUserId($booking_id, $user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$booking_id, $user_id]);
        return $stmt->fetch();
    }

    public function hasBookingsForPitch($pitch_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE pitch_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$pitch_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getTotalBookings() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE status = 'CONFIRMED'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalRevenue() {
        $query = "SELECT SUM(total_price) FROM " . $this->table_name . " WHERE status = 'PAID'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getRevenueLast7Days() {
        $query = "
            SELECT DATE(booking_date) as date, SUM(total_price) as daily_revenue
            FROM " . $this->table_name . "
            WHERE status = 'PAID'
              AND booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(booking_date)
            ORDER BY DATE(booking_date) ASC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
