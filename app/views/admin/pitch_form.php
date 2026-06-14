<?php
$isEdit = ($mode ?? 'create') === 'edit';
$actionUrl = $isEdit
    ? (BASE_URL . "index.php?controller=pitch&action=editPitch&id=" . (int)($pitch['id'] ?? 0))
    : (BASE_URL . "index.php?controller=pitch&action=createPitch");
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><?= $isEdit ? 'Sửa sân bóng' : 'Thêm sân bóng mới' ?></h4>
        <div class="text-light opacity-75 small">Nhập thông tin chi tiết về sân để hiển thị cho khách hàng.</div>
    </div>
    <a class="btn btn-outline-secondary rounded-pill fw-bold" href="<?= BASE_URL ?>index.php?controller=pitch&action=pitches">
        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
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
                <div class="mb-4">
                    <label class="form-label fw-semibold text-light">Tên sân</label>
                    <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="text" name="name" value="<?= htmlspecialchars($pitch['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-light">Loại sân</label>
                        <select class="form-select form-select-lg rounded-3 bg-dark text-white border-secondary" name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ((int)($pitch['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-light">Giá / giờ (VND)</label>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="number" name="price_per_hour" min="0" step="1000" value="<?= htmlspecialchars((string)($pitch['price_per_hour'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-light">Trạng thái</label>
                        <select class="form-select form-select-lg rounded-3 bg-dark text-white border-secondary" name="status" required>
                            <option value="active" <?= ($pitch['status'] ?? '') === 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                            <option value="maintenance" <?= ($pitch['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Bảo trì</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-gold btn-lg w-100 fw-bold rounded-3 mt-2" type="submit">
                    <?= $isEdit ? 'Cập nhật thông tin' : 'Tạo sân mới' ?>
                </button>
            </form>
        </div>
    </div>
</div>
