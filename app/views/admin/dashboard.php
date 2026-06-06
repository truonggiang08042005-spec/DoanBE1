<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-black mb-1">Dashboard Thống Kê</h4>
        <div class="text-muted small">Tổng quan về hoạt động của hệ thống.</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=bookings">
            <i class="bi bi-calendar-check me-1"></i>Quản lý Đặt sân
        </a>
        <a class="btn btn-outline-success rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=pitches">
            <i class="bi bi-grid me-1"></i>Quản lý Sân
        </a>
        <a class="btn btn-outline-info rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=users">
            <i class="bi bi-people me-1"></i>Quản lý Khách hàng
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-currency-dollar fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="card-title text-muted mb-0 fw-semibold">Tổng doanh thu</h6>
                    </div>
                </div>
                <h3 class="fw-black mb-0"><?= number_format((float)($totalRevenue ?? 0)) ?>đ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="card-title text-muted mb-0 fw-semibold">Lượt đặt sân (Confirmed)</h6>
                    </div>
                </div>
                <h3 class="fw-black mb-0"><?= number_format((int)($totalBookings ?? 0)) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-grid fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="card-title text-muted mb-0 fw-semibold">Tổng số sân</h6>
                    </div>
                </div>
                <h3 class="fw-black mb-0"><?= number_format((int)($totalPitches ?? 0)) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="card-title text-muted mb-0 fw-semibold">Tổng khách hàng</h6>
                    </div>
                </div>
                <h3 class="fw-black mb-0"><?= number_format((int)($totalUsers ?? 0)) ?></h3>
            </div>
        </div>
    </div>
</div>
