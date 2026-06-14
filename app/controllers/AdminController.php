<?php

require_once dirname(__DIR__) . '/models/Booking.php';
require_once dirname(__DIR__) . '/models/Pitch.php';
require_once dirname(__DIR__) . '/models/ActivityLog.php';

class AdminController {
    private $bookingModel;
    private $pitchModel;
    private $activityLogModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->pitchModel = new Pitch();
        $this->activityLogModel = new ActivityLog();
    }

    private function requireAdmin() {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden";
            exit();
        }
    }

    public function dashboard() {
        $this->requireAdmin();

        $totalBookings = $this->bookingModel->getTotalBookings();
        $totalRevenue = $this->bookingModel->getTotalRevenue();
        $totalPitches = $this->pitchModel->getTotalPitches();
        
        require_once dirname(__DIR__) . '/models/User.php';
        $userModel = new User();
        $totalUsers = $userModel->getTotalUsers();

        // Lấy dữ liệu doanh thu 7 ngày qua cho biểu đồ
        $revenueData = $this->bookingModel->getRevenueLast7Days();
        
        $chartLabels = [];
        $chartValuesMap = [];
        
        // Khởi tạo mảng 7 ngày gần nhất với giá trị 0
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('d/m', strtotime("-$i days"));
            $chartValuesMap[$dateStr] = 0;
        }
        
        // Cập nhật giá trị doanh thu thực tế
        foreach ($revenueData as $row) {
            if (isset($chartValuesMap[$row['date']])) {
                $chartValuesMap[$row['date']] = (float)$row['daily_revenue'];
            }
        }
        
        $chartValues = array_values($chartValuesMap);

        // Lấy 5 booking gần nhất
        $recentBookings = $this->bookingModel->getRecentBookings(5);

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/dashboard.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }



    // --- ACTIVITY LOGS ---
    public function activityLogs() {
        $this->requireAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $logs = $this->activityLogModel->getLogsPaginated($limit, $offset);
        $totalLogs = $this->activityLogModel->getTotalLogs();
        $totalPages = ceil($totalLogs / $limit);

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/activity_logs.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }
}
