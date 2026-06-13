<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="admin-card h-100 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-light mb-0 fw-semibold">Tổng doanh thu</h6>
                </div>
            </div>
            <h3 class="fw-black mb-0 text-white"><?= number_format((float)($totalRevenue ?? 0)) ?>đ</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card h-100 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-calendar-check fs-4"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-light mb-0 fw-semibold">Lượt đặt (Confirmed)</h6>
                </div>
            </div>
            <h3 class="fw-black mb-0 text-white"><?= number_format((int)($totalBookings ?? 0)) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card h-100 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-grid fs-4"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-light mb-0 fw-semibold">Tổng số sân</h6>
                </div>
            </div>
            <h3 class="fw-black mb-0 text-white"><?= number_format((int)($totalPitches ?? 0)) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card h-100 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-light mb-0 fw-semibold">Tổng khách hàng</h6>
                </div>
            </div>
            <h3 class="fw-black mb-0 text-white"><?= number_format((int)($totalUsers ?? 0)) ?></h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card p-4">
            <h5 class="fw-bold text-white mb-4">Doanh thu 7 ngày qua</h5>
            <div style="height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="admin-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-white mb-0">5 Đơn đặt sân gần nhất</h5>
                <a href="<?= BASE_URL ?>index.php?controller=admin&action=bookings" class="btn btn-sm btn-outline-secondary rounded-pill">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="table-light-dark">
                        <tr>
                            <th scope="col" class="ps-3 py-3">ID</th>
                            <th scope="col" class="py-3">Khách hàng</th>
                            <th scope="col" class="py-3">Sân & Thời gian</th>
                            <th scope="col" class="py-3">Tổng tiền</th>
                            <th scope="col" class="py-3">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (!empty($recentBookings)): ?>
                            <?php foreach ($recentBookings as $rb): ?>
                                <tr>
                                    <td class="ps-3 py-3 fw-bold text-gold">#<?= $rb['id'] ?></td>
                                    <td class="py-3">
                                        <div class="fw-bold text-white"><?= htmlspecialchars($rb['customer_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($rb['customer_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-white fw-semibold"><?= htmlspecialchars($rb['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="text-muted small">
                                            <?= date('d/m/Y', strtotime($rb['booking_date'])) ?> | 
                                            <span class="text-gold"><?= date('H:i', strtotime($rb['start_time'])) ?> - <?= date('H:i', strtotime($rb['end_time'])) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 fw-bold text-success"><?= number_format((float)$rb['total_price']) ?>đ</td>
                                    <td class="py-3">
                                        <?php if ($rb['status'] === 'CONFIRMED'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Đã xác nhận</span>
                                        <?php elseif ($rb['status'] === 'PAID'): ?>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">Đã thanh toán</span>
                                        <?php elseif ($rb['status'] === 'CANCELLED'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Đã hủy</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">Chờ xử lý</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                    Chưa có đơn đặt sân nào.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Dữ liệu từ PHP
    const labels = <?= json_encode($chartLabels ?? []) ?>;
    const dataValues = <?= json_encode($chartValues ?? []) ?>;

    // Cấu hình Chart
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dataValues,
                borderColor: '#d4af37', // Màu vàng Gold
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#d4af37',
                pointBorderColor: '#141b26',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Tạo đường cong mềm mại
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Ẩn legend vì chỉ có 1 dataset và đã có tiêu đề
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#94a3b8',
                        callback: function(value) {
                            if (value === 0) return '0đ';
                            return new Intl.NumberFormat('vi-VN', { notation: "compact" , compactDisplay: "short" }).format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                }
            }
        }
    });
});
</script>
