<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-black mb-1">Dashboard - Quản lý đặt sân</h4>
        <div class="text-muted small">Xác nhận hoặc hủy đơn đặt sân.</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-success rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=pitches">
            <i class="bi bi-grid me-1"></i>Quản lý sân
        </a>
        <a class="btn btn-outline-secondary rounded-3" href="<?= BASE_URL ?>index.php">
            <i class="bi bi-house me-1"></i>Trang chủ
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                            $badge = 'text-bg-secondary';
                            if ($status === 'CONFIRMED') $badge = 'text-bg-success';
                            if ($status === 'CANCELLED') $badge = 'text-bg-danger';
                            ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold">#<?= (int)$b['id'] ?></td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($b['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($b['pitch_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($b['customer_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($b['customer_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-muted small">
                                        Tài khoản: <?= htmlspecialchars($b['user_username'] ?? 'Guest', ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($b['booking_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars(($b['start_time'] ?? '') . ' - ' . ($b['end_time'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td class="px-4 py-3 text-end fw-bold text-success"><?= number_format((float)($b['total_price'] ?? 0)) ?>đ</td>
                                <td class="px-4 py-3"><span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=updateBookingStatus">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                            <input type="hidden" name="status" value="CONFIRMED">
                                            <button class="btn btn-success btn-sm fw-bold rounded-3" type="submit" <?= $status === 'CONFIRMED' ? 'disabled' : '' ?>>
                                                Xác nhận
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=updateBookingStatus">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                            <input type="hidden" name="status" value="CANCELLED">
                                            <button class="btn btn-outline-danger btn-sm fw-bold rounded-3" type="submit" <?= $status === 'CANCELLED' ? 'disabled' : '' ?>>
                                                Hủy đơn
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-4 text-center text-muted" colspan="7">Chưa có đơn đặt sân nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

