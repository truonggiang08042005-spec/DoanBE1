<?php

require_once dirname(__DIR__) . '/models/Pitch.php';

class HomeController {
    private $pitchModel;

    public function __construct() {
        $this->pitchModel = new Pitch();
    }

    public function index() {
        $keyword = trim($_GET['q'] ?? '');
        $category_id = trim($_GET['category_id'] ?? '');
        $location = trim($_GET['location'] ?? '');

        $pitches = $this->pitchModel->searchActivePitches($keyword, $category_id, $location);
        
        $categories = []; // Default to empty array
        try {
            require_once dirname(__DIR__) . '/models/Category.php';
            $categoryModel = new Category();
            $categories_data = $categoryModel->getAllCategories();
            if (is_array($categories_data)) {
                $categories = $categories_data;
            }
        } catch (Exception $e) {
            // In a real application, you would log this error.
            // For now, we ensure the page doesn't crash by keeping $categories as an empty array.
        }
        
        // Load view
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/home/index.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function vouchers() {
        require_once dirname(__DIR__) . '/models/Voucher.php';
        $voucherModel = new Voucher();

        $user_id = !empty($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
        
        $availableVouchers = [];
        $claimedVouchers = [];

        if ($user_id) {
            $availableVouchers = $voucherModel->getAvailableVouchersToClaim($user_id);
            $claimedVouchers = $voucherModel->getUserClaimedVouchers($user_id);
        } else {
            // Nếu chưa đăng nhập thì lấy tất cả voucher active để show (nhưng không cho claim)
            $availableVouchers = $voucherModel->getAllVouchers();
            $availableVouchers = array_filter($availableVouchers, function($v) {
                return $v['status'] === 'active' && strtotime($v['expires_at']) > time() && ($v['usage_limit'] == 0 || $v['used_count'] < $v['usage_limit']);
            });
        }

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/home/vouchers.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function claimVoucher() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?controller=home&action=vouchers");
            exit();
        }

        if (empty($_SESSION['user'])) {
            $_SESSION['flash_error'] = "Vui lòng đăng nhập để lưu mã giảm giá.";
            header("Location: " . BASE_URL . "index.php?controller=auth&action=login");
            exit();
        }

        $user_id = (int)$_SESSION['user']['id'];
        $voucher_id = (int)($_POST['voucher_id'] ?? 0);

        if ($voucher_id > 0) {
            require_once dirname(__DIR__) . '/models/Voucher.php';
            $voucherModel = new Voucher();
            $result = $voucherModel->claimVoucher($user_id, $voucher_id);

            if ($result) {
                $_SESSION['flash_success'] = "Đã lưu mã giảm giá thành công. Bạn có thể sử dụng khi đặt sân.";
            } else {
                $_SESSION['flash_error'] = "Lưu mã thất bại. Có thể bạn đã lưu mã này rồi hoặc mã đã hết hạn.";
            }
        }

        header("Location: " . BASE_URL . "index.php?controller=home&action=vouchers");
        exit();
    }
}