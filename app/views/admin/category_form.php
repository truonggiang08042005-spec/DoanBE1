<?php
$isEdit = ($mode ?? 'create') === 'edit';
$actionUrl = $isEdit
    ? (BASE_URL . "index.php?controller=category&action=editCategory&id=" . (int)($category['id'] ?? 0))
    : (BASE_URL . "index.php?controller=category&action=createCategory");
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><?= $isEdit ? 'Sửa danh mục' : 'Thêm danh mục mới' ?></h4>
        <div class="text-muted small">Phân loại và quản lý các loại sân bóng.</div>
    </div>
    <a class="btn btn-outline-secondary rounded-pill fw-bold" href="<?= BASE_URL ?>index.php?controller=category&action=categories">
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
                    <label class="form-label fw-semibold text-light">Tên danh mục <span class="text-danger">*</span></label>
                    <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="text" name="name" value="<?= htmlspecialchars($category['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="VD: Sân 5, Sân 7, Sân Tennis..." required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-light">Mô tả chi tiết</label>
                    <textarea class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" name="description" rows="4" placeholder="Mô tả cụ thể về loại hình thể thao này..."><?= htmlspecialchars($category['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <button class="btn btn-gold btn-lg w-100 fw-bold rounded-3 mt-2" type="submit">
                    <?= $isEdit ? 'Cập nhật danh mục' : 'Lưu danh mục' ?>
                </button>
            </form>
        </div>
    </div>
</div>
