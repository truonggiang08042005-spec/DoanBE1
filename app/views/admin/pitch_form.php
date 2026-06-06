<?php
$isEdit = ($mode ?? 'create') === 'edit';
$actionUrl = $isEdit
    ? (BASE_URL . "index.php?controller=admin&action=editPitch&id=" . (int)($pitch['id'] ?? 0))
    : (BASE_URL . "index.php?controller=admin&action=createPitch");
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-black mb-1"><?= $isEdit ? 'Sửa sân' : 'Thêm sân mới' ?></h4>
        <div class="text-muted small">Quản lý thông tin và trạng thái sân.</div>
    </div>
    <a class="btn btn-outline-success rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=pitches">
        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger rounded-4" role="alert">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $actionUrl ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên sân</label>
                        <input class="form-control form-control-lg rounded-3" type="text" name="name" value="<?= htmlspecialchars($pitch['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Loại sân</label>
                            <select class="form-select form-select-lg rounded-3" name="type" required>
                                <option value="Sân 5" <?= ($pitch['type'] ?? '') === 'Sân 5' ? 'selected' : '' ?>>Sân 5</option>
                                <option value="Sân 7" <?= ($pitch['type'] ?? '') === 'Sân 7' ? 'selected' : '' ?>>Sân 7</option>
                                <option value="Sân 11" <?= ($pitch['type'] ?? '') === 'Sân 11' ? 'selected' : '' ?>>Sân 11</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Giá / giờ (VND)</label>
                            <input class="form-control form-control-lg rounded-3" type="number" name="price_per_hour" min="0" step="1000" value="<?= htmlspecialchars((string)($pitch['price_per_hour'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select class="form-select form-select-lg rounded-3" name="status" required>
                                <option value="active" <?= ($pitch['status'] ?? '') === 'active' ? 'selected' : '' ?>>active</option>
                                <option value="maintenance" <?= ($pitch['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>maintenance</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-success btn-lg w-100 fw-bold rounded-3 mt-4" type="submit">
                        <?= $isEdit ? 'Cập nhật' : 'Tạo sân' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

