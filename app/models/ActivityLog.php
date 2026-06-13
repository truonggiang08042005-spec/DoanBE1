<?php

require_once __DIR__ . '/Database.php';

class ActivityLog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ghi lại một hành động của admin
     *
     * @param int $admin_id
     * @param string $action_type
     * @param string $description
     * @return bool
     */
    public function logAction($admin_id, $action_type, $description) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        
        $sql = "INSERT INTO admin_activity_logs (admin_id, action_type, description, ip_address) 
                VALUES (:admin_id, :action_type, :description, :ip_address)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':admin_id' => $admin_id,
            ':action_type' => $action_type,
            ':description' => $description,
            ':ip_address' => $ip_address
        ]);
    }

    /**
     * Lấy danh sách lịch sử hoạt động
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getLogsPaginated($limit = 50, $offset = 0) {
        $sql = "SELECT l.*, u.fullname, u.username 
                FROM admin_activity_logs l
                JOIN users u ON l.admin_id = u.id
                ORDER BY l.created_at DESC
                LIMIT :limit OFFSET :offset";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm tổng số lượng log
     */
    public function getTotalLogs() {
        $sql = "SELECT COUNT(*) FROM admin_activity_logs";
        return $this->db->query($sql)->fetchColumn();
    }
}
