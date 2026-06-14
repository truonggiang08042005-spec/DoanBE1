<?php

require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '/models/Booking.php';
require_once dirname(__DIR__) . '/models/ActivityLog.php';

class UserController {
    private $userModel;
    private $bookingModel;
    private $activityLogModel;

    public function __construct() {
        $this->userModel = new User();
        $this->bookingModel = new Booking();
        $this->activityLogModel = new ActivityLog();
    }

    private function requireAdmin() {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden";
            exit();
        }
    }

    public function users() {
        $this->requireAdmin();
        $users = $this->userModel->getAllUsers();

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/users.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function editUser() {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        if (!$user) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $status = trim($_POST['status'] ?? '');

            if ($fullname === '' || $phone === '') {
                $error = 'Vui lòng nhập họ tên và số điện thoại.';
            } else {
                $updated = $this->userModel->updateByAdmin($id, $fullname, $phone, $role, $status);
                if ($updated) {
                    // Nếu admin tự sửa thông tin của chính mình, cập nhật lại session
                    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id) {
                        $_SESSION['user']['fullname'] = $fullname;
                    }

                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_USER', "Cập nhật người dùng ID {$id}");
                    $_SESSION['flash_success'] = "Cập nhật thông tin người dùng thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=user&action=users");
                    exit();
                }
                $error = 'Không thể cập nhật người dùng.';
            }
            $user['fullname'] = $fullname;
            $user['phone'] = $phone;
            $user['role'] = $role;
            $user['status'] = $status;
        }

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/user_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function lockUser() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $currentStatus = $_POST['current_status'] ?? '';
            
            if ($id > 0) {
                $newStatus = ($currentStatus === 'locked') ? 'active' : 'locked';
                $this->userModel->updateStatus($id, $newStatus);
                $actionType = ($newStatus === 'locked') ? 'LOCK_USER' : 'UNLOCK_USER';
                $this->activityLogModel->logAction($_SESSION['user']['id'], $actionType, ($newStatus === 'locked' ? 'Khóa' : 'Mở khóa') . " người dùng ID {$id}");
                $_SESSION['flash_success'] = ($newStatus === 'locked') ? "Đã khóa tài khoản." : "Đã mở khóa tài khoản.";
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=user&action=users");
        exit();
    }

    public function deleteUser() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                // Check if user has bookings
                $bookings = $this->bookingModel->getBookingsByUserId($id);
                if (!empty($bookings)) {
                    $_SESSION['flash_error'] = "Không thể xóa người dùng này vì họ đã có lịch sử đặt sân.";
                } else {
                    $this->userModel->deleteUser($id);
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_USER', "Xóa người dùng ID {$id}");
                    $_SESSION['flash_success'] = "Đã xóa người dùng thành công.";
                }
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=user&action=users");
        exit();
    }

    public function exportUsers() {
        $this->requireAdmin();
        $users = $this->userModel->getAllUsers();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=users_export_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM
        fputcsv($output, ['ID', 'Họ và tên', 'Tên đăng nhập', 'Email', 'Số điện thoại', 'Quyền hạn', 'Trạng thái', 'Ngày đăng ký']);

        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['fullname'],
                $user['username'],
                $user['email'],
                $user['phone'],
                $user['role'],
                $user['status'],
                $user['created_at']
            ]);
        }
        fclose($output);
        exit();
    }
}
