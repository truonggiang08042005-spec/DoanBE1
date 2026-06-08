<?php require_once dirname(dirname(dirname(__DIR__))) . '/app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-dark text-white rounded-top-4">
                    <h2 class="fw-bold mb-0">Chỉnh Sửa Hồ Sơ</h2>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>index.php?controller=profile&action=update" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <div class="mb-3">
                            <label for="fullname" class="form-label fw-semibold">Họ và tên</label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= BASE_URL ?>index.php?controller=profile&action=index" class="btn btn-secondary rounded-pill px-4">Hủy</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(dirname(dirname(__DIR__))) . '/app/views/layouts/footer.php'; ?>