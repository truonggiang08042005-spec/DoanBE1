<?php

require_once dirname(__DIR__) . '/models/Pitch.php';
require_once dirname(__DIR__) . '/models/Booking.php';
require_once dirname(__DIR__) . '/models/ActivityLog.php';

class PitchController {
    private $pitchModel;
    private $bookingModel;
    private $activityLogModel;

    public function __construct() {
        $this->pitchModel = new Pitch();
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

    public function detail() {
        $pitch = null;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $pitch = $this->pitchModel->getPitchById($id);
        }

        if (!$pitch) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found - Pitch not found.";
            return;
        }

        $error = $_SESSION['flash_error'] ?? '';
        unset($_SESSION['flash_error']);
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);

        $userVouchers = [];
        if (!empty($_SESSION['user']['id'])) {
            require_once dirname(__DIR__) . '/models/Voucher.php';
            $voucherModel = new Voucher();
            $userVouchers = $voucherModel->getUserClaimedVouchers($_SESSION['user']['id']);
        }

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/pitch/detail.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    // --- ADMIN METHODS ---

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
                $image = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = $this->uploadImage($_FILES['image']);
                    if (!$image) {
                        $error = 'Tải ảnh lên thất bại. Vui lòng kiểm tra lại tệp ảnh.';
                    }
                }

                if (!$error) {
                    $created = $this->pitchModel->createPitch($pitch['name'], (int)$pitch['category_id'], (float)$pitch['price_per_hour'], $pitch['status'], $image);
                    if ($created) {
                        $this->activityLogModel->logAction($_SESSION['user']['id'], 'CREATE_PITCH', "Tạo sân bóng mới: {$pitch['name']}");
                        $_SESSION['flash_success'] = "Tạo sân bóng thành công.";
                        header("Location: " . BASE_URL . "index.php?controller=pitch&action=pitches");
                        exit();
                    }
                    $error = 'Không thể tạo sân.';
                }
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
                $image = $pitch['image'] ?? null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = $this->uploadImage($_FILES['image']);
                    if (!$image) {
                        $error = 'Tải ảnh lên thất bại. Vui lòng kiểm tra lại tệp ảnh.';
                    }
                }

                if (!$error) {
                    $updated = $this->pitchModel->updatePitch($id, $name, (int)$category_id, (float)$price_per_hour, $status, $image);
                    if ($updated) {
                        $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_PITCH', "Cập nhật sân bóng ID {$id}");
                        $_SESSION['flash_success'] = "Cập nhật sân bóng thành công.";
                        header("Location: " . BASE_URL . "index.php?controller=pitch&action=pitches");
                        exit();
                    }
                    $error = 'Không thể cập nhật sân.';
                }
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

        header("Location: " . BASE_URL . "index.php?controller=pitch&action=pitches");
        exit();
    }

    private function uploadImage($file) {
        $targetDir = dirname(__DIR__, 2) . '/public/uploads/pitches/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return false;
        }

        // Check file size (e.g., 5MB)
        if ($file['size'] > 5000000) {
            return false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return 'pitches/' . $fileName;
        }

        return false;
    }
}