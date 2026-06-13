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
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Giá theo giờ</span>
                        <span class="fw-bold text-dark"><?= number_format((float)($pitch['price_per_hour'] ?? 0)) ?>đ <span class="fw-normal text-secondary small">(Giá 1 tiếng)</span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small" id="dynamic-duration-label">Tiền sân (1.5h)</span>
                        <span class="fw-bold text-dark" id="dynamic-base-price"><?= number_format((float)($pitch['price_per_hour'] ?? 0) * 1.5) ?>đ <span class="fw-normal text-secondary small">(Giá 1 tiếng rưỡi)</span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Giảm giá (Voucher)</span>
                        <span class="fw-bold text-success" id="dynamic-discount">-0đ</span>
                    </div>
                    <hr class="my-2 border-secondary opacity-25">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold">Tổng tiền thanh toán</span>
                        <span class="fs-4 fw-black text-danger" id="dynamic-total-price"><?= number_format((float)($pitch['price_per_hour'] ?? 0) * 1.5) ?>đ</span>
                    </div>
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
                                <label class="form-label fw-semibold">Khung giờ</label>
                                <select name="slot_time" class="form-select form-select-lg rounded-3" required>
                                    <option value="" disabled selected>-- Chọn khung giờ --</option>
                                    <?php 
                                    $slots = [
                                        '1h' => [
                                            '06:00-07:00' => '06:00 - 07:00',
                                            '07:00-08:00' => '07:00 - 08:00',
                                            '16:00-17:00' => '16:00 - 17:00',
                                            '17:00-18:00' => '17:00 - 18:00',
                                            '18:00-19:00' => '18:00 - 19:00 (Giờ vàng)'
                                        ],
                                        '1.5h' => [
                                            '05:30-07:00' => '05:30 - 07:00 (Sáng sớm)',
                                            '07:15-08:45' => '07:15 - 08:45 (Sáng)',
                                            '14:30-16:00' => '14:30 - 16:00 (Chiều)',
                                            '16:15-17:45' => '16:15 - 17:45 (Chiều tối)',
                                            '18:00-19:30' => '18:00 - 19:30 (Giờ vàng)',
                                            '19:45-21:15' => '19:45 - 21:15 (Giờ vàng)'
                                        ],
                                        '2h' => [
                                            '07:00-09:00' => '07:00 - 09:00 (Sáng)',
                                            '18:00-20:00' => '18:00 - 20:00 (Giờ vàng)',
                                            '20:00-22:00' => '20:00 - 22:00 (Tối muộn)'
                                        ]
                                    ];
                                    $oldSlot = ($old['start_time'] ?? '') . '-' . ($old['end_time'] ?? '');
                                    foreach ($slots as $duration => $groupSlots): ?>
                                        <optgroup label="Khung <?= $duration === '1h' ? '1 tiếng' : ($duration === '1.5h' ? '1 tiếng rưỡi' : '2 tiếng') ?>">
                                            <?php foreach ($groupSlots as $val => $label): ?>
                                                <option value="<?= $val ?>" data-duration="<?= $duration === '1h' ? '1' : ($duration === '1.5h' ? '1.5' : '2') ?>" <?= $oldSlot === $val ? 'selected' : '' ?>><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mã giảm giá</label>
                                <?php if ($isLoggedIn): ?>
                                    <?php if (empty($userVouchers)): ?>
                                        <select class="form-select form-select-lg rounded-3" disabled>
                                            <option>Bạn chưa có mã nào</option>
                                        </select>
                                        <div class="form-text"><a href="<?= BASE_URL ?>index.php?controller=home&action=vouchers">Vào Kho Voucher</a> để lấy mã.</div>
                                    <?php else: ?>
                                        <select name="voucher_id" class="form-select form-select-lg rounded-3">
                                            <option value="">-- Không dùng --</option>
                                            <?php foreach ($userVouchers as $v): ?>
                                                <option value="<?= $v['id'] ?>" data-discount="<?= $v['discount_amount'] ?>" <?= (isset($old['voucher_id']) && $old['voucher_id'] == $v['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($v['code']) ?> (-<?= number_format($v['discount_amount']) ?>đ)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <select class="form-select form-select-lg rounded-3" disabled>
                                        <option>Đăng nhập để dùng mã</option>
                                    </select>
                                <?php endif; ?>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const pricePerHour = <?= (float)($pitch['price_per_hour'] ?? 0) ?>;
    const slotSelect = document.querySelector('select[name="slot_time"]');
    const voucherSelect = document.querySelector('select[name="voucher_id"]');
    const totalPriceEl = document.getElementById('dynamic-total-price');
    const discountEl = document.getElementById('dynamic-discount');
    const durationLabelEl = document.getElementById('dynamic-duration-label');
    const basePriceEl = document.getElementById('dynamic-base-price');

    function calculateTotal() {
        let duration = 1.5; // mặc định nếu chưa chọn
        let durationText = "1.5h";
        let durationSuffix = " <span class='fw-normal text-secondary small'>(Giá 1 tiếng rưỡi)</span>";
        
        if (slotSelect && slotSelect.value !== '') {
            const selectedOption = slotSelect.options[slotSelect.selectedIndex];
            duration = parseFloat(selectedOption.getAttribute('data-duration'));
            if (duration === 1) {
                durationText = "1h";
                durationSuffix = " <span class='fw-normal text-secondary small'>(Giá 1 tiếng)</span>";
            } else if (duration === 2) {
                durationText = "2h";
                durationSuffix = " <span class='fw-normal text-secondary small'>(Giá 2 tiếng)</span>";
            }
        }

        const basePrice = pricePerHour * duration;
        let total = basePrice;
        let discount = 0;
        
        // Check voucher
        if (voucherSelect && voucherSelect.value !== '') {
            const selectedOption = voucherSelect.options[voucherSelect.selectedIndex];
            const discountAttr = selectedOption.getAttribute('data-discount');
            if (discountAttr) {
                discount = parseFloat(discountAttr);
                total -= discount;
            }
        }
        
        if (total < 0) total = 0;

        durationLabelEl.textContent = 'Tiền sân (' + durationText + ')';
        basePriceEl.innerHTML = new Intl.NumberFormat('vi-VN').format(basePrice) + 'đ' + durationSuffix;

        discountEl.textContent = '-' + new Intl.NumberFormat('vi-VN').format(discount) + 'đ';
        totalPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    if (slotSelect) slotSelect.addEventListener('change', calculateTotal);
    if (voucherSelect) voucherSelect.addEventListener('change', calculateTotal);
    
    // Initial calculation
    calculateTotal();
});
</script>