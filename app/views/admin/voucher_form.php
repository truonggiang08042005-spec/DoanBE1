<?php
$isEdit = ($mode ?? 'create') === 'edit';
$actionUrl = $isEdit
    ? (BASE_URL . "index.php?controller=voucher&action=editVoucher&id=" . (int)($voucher['id'] ?? 0))
    : (BASE_URL . "index.php?controller=voucher&action=createVoucher");
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><?= $isEdit ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá mới' ?></h4>
        <div class="text-light opacity-75 small">Thiết lập mức giảm giá và giới hạn sử dụng.</div>
    </div>
    <a class="btn btn-outline-secondary rounded-pill fw-bold" href="<?= BASE_URL ?>index.php?controller=voucher&action=vouchers">
        <i class="bi bi-arrow-left me-1"></i>Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="admin-card p-4 p-md-5">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger rounded-4" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= $actionUrl ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-light">Mã giảm giá (Code)</label>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="text" name="code" value="<?= htmlspecialchars($voucher['code'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="VD: SUMMER2024" style="text-transform: uppercase;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-light">Số tiền giảm (VNĐ)</label>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="number" name="discount_amount" min="1000" step="1000" value="<?= htmlspecialchars((string)($voucher['discount_amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="VD: 50000" required>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-light">Giới hạn sử dụng</label>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="number" name="usage_limit" min="0" value="<?= htmlspecialchars((string)($voucher['usage_limit'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" required>
                        <div class="form-text text-light opacity-50">Nhập 0 nếu không giới hạn số lượng.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-light">Hạn sử dụng</label>
                        <?php 
                            $dateVal = '';
                            if (!empty($voucher['expires_at'])) {
                                $dateVal = date('Y-m-d\TH:i', strtotime($voucher['expires_at']));
                            }
                        ?>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="datetime-local" name="expires_at" value="<?= $dateVal ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-light">Trạng thái</label>
                        <select class="form-select form-select-lg rounded-3 bg-dark text-white border-secondary" name="status" required>
                            <option value="active" <?= ($voucher['status'] ?? '') === 'active' ? 'selected' : '' ?>>Kích hoạt</option>
                            <option value="inactive" <?= ($voucher['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Khóa tạm thời</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-gold btn-lg w-100 fw-bold rounded-3 mt-2" type="submit">
                    <?= $isEdit ? 'Cập nhật mã giảm giá' : 'Tạo mã giảm giá' ?>
                </button>
            </form>
        </div>
    </div>
</div>
