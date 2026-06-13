<?php
$q = trim($_GET['q'] ?? '');
$category_id = trim($_GET['category_id'] ?? '');
$isLoggedIn = !empty($_SESSION['user']);
$isAdmin = !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
$location = trim($_GET['location'] ?? '');

$goldLineArt = BASE_URL . 'public/images/gold-line-art.png';
$pitchImg1 = BASE_URL . 'public/images/S1.jpg';
$pitchImg2 = BASE_URL . 'public/images/S1.jpg';
$pitchImg3 = BASE_URL . 'public/images/S2.jpg';
$pitchImg4 = BASE_URL . 'public/images/S4.jpg';

$promoBg1 = BASE_URL . 'public/images/promo-bg-1.jpg';
$promoBg2 = BASE_URL . 'public/images/promo-bg-2.jpg';
$promoBg3 = BASE_URL . 'public/images/promo-bg-3.jpg';

$locations = [
    '' => 'Chọn địa điểm',
    'TP.HCM - Tân Bình' => 'TP.HCM - Tân Bình',
    'TP.HCM - Gò Vấp' => 'TP.HCM - Gò Vấp',
    'TP.HCM - Thủ Đức' => 'TP.HCM - Thủ Đức',
    'Hà Nội - Cầu Giấy' => 'Hà Nội - Cầu Giấy',
    'Hà Nội - Thanh Xuân' => 'Hà Nội - Thanh Xuân',
];

$dates = [];
for ($i = 0; $i < 7; $i++) {
    $d = date('Y-m-d', strtotime('+' . $i . ' day'));
    $dates[$d] = date('d/m/Y', strtotime($d));
}

$slots = [
    '' => 'Chọn khung giờ',
    '06:00-07:00' => '06:00 - 07:00',
    '07:00-08:00' => '07:00 - 08:00',
    '17:00-18:00' => '17:00 - 18:00',
    '18:00-19:00' => '18:00 - 19:00',
    '19:00-20:00' => '19:00 - 20:00',
    '20:00-21:00' => '20:00 - 21:00',
];

$slot = trim($_GET['slot'] ?? '');

$suggested = array_slice($pitches ?? [], 0, 4);
$suggestedImages = [$pitchImg1, $pitchImg2, $pitchImg3, $pitchImg4];
?>

