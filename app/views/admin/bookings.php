<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Quản lý Đặt sân</h4>
        <div class="text-muted small">Xác nhận hoặc hủy các đơn đặt sân từ khách hàng.</div>
    </div>
    <a href="<?= BASE_URL ?>index.php?controller=booking&action=exportBookings" class="btn btn-success rounded-pill fw-bold shadow-sm">
        <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel (CSV)
    </a>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="px-4 py-3">Mã</th>
                    <th class="px-4 py-3">Sân</th>
                    <th class="px-4 py-3">Khách</th>
                    <th class="px-4 py-3">Ngày / Giờ</th>
                    <th class="px-4 py-3 text-end">Tổng tiền</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                        <?php
                        $status = $b['status'] ?? 'PENDING';
                        $badge = 'bg-secondary';
                        if ($status === 'CONFIRMED') $badge = 'bg-success';
                        if ($status === 'CANCELLED') $badge = 'bg-danger';
                        ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-gold">#<?= (int)$b['id'] ?></td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-white"><?= htmlspecialchars($b['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($b['pitch_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-white"><?= htmlspecialchars($b['customer_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($b['customer_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small">
                                    Tài khoản: <span class="text-info"><?= htmlspecialchars($b['user_username'] ?? 'Guest', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-light"><?= htmlspecialchars($b['booking_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small"><?= htmlspecialchars(($b['start_time'] ?? '') . ' - ' . ($b['end_time'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </td>
                            <td class="px-4 py-3 text-end fw-bold text-success"><?= number_format((float)($b['total_price'] ?? 0)) ?>đ</td>
                            <td class="px-4 py-3">
                                <select name="status" form="form-booking-<?= (int)$b['id'] ?>" class="form-select form-select-sm bg-dark text-white border-secondary rounded-3 shadow-sm" style="min-width: 130px;">
                                    <option value="PENDING" <?= $status === 'PENDING' ? 'selected' : '' ?>>Chờ xử lý</option>
                                    <option value="CONFIRMED" <?= $status === 'CONFIRMED' ? 'selected' : '' ?>>Đã xác nhận</option>
                                    <option value="PAID" <?= $status === 'PAID' ? 'selected' : '' ?>>Đã thanh toán</option>
                                    <option value="CANCELLED" <?= $status === 'CANCELLED' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <form id="form-booking-<?= (int)$b['id'] ?>" method="POST" action="<?= BASE_URL ?>index.php?controller=booking&action=updateBookingStatus">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                    <button class="btn btn-gold btn-sm fw-bold rounded-3 px-3 shadow-sm" type="submit">
                                        Cập nhật
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="px-4 py-5 text-center text-muted" colspan="7">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Chưa có đơn đặt sân nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
