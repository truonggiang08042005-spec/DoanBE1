<?php

require_once dirname(__DIR__) . '/models/Voucher.php';
require_once dirname(__DIR__) . '/models/ActivityLog.php';

class VoucherController {
    private $voucherModel;
    private $activityLogModel;

    public function __construct() {
        $this->voucherModel = new Voucher();
        $this->activityLogModel = new ActivityLog();
    }

    private function requireAdmin() {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden";
            exit();
        }
    }

    public function vouchers() {
        $this->requireAdmin();
        $vouchers = $this->voucherModel->getAllVouchers();
        
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/vouchers.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function createVoucher() {
        $this->requireAdmin();

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $discount_amount = (float)($_POST['discount_amount'] ?? 0);
            $usage_limit = (int)($_POST['usage_limit'] ?? 0);
            $expires_at = $_POST['expires_at'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if ($code === '' || $discount_amount <= 0 || $expires_at === '') {
                $error = "Vui lòng nhập đầy đủ Mã, Số tiền giảm và Ngày hết hạn.";
            } else {
                if ($this->voucherModel->getVoucherByCode($code)) {
                    $error = "Mã giảm giá này đã tồn tại.";
                } else {
                    if ($this->voucherModel->createVoucher($code, $discount_amount, $usage_limit, $expires_at, $status)) {
                        $this->activityLogModel->logAction($_SESSION['user']['id'], 'CREATE_VOUCHER', "Tạo mã giảm giá mới: {$code}");
                        $_SESSION['flash_success'] = "Tạo mã giảm giá thành công.";
                        header("Location: " . BASE_URL . "index.php?controller=voucher&action=vouchers");
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
        
        $id = (int)($_GET['id'] ?? 0);
        $voucher = $this->voucherModel->getVoucherById($id);
        if (!$voucher) {
            header("Location: " . BASE_URL . "index.php?controller=voucher&action=vouchers");
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $discount_amount = (float)($_POST['discount_amount'] ?? 0);
            $usage_limit = (int)($_POST['usage_limit'] ?? 0);
            $expires_at = $_POST['expires_at'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if ($code === '' || $discount_amount <= 0 || $expires_at === '') {
                $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
            } else {
                $existing = $this->voucherModel->getVoucherByCode($code);
                if ($existing && $existing['id'] != $id) {
                    $error = "Mã giảm giá này đã được sử dụng.";
                } else {
                    if ($this->voucherModel->updateVoucher($id, $code, $discount_amount, $usage_limit, $expires_at, $status)) {
                        $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_VOUCHER', "Cập nhật mã giảm giá ID {$id} ({$code})");
                        $_SESSION['flash_success'] = "Cập nhật mã giảm giá thành công.";
                        header("Location: " . BASE_URL . "index.php?controller=voucher&action=vouchers");
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
                $this->voucherModel->deleteVoucher($id);
                $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_VOUCHER', "Xóa mã giảm giá ID {$id}");
                $_SESSION['flash_success'] = "Đã xóa mã giảm giá.";
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=voucher&action=vouchers");
        exit();
    }
}
