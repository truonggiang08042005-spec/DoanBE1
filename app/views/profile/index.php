<?php require_once dirname(dirname(dirname(__DIR__))) . '/app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-dark text-white rounded-top-4">
                    <h2 class="fw-bold mb-0">Hồ Sơ Của Bạn</h2>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Họ và tên:</div>
                        <div class="col-sm-8"><?= htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <!-- <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Email:</div>
                        <div class="col-sm-8"><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật', ENT_QUOTES, 'UTF-8') ?></div>
                    </div> -->
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Số điện thoại:</div>
                        <div class="col-sm-8"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-semibold">Vai trò:</div>
                        <div class="col-sm-8">
                            <span class="badge rounded-pill bg-primary">
                                <?= htmlspecialchars(ucfirst($user['role']), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="<?= BASE_URL ?>index.php?controller=profile&action=edit" class="btn btn-outline-primary rounded-pill fw-bold">Chỉnh sửa thông tin</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(dirname(dirname(__DIR__))) . '/app/views/layouts/footer.php'; ?>