<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-black mb-1">Lịch sử đặt sân</h4>
        <div class="text-muted small">Chỉ hiển thị các đơn đặt thuộc tài khoản hiện tại.</div>
    </div>
    <a class="btn btn-outline-secondary rounded-3" href="<?= BASE_URL ?>index.php">
        <i class="bi bi-house me-1"></i>Trang chủ
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Sân</th>
                        <th class="px-4 py-3">Ngày đá</th>
                        <th class="px-4 py-3">Khung giờ</th>
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
                                <td class="px-4 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($b['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($b['pitch_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td class="px-4 py-3"><?= htmlspecialchars($b['booking_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars(($b['start_time'] ?? '') . ' - ' . ($b['end_time'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-end fw-bold text-success"><?= number_format((float)($b['total_price'] ?? 0)) ?>đ</td>
                                <td class="px-4 py-3">
                                    <span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <?php 
                                        $startTs = strtotime($b['booking_date'] . ' ' . $b['start_time']);
                                    ?>
                                    <?php if ($status === 'PENDING' && $startTs > time() + 3600): ?>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=booking&action=cancel" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt sân này không?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hủy</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-4 text-center text-muted" colspan="6">Bạn chưa có đơn đặt sân nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

