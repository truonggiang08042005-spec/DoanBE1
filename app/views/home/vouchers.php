<?php
/**
 * @var array $availableVouchers
 * @var array $claimedVouchers
 * @var bool|null $isLoggedIn
 */
$isLoggedIn = !empty($_SESSION['user']);
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-black text-gold display-5"><i class="bi bi-wallet2 me-2"></i>Kho Voucher</h1>
        <p class="text-light opacity-75 fs-5">Sưu tầm mã giảm giá và tiết kiệm chi phí cho trận đấu của bạn</p>
    </div>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Ví Voucher Của Bạn (Chỉ hiện khi đã đăng nhập và có mã) -->
    <?php if ($isLoggedIn && !empty($claimedVouchers)): ?>
        <h3 class="fw-bold text-white mb-4"><i class="bi bi-ticket-detailed me-2 text-success"></i>Mã đã lưu (Ví của bạn)</h3>
        <div class="row g-4 mb-5">
            <?php foreach ($claimedVouchers as $v): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-success border-opacity-50 rounded-4 shadow-sm" style="background: rgba(25, 135, 84, 0.05);">
                        <div class="card-body p-4 text-center">
                            <div class="display-6 fw-black text-success mb-2"><?= number_format($v['discount_amount']) ?>đ</div>
                            <div class="badge bg-success rounded-pill px-3 py-2 mb-3 fs-6">Mã: <?= htmlspecialchars($v['code']) ?></div>
                            <p class="text-muted small mb-0"><i class="bi bi-clock me-1"></i>HSD: <?= date('d/m/Y H:i', strtotime($v['expires_at'])) ?></p>
                            <p class="text-muted small mb-0"><i class="bi bi-calendar-check me-1"></i>Đã lấy lúc: <?= date('d/m/Y', strtotime($v['claimed_at'])) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <a href="<?= BASE_URL ?>index.php#pitch-list" class="btn btn-outline-success rounded-pill fw-bold w-100">Dùng Ngay</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <hr class="border-secondary opacity-25 mb-5">
    <?php endif; ?>

    <!-- Danh sách Voucher để lấy -->
    <h3 class="fw-bold text-white mb-4"><i class="bi bi-gift me-2 text-gold"></i>Voucher đang phát hành</h3>
    <?php if (empty($availableVouchers)): ?>
        <div class="text-center py-5">
            <i class="bi bi-emoji-frown text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-white mt-3">Hiện chưa có thêm mã giảm giá nào!</h4>
            <p class="text-muted">Bạn vui lòng quay lại sau nhé.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($availableVouchers as $v): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-gold border-opacity-25 rounded-4 shadow-sm" style="background: rgba(212, 175, 55, 0.05);">
                        <div class="card-body p-4 text-center">
                            <div class="display-6 fw-black text-gold mb-2"><?= number_format($v['discount_amount']) ?>đ</div>
                            <h5 class="text-white fw-bold mb-3"><?= htmlspecialchars($v['code']) ?></h5>
                            <p class="text-light opacity-75 small mb-1"><i class="bi bi-clock me-1"></i>HSD: <?= date('d/m/Y H:i', strtotime($v['expires_at'])) ?></p>
                            <?php if ($v['usage_limit'] > 0): ?>
                                <div class="progress mt-3" style="height: 6px;">
                                    <?php $percent = min(100, ($v['used_count'] / $v['usage_limit']) * 100); ?>
                                    <div class="progress-bar bg-gold" role="progressbar" style="width: <?= $percent ?>%"></div>
                                </div>
                                <div class="text-end small mt-1 text-muted">Đã dùng: <?= $v['used_count'] ?>/<?= $v['usage_limit'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <?php if ($isLoggedIn): ?>
                                <form method="POST" action="<?= BASE_URL ?>index.php?controller=home&action=claimVoucher">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="voucher_id" value="<?= $v['id'] ?>">
                                    <button type="submit" class="btn btn-gold rounded-pill fw-bold w-100 shadow-sm">
                                        <i class="bi bi-download me-1"></i>Lưu mã
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>index.php?controller=auth&action=login" class="btn btn-outline-gold rounded-pill fw-bold w-100">
                                    Đăng nhập để lưu mã
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
