<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Cập nhật Người dùng</h4>
        <div class="text-muted small">Thay đổi thông tin liên hệ, trạng thái và cấp quyền truy cập.</div>
    </div>
    <a class="btn btn-outline-secondary rounded-pill fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=users">
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

            <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=editUser&id=<?= $user['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="mb-4">
                    <label class="form-label fw-semibold text-light">Tài khoản (Username)</label>
                    <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="text" value="<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" disabled>
                    <div class="form-text text-muted">Tên đăng nhập không thể thay đổi.</div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-light">Họ và Tên</label>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-light">Số điện thoại</label>
                        <input class="form-control form-control-lg rounded-3 bg-dark text-white border-secondary" type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-light">Vai trò (Role)</label>
                        <select class="form-select form-select-lg rounded-3 bg-dark text-white border-secondary" name="role" required>
                            <option value="customer" <?= ($user['role'] === 'customer') ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                            <?php if ($user['role'] === 'superadmin'): ?>
                                <option value="superadmin" selected>Super Admin</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-light">Trạng thái tài khoản</label>
                        <select class="form-select form-select-lg rounded-3 bg-dark text-white border-secondary" name="status" required>
                            <option value="active" <?= ($user['status'] === 'active') ? 'selected' : '' ?>>Hoạt động (Active)</option>
                            <option value="locked" <?= ($user['status'] === 'locked') ? 'selected' : '' ?>>Khóa (Locked)</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-gold btn-lg w-100 fw-bold rounded-3 mt-2" type="submit">
                    Cập nhật thông tin
                </button>
            </form>
        </div>
    </div>
</div>
