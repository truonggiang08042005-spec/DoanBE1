<?php
/**
 * @var array $pitch
 * @var array $old
 * @var string|null $error
 */
$isLoggedIn = !empty($_SESSION['user']);
$prefillName = $isLoggedIn ? ($_SESSION['user']['fullname'] ?? '') : '';
$prefillPhone = $isLoggedIn ? ($_SESSION['user']['phone'] ?? '') : '';

$customerNameValue = $old['customer_name'] ?? $prefillName;
$customerPhoneValue = $old['customer_phone'] ?? $prefillPhone;
$bookingDateValue = $old['booking_date'] ?? '';
$startTimeValue = $old['start_time'] ?? '';
$endTimeValue = $old['end_time'] ?? '';
$isMaintenance = ($pitch['status'] ?? 'active') !== 'active';
?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-black mb-1"><?= htmlspecialchars($pitch['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    <span class="badge badge-sport rounded-pill"><?= htmlspecialchars($pitch['type'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if ($isMaintenance): ?>
                        <span class="badge text-bg-warning rounded-pill">Maintenance</span>
                    <?php else: ?>
                        <span class="badge text-bg-success rounded-pill">Active</span>
                    <?php endif; ?>
                </div>

                <div class="p-3 rounded-4 border bg-light">
                    <div class="text-muted small">Giá theo giờ</div>
                    <div class="fs-4 fw-black text-success mb-1"><?= number_format((float)($pitch['price_per_hour'] ?? 0)) ?>đ</div>
                    <div class="text-muted small">Tối thiểu 1 tiếng. Vui lòng chọn khung giờ hợp lệ.</div>
                </div>

                <a href="<?= BASE_URL ?>index.php" class="btn btn-outline-secondary w-100 mt-3 rounded-3">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h4 class="fw-black mb-3">Thông tin đặt lịch</h4>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger rounded-4" role="alert">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if ($isMaintenance): ?>
                    <div class="alert alert-warning rounded-4 mb-0" role="alert">
                        Sân đang bảo trì, vui lòng chọn sân khác.
                    </div>
                <?php else: ?>
                    <form action="<?= BASE_URL ?>index.php?controller=booking&action=store" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="pitch_id" value="<?= $pitch['id'] ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tên đại diện đội</label>
                                <input type="text" name="customer_name" class="form-control form-control-lg rounded-3" value="<?= htmlspecialchars($customerNameValue, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="tel" name="customer_phone" class="form-control form-control-lg rounded-3" value="<?= htmlspecialchars($customerPhoneValue, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Ngày đá</label>
                                <input type="date" name="booking_date" class="form-control form-control-lg rounded-3" min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($bookingDateValue, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Giờ bắt đầu</label>
                                <input type="time" name="start_time" class="form-control form-control-lg rounded-3" value="<?= htmlspecialchars($startTimeValue, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Giờ kết thúc</label>
                                <input type="time" name="end_time" class="form-control form-control-lg rounded-3" value="<?= htmlspecialchars($endTimeValue, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-3 mt-4">
                            <i class="bi bi-calendar2-check me-1"></i>Xác nhận đặt sân
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>