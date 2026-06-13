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

    public function users() {
        $this->requireAdmin();
        
        require_once dirname(__DIR__) . '/models/User.php';
        $userModel = new User();
        $users = $userModel->getAllUsers();

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/users.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function editUser() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/User.php';
        $userModel = new User();

        $id = (int)($_GET['id'] ?? 0);
        $user = $userModel->findById($id);
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
                $updated = $userModel->updateByAdmin($id, $fullname, $phone, $role, $status);
                if ($updated) {
                    // Nếu admin tự sửa thông tin của chính mình, cập nhật lại session
                    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id) {
                        $_SESSION['user']['fullname'] = $fullname;
                    }

                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_USER', "Cập nhật người dùng ID {$id}");
                    $_SESSION['flash_success'] = "Cập nhật thông tin người dùng thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=users");
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
                require_once dirname(__DIR__) . '/models/User.php';
                $userModel = new User();
                $newStatus = ($currentStatus === 'locked') ? 'active' : 'locked';
                $userModel->updateStatus($id, $newStatus);
                $actionType = ($newStatus === 'locked') ? 'LOCK_USER' : 'UNLOCK_USER';
                $this->activityLogModel->logAction($_SESSION['user']['id'], $actionType, ($newStatus === 'locked' ? 'Khóa' : 'Mở khóa') . " người dùng ID {$id}");
                $_SESSION['flash_success'] = ($newStatus === 'locked') ? "Đã khóa tài khoản." : "Đã mở khóa tài khoản.";
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=admin&action=users");
        exit();
    }

    public function deleteUser() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                require_once dirname(__DIR__) . '/models/User.php';
                $userModel = new User();
                
                // Check if user has bookings
                $bookings = $this->bookingModel->getBookingsByUserId($id);
                if (!empty($bookings)) {
                    $_SESSION['flash_error'] = "Không thể xóa người dùng này vì họ đã có lịch sử đặt sân.";
                } else {
                    $userModel->deleteUser($id);
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_USER', "Xóa người dùng ID {$id}");
                    $_SESSION['flash_success'] = "Đã xóa người dùng thành công.";
                }
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=admin&action=users");
        exit();
    }

    public function bookings() {
        $this->requireAdmin();

        $bookings = $this->bookingModel->getActiveBookings();

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/bookings.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function updateBookingStatus() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?controller=admin&action=bookings");
            exit();
        }

        $booking_id = (int)($_POST['booking_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($booking_id > 0 && in_array($status, ['CONFIRMED', 'CANCELLED', 'PAID'], true)) {
            $this->bookingModel->updateStatus($booking_id, $status);
            $actionDesc = "Cập nhật trạng thái đơn đặt sân ID {$booking_id} thành {$status}";
            if ($status === 'PAID') {
                $actionDesc = "Xác nhận đã thanh toán đơn đặt sân ID {$booking_id}";
            }
            $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_BOOKING', $actionDesc);
            $_SESSION['flash_success'] = "Cập nhật trạng thái đơn đặt sân thành công.";
        } else {
            $_SESSION['flash_error'] = "Dữ liệu không hợp lệ.";
        }

        header("Location: " . BASE_URL . "index.php?controller=admin&action=bookings");
        exit();
    }

    public function pitches() {
        $this->requireAdmin();

        $pitches = $this->pitchModel->getAllPitches();

        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/pitches.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function createPitch() {
        $this->requireAdmin();

        require_once dirname(__DIR__) . '/models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        $error = '';
        $pitch = [
            'name' => '',
            'category_id' => '',
            'price_per_hour' => '',
            'status' => 'active',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pitch['name'] = trim($_POST['name'] ?? '');
            $pitch['category_id'] = trim($_POST['category_id'] ?? '');
            $pitch['price_per_hour'] = trim($_POST['price_per_hour'] ?? '');
            $pitch['status'] = trim($_POST['status'] ?? '');

            if ($pitch['name'] === '' || $pitch['price_per_hour'] === '' || $pitch['category_id'] === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } elseif (!is_numeric($pitch['price_per_hour']) || (float)$pitch['price_per_hour'] <= 0) {
                $error = 'Giá theo giờ không hợp lệ.';
            } else {
                $created = $this->pitchModel->createPitch($pitch['name'], (int)$pitch['category_id'], (float)$pitch['price_per_hour'], $pitch['status']);
                if ($created) {
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'CREATE_PITCH', "Tạo sân bóng mới: {$pitch['name']}");
                    $_SESSION['flash_success'] = "Tạo sân bóng thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=pitches");
                    exit();
                }
                $error = 'Không thể tạo sân.';
            }
        }

        $mode = 'create';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/pitch_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function editPitch() {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $pitch = $this->pitchModel->getPitchById($id);
        if (!$pitch) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        require_once dirname(__DIR__) . '/models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            $price_per_hour = trim($_POST['price_per_hour'] ?? '');
            $status = trim($_POST['status'] ?? '');

            if ($name === '' || $price_per_hour === '' || $category_id === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } elseif (!is_numeric($price_per_hour) || (float)$price_per_hour <= 0) {
                $error = 'Giá theo giờ không hợp lệ.';
            } else {
                $updated = $this->pitchModel->updatePitch($id, $name, (int)$category_id, (float)$price_per_hour, $status);
                if ($updated) {
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_PITCH', "Cập nhật sân bóng ID {$id}");
                    $_SESSION['flash_success'] = "Cập nhật sân bóng thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=pitches");
                    exit();
                }
                $error = 'Không thể cập nhật sân.';
            }

            $pitch['name'] = $name;
            $pitch['category_id'] = $category_id;
            $pitch['price_per_hour'] = $price_per_hour;
            $pitch['status'] = $status;
        }

        $mode = 'edit';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/pitch_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function deletePitch() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $hasBookings = $this->bookingModel->hasBookingsForPitch($id);
                if ($hasBookings) {
                    $_SESSION['flash_error'] = "Không thể xóa sân này vì đã có lịch sử đặt sân. Vui lòng đổi trạng thái sang Bảo trì.";
                } else {
                    $this->pitchModel->deletePitch($id);
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_PITCH', "Xóa sân bóng ID {$id}");
                    $_SESSION['flash_success'] = "Đã xóa sân thành công.";
                }
            }
        }

        header("Location: " . BASE_URL . "index.php?controller=admin&action=pitches");
        exit();
    }

    // --- CATEGORY MANAGEMENT ---
    
    public function categories() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/categories.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function createCategory() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Category.php';
        $categoryModel = new Category();

        $error = '';
        $category = ['name' => '', 'description' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category['name'] = trim($_POST['name'] ?? '');
            $category['description'] = trim($_POST['description'] ?? '');

            if ($category['name'] === '') {
                $error = 'Vui lòng nhập tên danh mục.';
            } else {
                $created = $categoryModel->createCategory($category['name'], $category['description']);
                if ($created) {
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'CREATE_CATEGORY', "Tạo danh mục mới: {$category['name']}");
                    $_SESSION['flash_success'] = "Thêm danh mục thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=categories");
                    exit();
                }
                $error = 'Không thể tạo danh mục.';
            }
        }

        $mode = 'create';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/category_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function editCategory() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Category.php';
        $categoryModel = new Category();

        $id = (int)($_GET['id'] ?? 0);
        $category = $categoryModel->getCategoryById($id);
        if (!$category) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $error = 'Vui lòng nhập tên danh mục.';
            } else {
                $updated = $categoryModel->updateCategory($id, $name, $description);
                if ($updated) {
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_CATEGORY', "Cập nhật danh mục ID {$id}");
                    $_SESSION['flash_success'] = "Cập nhật danh mục thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=categories");
                    exit();
                }
                $error = 'Không thể cập nhật danh mục.';
            }
            $category['name'] = $name;
            $category['description'] = $description;
        }

        $mode = 'edit';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/category_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function deleteCategory() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                require_once dirname(__DIR__) . '/models/Category.php';
                $categoryModel = new Category();
                // Check if any pitches are using this category. If yes, prevent delete.
                $pitches = $this->pitchModel->getAllPitches();
                $hasPitches = false;
                foreach ($pitches as $p) {
                    if ((int)$p['category_id'] === $id) {
                        $hasPitches = true; break;
                    }
                }
                
                if ($hasPitches) {
                    $_SESSION['flash_error'] = "Không thể xóa danh mục này vì đang có sân thuộc danh mục này.";
                } else {
                    $categoryModel->deleteCategory($id);
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_CATEGORY', "Xóa danh mục ID {$id}");
                    $_SESSION['flash_success'] = "Đã xóa danh mục thành công.";
                }
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=admin&action=categories");
        exit();
    }

    // --- REVIEWS MANAGEMENT ---
    
    public function reviews() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Review.php';
        $reviewModel = new Review();
        $reviews = $reviewModel->getPendingReviews();
        
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/reviews.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function replyReview() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['review_id'] ?? 0);
            $admin_reply = trim($_POST['admin_reply'] ?? '');
            
            if ($id > 0 && $admin_reply !== '') {
                require_once dirname(__DIR__) . '/models/Review.php';
                $reviewModel = new Review();
                $reviewModel->replyReview($id, $admin_reply);
                $this->activityLogModel->logAction($_SESSION['user']['id'], 'REPLY_REVIEW', "Phản hồi đánh giá ID {$id}");
                $_SESSION['flash_success'] = "Đã gửi phản hồi đánh giá thành công.";
            } else {
                $_SESSION['flash_error'] = "Vui lòng nhập nội dung phản hồi.";
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=admin&action=reviews");
        exit();
    }

    // --- VOUCHERS MANAGEMENT ---
    public function vouchers() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Voucher.php';
        $voucherModel = new Voucher();
        $vouchers = $voucherModel->getAllVouchers();
        
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/vouchers.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function createVoucher() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Voucher.php';
        $voucherModel = new Voucher();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $discount_amount = (float)($_POST['discount_amount'] ?? 0);
            $usage_limit = (int)($_POST['usage_limit'] ?? 0);
            $expires_at = $_POST['expires_at'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if ($code === '' || $discount_amount <= 0 || $expires_at === '') {
                $error = "Vui lòng nhập đầy đủ Mã, Số tiền giảm và Ngày hết hạn.";
            } else {
                if ($voucherModel->getVoucherByCode($code)) {
                    $error = "Mã giảm giá này đã tồn tại.";
                } else {
                    if ($voucherModel->createVoucher($code, $discount_amount, $usage_limit, $expires_at, $status)) {
                        $this->activityLogModel->logAction($_SESSION['user']['id'], 'CREATE_VOUCHER', "Tạo mã giảm giá mới: {$code}");
                        $_SESSION['flash_success'] = "Tạo mã giảm giá thành công.";
                        header("Location: " . BASE_URL . "index.php?controller=admin&action=vouchers");
                        exit();
                    } else {
                        $error = "Không thể tạo mã giảm giá.";
                    }
                }
            }
            $voucher = [
                'code' => $code,
                'discount_amount' => $discount_amount,
                'usage_limit' => $usage_limit,
                'expires_at' => $expires_at,
                'status' => $status
            ];
        }

        $mode = 'create';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/voucher_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function editVoucher() {
        $this->requireAdmin();
        require_once dirname(__DIR__) . '/models/Voucher.php';
        $voucherModel = new Voucher();
        
        $id = (int)($_GET['id'] ?? 0);
        $voucher = $voucherModel->getVoucherById($id);
        if (!$voucher) {
            header("Location: " . BASE_URL . "index.php?controller=admin&action=vouchers");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $discount_amount = (float)($_POST['discount_amount'] ?? 0);
            $usage_limit = (int)($_POST['usage_limit'] ?? 0);
            $expires_at = $_POST['expires_at'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if ($code === '' || $discount_amount <= 0 || $expires_at === '') {
                $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
            } else {
                $existing = $voucherModel->getVoucherByCode($code);
                if ($existing && $existing['id'] != $id) {
                    $error = "Mã giảm giá này đã được sử dụng.";
                } else {
                    if ($voucherModel->updateVoucher($id, $code, $discount_amount, $usage_limit, $expires_at, $status)) {
                        $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_VOUCHER', "Cập nhật mã giảm giá ID {$id} ({$code})");
                        $_SESSION['flash_success'] = "Cập nhật mã giảm giá thành công.";
                        header("Location: " . BASE_URL . "index.php?controller=admin&action=vouchers");
                        exit();
                    } else {
                        $error = "Không thể cập nhật mã.";
                    }
                }
            }
            $voucher['code'] = $code;
            $voucher['discount_amount'] = $discount_amount;
            $voucher['usage_limit'] = $usage_limit;
            $voucher['expires_at'] = $expires_at;
            $voucher['status'] = $status;
        }

        $mode = 'edit';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/voucher_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function deleteVoucher() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                require_once dirname(__DIR__) . '/models/Voucher.php';
                $voucherModel = new Voucher();
                $voucherModel->deleteVoucher($id);
                $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_VOUCHER', "Xóa mã giảm giá ID {$id}");
                $_SESSION['flash_success'] = "Đã xóa mã giảm giá.";
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=admin&action=vouchers");
        exit();
    }

    // --- EXPORT TO CSV ---
    
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

    public function exportBookings() {
        $this->requireAdmin();
        $bookings = $this->bookingModel->getAllBookings();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=bookings_export_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM
        fputcsv($output, ['ID Đơn', 'Khách hàng', 'SĐT', 'Sân', 'Ngày đá', 'Giờ đá', 'Giá sân', 'Mã GG', 'Giảm giá', 'Tổng tiền', 'Trạng thái', 'Ngày tạo']);

        foreach ($bookings as $b) {
            $voucher_code = ''; // Có thể join lấy code nhưng tạm bỏ qua code, hiển thị số tiền giảm
            fputcsv($output, [
                $b['id'],
                $b['customer_name'],
                $b['customer_phone'],
                $b['pitch_name'],
                $b['booking_date'],
                $b['start_time'] . ' - ' . $b['end_time'],
                $b['total_price'] + $b['discount_amount'], // Giá gốc
                $b['voucher_id'] ? 'Có' : 'Không',
                $b['discount_amount'],
                $b['total_price'], // Giá cuối cùng
                $b['status'],
                $b['created_at']
            ]);
        }
        fclose($output);
        $this->activityLogModel->logAction($_SESSION['user']['id'], 'EXPORT_BOOKINGS', "Xuất file Excel danh sách lịch đặt sân");
        exit();
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
