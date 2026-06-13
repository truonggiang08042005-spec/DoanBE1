<?php
require_once dirname(__DIR__) . '/models/Database.php';

class Voucher {
    private $conn;
    private $table_name = "vouchers";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getAllVouchers() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVoucherById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVoucherByCode($code) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE code = ? AND status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createVoucher($code, $discount_amount, $usage_limit, $expires_at, $status) {
        $query = "INSERT INTO " . $this->table_name . " (code, discount_amount, usage_limit, expires_at, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$code, $discount_amount, $usage_limit, $expires_at, $status]);
    }

    public function updateVoucher($id, $code, $discount_amount, $usage_limit, $expires_at, $status) {
        $query = "UPDATE " . $this->table_name . " SET code = ?, discount_amount = ?, usage_limit = ?, expires_at = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$code, $discount_amount, $usage_limit, $expires_at, $status, $id]);
    }

    public function deleteVoucher($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function incrementUsedCount($id) {
        $query = "UPDATE " . $this->table_name . " SET used_count = used_count + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function isValidForUse($code) {
        $voucher = $this->getVoucherByCode($code);
        if (!$voucher) return false;

        // Check expiration
        if (strtotime($voucher['expires_at']) < time()) return false;

        // Check usage limit
        if ($voucher['usage_limit'] > 0 && $voucher['used_count'] >= $voucher['usage_limit']) return false;

        return $voucher;
    }

    // --- USER VOUCHER WALLET LOGIC ---

    public function getAvailableVouchersToClaim($user_id) {
        // Lấy danh sách voucher active, chưa hết hạn, chưa đạt giới hạn sử dụng
        // VÀ user này chưa claim
        $query = "SELECT v.* FROM " . $this->table_name . " v
                  WHERE v.status = 'active'
                  AND v.expires_at > NOW()
                  AND (v.usage_limit = 0 OR v.used_count < v.usage_limit)
                  AND v.id NOT IN (
                      SELECT voucher_id FROM user_vouchers WHERE user_id = ?
                  )
                  ORDER BY v.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserClaimedVouchers($user_id) {
        // Lấy danh sách voucher user đã claim, CHƯA SỬ DỤNG, và voucher vẫn hợp lệ
        $query = "SELECT v.*, uv.claimed_at FROM user_vouchers uv
                  JOIN " . $this->table_name . " v ON uv.voucher_id = v.id
                  WHERE uv.user_id = ?
                  AND uv.is_used = 0
                  AND v.status = 'active'
                  AND v.expires_at > NOW()
                  ORDER BY uv.claimed_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function claimVoucher($user_id, $voucher_id) {
        // Kiểm tra xem đã claim chưa
        $queryCheck = "SELECT COUNT(*) FROM user_vouchers WHERE user_id = ? AND voucher_id = ?";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->execute([$user_id, $voucher_id]);
        if ($stmtCheck->fetchColumn() > 0) return false;

        $query = "INSERT INTO user_vouchers (user_id, voucher_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $voucher_id]);
    }

    public function markVoucherAsUsed($user_id, $voucher_id) {
        $query = "UPDATE user_vouchers SET is_used = 1 WHERE user_id = ? AND voucher_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $voucher_id]);
    }
}
