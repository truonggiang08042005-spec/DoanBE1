<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Doanh thu / Đơn đã thanh toán</h4>
        <div class="text-muted small">Quản lý các đơn đặt sân đã hoàn tất thanh toán và tổng hợp doanh thu.</div>
    </div>
</div>

<div class="admin-card mb-4 p-4">
    <form method="GET" action="<?= BASE_URL ?>index.php" class="row g-3 align-items-end">
        <input type="hidden" name="controller" value="booking">
        <input type="hidden" name="action" value="paidBookings">
        
        <div class="col-md-4">
            <label for="search" class="form-label text-light fw-semibold">Tên khách hàng</label>
            <input type="text" class="form-control bg-dark text-white border-secondary" id="search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập tên KH hoặc Username...">
        </div>
        <div class="col-md-4">
            <label for="date" class="form-label text-light fw-semibold">Ngày đặt sân</label>
            <input type="date" class="form-control bg-dark text-white border-secondary" id="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-gold fw-bold px-4"><i class="bi bi-search me-2"></i>Tìm kiếm</button>
            <a href="<?= BASE_URL ?>index.php?controller=booking&action=paidBookings" class="btn btn-outline-secondary fw-bold px-4 ms-2"><i class="bi bi-arrow-counterclockwise me-2"></i>Xóa lọc</a>
        </div>
    </form>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="admin-card p-4 d-flex align-items-center gap-3 border-start border-4 border-success">
            <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-cash-stack fs-2"></i>
            </div>
            <div>
                <div class="text-muted fw-semibold small text-uppercase">Tổng doanh thu (Kết quả lọc)</div>
                <h3 class="fw-bold text-white mb-0"><?= number_format($totalRevenue, 0, ',', '.') ?>đ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card p-4 d-flex align-items-center gap-3 border-start border-4 border-info">
            <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-receipt fs-2"></i>
            </div>
            <div>
                <div class="text-muted fw-semibold small text-uppercase">Số lượng đơn (Kết quả lọc)</div>
                <h3 class="fw-bold text-white mb-0"><?= count($paidBookings) ?> đơn</h3>
            </div>
        </div>
    </div>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th scope="col" class="px-4 py-3">Mã đơn</th>
                    <th scope="col" class="px-4 py-3">Khách hàng</th>
                    <th scope="col" class="px-4 py-3">SĐT</th>
                    <th scope="col" class="px-4 py-3">Sân bóng</th>
                    <th scope="col" class="px-4 py-3">Thời gian (Ngày - Giờ)</th>
                    <th scope="col" class="px-4 py-3 text-end">Tổng tiền (VNĐ)</th>
                    <th scope="col" class="px-4 py-3 text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($paidBookings)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            Không tìm thấy đơn hàng đã thanh toán nào phù hợp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($paidBookings as $b): ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-gold">#<?= $b['id'] ?></td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-white"><?= htmlspecialchars($b['customer_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php if ($b['user_username']): ?>
                                    <div class="small text-muted">@<?= htmlspecialchars($b['user_username'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php else: ?>
                                    <div class="small text-secondary">Khách vãng lai</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-light"><?= htmlspecialchars($b['customer_phone'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-4 py-3">
                                <div class="fw-semibold text-white"><?= htmlspecialchars($b['pitch_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="small text-info"><?= htmlspecialchars($b['pitch_type'] ?? 'Không rõ', ENT_QUOTES, 'UTF-8') ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-light fw-medium"><i class="bi bi-calendar-event me-2 text-muted"></i><?= date('d/m/Y', strtotime($b['booking_date'])) ?></div>
                                <div class="small text-gold mt-1"><i class="bi bi-clock me-2 text-muted"></i><?= date('H:i', strtotime($b['start_time'])) ?> - <?= date('H:i', strtotime($b['end_time'])) ?></div>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <?php if ((float)$b['discount_amount'] > 0): ?>
                                    <div class="text-decoration-line-through text-muted small"><?= number_format($b['total_price'] + $b['discount_amount'], 0, ',', '.') ?>đ</div>
                                    <div class="fw-bold text-success fs-5"><?= number_format($b['total_price'], 0, ',', '.') ?>đ</div>
                                <?php else: ?>
                                    <div class="fw-bold text-success fs-5"><?= number_format($b['total_price'], 0, ',', '.') ?>đ</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>Đã thanh toán
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