<style>
    /* Tổng thể giao diện Dark Mode */
    body {
        background-color: #0b0f17 !important;
        color: #e2e8f0 !important;
    }

    /* Định hình lại màu sắc văn bản Bootstrap mặc định */
    .text-muted { color: #94a3b8 !important; }
    .text-dark { color: #f8fafc !important; }

    /* Nút bấm Custom màu Vàng Gold */
    .btn-gold {
        background: linear-gradient(135deg, #d4af37, #aa7c11);
        color: #000 !important;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gold:hover:not(:disabled) {
        background: linear-gradient(135deg, #f3e5ab, #d4af37);
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
        transform: translateY(-1px);
    }
    .btn-gold:disabled {
        background: #475569;
        color: #94a3b8;
    }

    .btn-outline-gold {
        border: 1px solid #d4af37;
        color: #d4af37 !important;
        background: transparent;
        transition: all 0.3s ease;
    }
    .btn-outline-gold:hover {
        background: #d4af37;
        color: #000 !important;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
    }

    /* Thiết kế lại Hero Banner cao cấp */
    .hero-banner {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(135deg, #0f141c, #1e2633);
        border: 1px solid rgba(212, 175, 55, 0.15) !important;
    }
    .hero-banner::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('<?= $goldLineArt ?>');
        background-size: cover;
        background-position: center;
        opacity: .12;
        pointer-events: none;
        mix-blend-mode: screen;
    }
    .hero-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(900px 500px at 15% 20%, rgba(212, 175, 55, .08), transparent 65%),
                    radial-gradient(700px 450px at 85% 10%, rgba(30, 41, 59, .5), transparent 60%);
        pointer-events: none;
    }
    .hero-inner { position: relative; z-index: 1; }

    /* Thanh tìm kiếm */
    .hero-search {
        background: #1e293b;
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    .hero-search .form-control { 
        background: #1e293b; 
        color: #fff; 
        border: 0; 
        padding-left: 18px; 
    }
    .hero-search .form-control::placeholder { color: #64748b; }
    .hero-search .form-control:focus { background: #1e293b; color: #fff; box-shadow: none; }
    .hero-search .input-group-text { border: 0; background: #1e293b; color: #d4af37; }
    .hero-search .btn { border-radius: 999px; margin: 6px; }

    /* Form Đặt Sân Nhanh */
    .quick-card {
        background: #141b26 !important;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 24px 80px rgba(0,0,0,0.6);
        color: #f8fafc;
    }
    .quick-card .form-select {
        background-color: #1e293b;
        border-color: rgba(255,255,255,0.1);
        color: #f8fafc;
    }
    .quick-card .form-select:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    }

    /* Phong cách các thẻ Card trong trang */
    .card {
        background-color: #141b26 !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5) !important;
        border-color: rgba(212, 175, 55, 0.2) !important;
    }
    .card-footer {
        background-color: transparent !important;
    }

    /* Biến đổi Badge */
    .badge-gold-light {
        background-color: rgba(212, 175, 55, 0.1);
        color: #d4af37;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }
    .text-gold {
        color: #d4af37 !important;
    }
</style>

<section class="hero-banner shadow-sm mb-5">
    <div class="hero-inner p-4 p-md-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7 text-white">
                <div class="badge rounded-pill badge-gold-light fw-bold px-3 py-2 mb-3">
                    <i class="bi bi-gem me-1"></i>Trải Nghiệm Đẳng Cấp Thể Thao
                </div>
                <h1 class="fw-black display-6 mb-3 text-gold">ĐẶT SÂN BÓNG THƯỢNG LƯU CHỈ TRONG VÀI PHÚT</h1>
                <p class="text-muted mb-4" style="font-size: 1.1rem;">Hệ thống kết nối các tổ hợp sân đấu cao cấp, ánh sáng đỉnh cao và dịch vụ chuẩn mực. Tìm kiếm không gian khơi nguồn đam mê của bạn.</p>

                <form id="search" class="mb-3" method="GET" action="<?= BASE_URL ?>index.php">
                    <input type="hidden" name="controller" value="home">
                    <input type="hidden" name="action" value="index">
                    <div class="input-group input-group-lg hero-search">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input class="form-control" type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Tìm sân đấu cao cấp, quận huyện hoặc khung giờ...">
                        <button class="btn btn-gold fw-black px-4" type="submit">Tìm kiếm</button>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-gold fw-bold rounded-pill px-4" href="<?= $isLoggedIn ? (BASE_URL . 'index.php?controller=booking&action=history') : (BASE_URL . 'index.php?controller=auth&action=login') ?>">
                        <i class="bi bi-clock-history me-1"></i>Lịch sử đặt sân
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="quick-card p-4" id="quick-book">
                    <div class="fw-black fs-5 mb-3 text-gold"><i class="bi bi-lightning-charge-fill me-1"></i>Đặt Lịch Nhanh</div>

                    <form class="row g-3" method="GET" action="<?= BASE_URL ?>index.php#pitch-list">
                        <input type="hidden" name="controller" value="home">
                        <input type="hidden" name="action" value="index">

                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted">Địa điểm khu vực</label>
                            <select class="form-select form-select-lg rounded-3" name="location">
                                <?php foreach ($locations as $k => $label): ?>
                                    <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>" <?= $location === $k ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ngày thi đấu</label>
                            <select class="form-select form-select-lg rounded-3" name="booking_date">
                                <?php foreach ($dates as $k => $label): ?>
                                    <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Khung giờ</label>
                            <select class="form-select form-select-lg rounded-3" name="slot">
                                <?php foreach ($slots as $k => $label): ?>
                                    <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>" <?= $slot === $k ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted">Quy mô sân</label>
                            <select class="form-select form-select-lg rounded-3" name="category_id">
                                <option value="">Tất cả quy mô</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ((int)$category_id === (int)$cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 d-grid">
                            <button class="btn btn-gold btn-lg fw-black rounded-3" type="submit"><i class="bi bi-search me-1"></i>Tìm Sân Trống</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
        <div>
            <h2 class="fw-black mb-1 text-gold">SÂN BÓNG GỢI Ý CHO BẠN</h2>
            <div class="text-muted">Bộ sưu tập những sân đấu được cộng đồng đánh giá cao nhất.</div>
        </div>
        <a class="btn btn-outline-gold fw-bold rounded-pill" href="#pitch-list">Xem tất cả sân</a>
    </div>

    <div class="row g-4">
        <?php if (!empty($suggested)): ?>
            <?php foreach ($suggested as $i => $pitch): ?>
                <?php
                $img = $suggestedImages[$i] ?? $pitchImg1;
                $rating = 4.6 + (((int)($pitch['id'] ?? 1) % 4) * 0.1);
                $status = $pitch['status'] ?? 'active';
                $isActive = $status === 'active';
                ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <img src="<?= $img ?>" class="w-100" style="height: 160px; object-fit: cover; filter: brightness(0.9);" alt="Sân bóng">
                        <div class="card-body p-4">
                            <div class="fw-black fs-5 mb-1 text-white"><?= htmlspecialchars(($pitch['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i>Sân Thống Nhất - Tân Bình</div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="fw-bold text-gold">⭐<?= number_format($rating, 1) ?></div>
                                <span class="badge rounded-pill <?= $isActive ? 'text-bg-success' : 'text-bg-warning' ?>"><?= htmlspecialchars($isActive ? 'Sẵn sàng' : 'Đang bảo trì', ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <a class="btn btn-gold w-100 fw-black rounded-3 <?= $isActive ? '' : 'disabled' ?>" href="<?= BASE_URL ?>index.php?controller=pitch&action=detail&id=<?= (int)$pitch['id'] ?>">
                                Đặt sân ngay
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning bg-transparent border-warning text-warning rounded-4 mb-0">Chưa có dữ liệu sân để gợi ý.</div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="mb-5" id="offers">
    <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
        <div>
            <h3 class="fw-black mb-1 text-gold">ĐẶC QUYỀN & ƯU ĐÃI</h3>
            <div class="text-muted">Nâng tầm trải nghiệm với các chương trình thành viên ưu tú.</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div style="height: 160px; background-image: url('<?= $promoBg1 ?>'); background-size: cover; background-position: center; filter: brightness(0.7);"></div>
                <div class="card-body p-4">
                    <div class="fw-black fs-5 text-white">GIẢM 20% KHUNG GIỜ VÀNG</div>
                    <div class="text-muted mb-3">Áp dụng từ 13h - 16h mỗi ngày</div>
                    <span class="badge rounded-pill badge-gold-light">ĐẶC BIỆT</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div style="height: 160px; background-image: url('<?= $promoBg2 ?>'); background-size: cover; background-position: center; filter: brightness(0.7);"></div>
                <div class="card-body p-4">
                    <div class="fw-black fs-5 text-white">TẶNG NƯỚC KHI ĐẶT TRÊN 2H</div>
                    <div class="text-muted mb-3">Tiếp năng lượng cho các chiến binh</div>
                    <span class="badge rounded-pill text-bg-success">ƯU ĐÃI</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div style="height: 160px; background-image: url('<?= $promoBg3 ?>'); background-size: cover; background-position: center; filter: brightness(0.7);"></div>
                <div class="card-body p-4">
                    <div class="fw-black fs-5 text-white">VOUCHER 50K CHO KHÁCH MỚI</div>
                    <div class="text-muted mb-3">Chào mừng bạn gia nhập câu lạc bộ</div>
                    <a class="btn btn-outline-gold fw-bold rounded-pill <?= $isLoggedIn ? 'disabled' : '' ?>" href="<?= BASE_URL ?>index.php?controller=auth&action=register">Khởi động ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4" id="teams">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="fw-black fs-5 mb-1 text-white">Cộng Đồng Đội Nhóm</div>
                    <div class="text-muted mb-3">Gắn kết đồng đội, kiến tạo những trận cầu đỉnh cao một cách dễ dàng.</div>
                    <a class="btn btn-outline-secondary fw-bold rounded-pill text-white border-secondary" href="#">Khám phá ngay</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" id="support">
                <div class="card-body p-4">
                    <div class="fw-black fs-5 mb-1 text-white">Hỗ Trợ Thượng Khách</div>
                    <div class="text-muted mb-3">Đội ngũ CSKH chuyên nghiệp luôn sẵn sàng hỗ trợ bạn 24/7.</div>
                    <a class="btn btn-outline-secondary fw-bold rounded-pill text-white border-secondary" href="#">Liên hệ Hotline</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="d-flex align-items-center justify-content-between mb-3" id="pitch-list">
    <div>
        <h3 class="fw-black mb-1 text-gold">DANH SÁCH SÂN ĐẤU BÓNG</h3>
        <div class="text-muted">Kết quả tối ưu dựa trên lựa chọn lọc của bạn.</div>
    </div>
</div>

<div class="row g-4">
    <?php if (!empty($pitches)): ?>
        <?php foreach ($pitches as $pitch): ?>
            <?php
            $status = $pitch['status'] ?? 'active';
            $isActive = $status === 'active';
            $badge = $isActive ? 'text-bg-success' : 'text-bg-secondary';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <h5 class="fw-black mb-1 text-white"><?= htmlspecialchars($pitch['name'], ENT_QUOTES, 'UTF-8') ?></h5>
                                <span class="badge badge-gold-light rounded-pill mt-1"><?= htmlspecialchars($pitch['type'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status === 'active' ? 'Hoạt động' : 'Bảo trì', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="mt-4">
                            <div class="text-muted small">Chi phí trải nghiệm / giờ</div>
                            <div class="fs-4 fw-black text-gold">
                                <?= number_format((float)$pitch['price_per_hour']) ?>đ
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-4 pt-0">
                        <?php if ($isActive): ?>
                            <a class="btn btn-gold w-100 fw-black rounded-3" href="<?= BASE_URL ?>index.php?controller=pitch&action=detail&id=<?= $pitch['id'] ?>">
                                Đặt sân ngay
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 fw-black rounded-3" type="button" disabled>
                                Tạm đóng bảo trì
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning bg-transparent border-warning text-warning rounded-4 mb-0" role="alert">
                Không tìm thấy sân phù hợp với tiêu chí thượng lưu của bạn. Vui lòng thử lại bộ lọc khác.
            </div>
        </div>
    <?php endif; ?>
</div>