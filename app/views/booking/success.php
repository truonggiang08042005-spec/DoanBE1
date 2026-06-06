<div class="row justify-content-center py-5">
    <div class="col-md-7 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="display-4 text-success mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h2 class="fw-black mb-2">Đặt sân thành công!</h2>
                <p class="text-muted mb-4">
                    Hệ thống đã ghi nhận đơn đặt của bạn ở trạng thái <span class="fw-bold">PENDING</span>.
                    Chủ sân sẽ xác nhận sớm nhất có thể.
                </p>
                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>index.php" class="btn btn-success btn-lg fw-bold rounded-3">
                        <i class="bi bi-house-door me-1"></i>Về trang chủ
                    </a>
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a href="<?= BASE_URL ?>index.php?controller=booking&action=history" class="btn btn-outline-success btn-lg fw-bold rounded-3">
                            <i class="bi bi-clock-history me-1"></i>Xem lịch sử đặt sân
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
