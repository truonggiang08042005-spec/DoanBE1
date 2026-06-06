<?php
$q = trim($_GET['q'] ?? '');
$type = trim($_GET['type'] ?? '');
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
    .hero-banner {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        background: linear-gradient(90deg, #052e25, #0b4d3e);
    }
    .hero-banner::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('<?= $goldLineArt ?>');
        background-size: cover;
        background-position: center;
        opacity: .18;
        pointer-events: none;
        mix-blend-mode: screen;
    }
    .hero-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(900px 500px at 15% 20%, rgba(245, 200, 76, .10), transparent 60%),
                    radial-gradient(700px 450px at 85% 10%, rgba(13, 110, 253, .12), transparent 55%);
        pointer-events: none;
    }
    .hero-inner { position: relative; z-index: 1; }
    .hero-search {
        background: #fff;
        border-radius: 999px;
        overflow: hidden;
        box-shadow: 0 18px 50px rgba(2, 6, 23, .25);
    }
    .hero-search .form-control { border: 0; padding-left: 18px; }
    .hero-search .input-group-text { border: 0; background: #fff; }
    .hero-search .btn { border-radius: 999px; margin: 6px; }
    .quick-card {
        border-radius: 18px;
        box-shadow: 0 24px 80px rgba(2, 6, 23, .25);
    }
    .outline-soft {
        border-color: rgba(255,255,255,.45);
        color: rgba(255,255,255,.92);
    }
    .outline-soft:hover {
        border-color: rgba(245,200,76,.75);
        color: #fff;
    }
</style>

<section class="hero-banner shadow-sm border mb-5">
    <div class="hero-inner p-4 p-md-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7 text-white">
                <div class="badge rounded-pill text-bg-primary fw-bold px-3 py-2 mb-3">Sân Cỏ Mỗi Ngày</div>
                <h1 class="fw-black display-6 mb-3">ĐẶT SÂN BÓNG DỄ DÀNG CHỈ TRONG PHÚT!</h1>
                <p class="opacity-90 mb-4">Tìm kiếm sân bóng, địa điểm hoặc thời gian phù hợp. Đặt lịch nhanh và rõ ràng.</p>

                <form id="search" class="mb-3" method="GET" action="<?= BASE_URL ?>index.php">
                    <input type="hidden" name="controller" value="home">
                    <input type="hidden" name="action" value="index">
                    <div class="input-group input-group-lg hero-search">
                        <span class="input-group-text"><i class="bi bi-search text-primary"></i></span>
                        <input class="form-control" type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Tìm kiếm sân bóng, địa điểm hoặc thời gian...">
                        <button class="btn btn-primary fw-black px-4" type="submit">Tìm kiếm</button>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-light fw-bold rounded-pill px-4 outline-soft" href="<?= $isLoggedIn ? (BASE_URL . 'index.php?controller=booking&action=history') : (BASE_URL . 'index.php?controller=auth&action=login') ?>">
                        <i class="bi bi-clock-history me-1"></i>Lịch sử đặt sân
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white quick-card p-4" id="quick-book">
                    <div class="fw-black fs-5 mb-3">Form Đặt Sân Nhanh</div>

                    <form class="row g-3" method="GET" action="<?= BASE_URL ?>index.php#pitch-list">
                        <input type="hidden" name="controller" value="home">
                        <input type="hidden" name="action" value="index">

                        <div class="col-12">
                            <label class="form-label fw-semibold">Địa điểm</label>
                            <select class="form-select form-select-lg rounded-3" name="location">
                                <?php foreach ($locations as $k => $label): ?>
                                    <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>" <?= $location === $k ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngày đặt</label>
                            <select class="form-select form-select-lg rounded-3" name="booking_date">
                                <?php foreach ($dates as $k => $label): ?>
                                    <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giờ đặt</label>
                            <select class="form-select form-select-lg rounded-3" name="slot">
                                <?php foreach ($slots as $k => $label): ?>
                                    <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>" <?= $slot === $k ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Loại sân</label>
                            <select class="form-select form-select-lg rounded-3" name="type">
                                <option value="">Tất cả</option>
                                <option value="Sân 5" <?= $type === 'Sân 5' ? 'selected' : '' ?>>Sân 5</option>
                                <option value="Sân 7" <?= $type === 'Sân 7' ? 'selected' : '' ?>>Sân 7</option>
                                <option value="Sân 11" <?= $type === 'Sân 11' ? 'selected' : '' ?>>Sân 11</option>
                            </select>
                        </div>

                        <div class="col-12 d-grid">
                            <button class="btn btn-primary btn-lg fw-black rounded-3" type="submit"><i class="bi bi-search me-1"></i>Tìm Sân</button>
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
            <h2 class="fw-black mb-1" style="color:#0b4d3e;">SÂN BÓNG GỢI Ý CHO BẠN</h2>
            <div class="text-muted">Top sân được nhiều người quan tâm.</div>
        </div>
        <a class="btn btn-outline-primary fw-bold rounded-pill" href="#pitch-list">Xem tất cả</a>
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
                        <img src="<?= $img ?>" class="w-100" style="height: 160px; object-fit: cover;" alt="Sân bóng">
                        <div class="card-body p-4">
                            <div class="fw-black"><?= htmlspecialchars(($pitch['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="text-muted small mb-2">Sân Thống Nhất - Tân Bình</div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="fw-bold text-warning">⭐<?= number_format($rating, 1) ?></div>
                                <span class="badge rounded-pill <?= $isActive ? 'text-bg-success' : 'text-bg-warning' ?>"><?= htmlspecialchars($isActive ? 'Còn sân trống hôm nay' : 'Đang bảo trì', ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <a class="btn btn-primary w-100 fw-black rounded-3 <?= $isActive ? '' : 'disabled' ?>" href="<?= BASE_URL ?>index.php?controller=pitch&action=detail&id=<?= (int)$pitch['id'] ?>">
                                Đặt sân
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning rounded-4 mb-0">Chưa có dữ liệu sân để gợi ý.</div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="mb-5" id="offers">
    <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
        <div>
            <h3 class="fw-black mb-1">ƯU ĐÃI NỔI BẬT</h3>
            <div class="text-muted">Ưu đãi theo khung giờ, theo tổng thời lượng, và voucher.</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div style="height: 160px; background-image: url('<?= $promoBg1 ?>'); background-size: cover; background-position: center;"></div>
                <div class="card-body p-4">
                    <div class="fw-black fs-5">GIẢM 20% KHUNG GIỜ VÀNG</div>
                    <div class="text-muted mb-3">13h - 16h mỗi ngày</div>
                    <span class="badge rounded-pill text-bg-primary">HOT</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div style="height: 160px; background-image: url('<?= $promoBg2 ?>'); background-size: cover; background-position: center;"></div>
                <div class="card-body p-4">
                    <div class="fw-black fs-5">TẶNG NƯỚC KHI ĐẶT TRÊN 2H</div>
                    <div class="text-muted mb-3">Áp dụng sân đối tác</div>
                    <span class="badge rounded-pill text-bg-success">FREE</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div style="height: 160px; background-image: url('<?= $promoBg3 ?>'); background-size: cover; background-position: center;"></div>
                <div class="card-body p-4">
                    <div class="fw-black fs-5">VOUCHER 50K CHO KHÁCH MỚI</div>
                    <div class="text-muted mb-3">Đăng ký tài khoản để nhận</div>
                    <a class="btn btn-outline-primary fw-bold rounded-pill <?= $isLoggedIn ? 'disabled' : '' ?>" href="<?= BASE_URL ?>index.php?controller=auth&action=register">Nhận ưu đãi</a>
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
                    <div class="fw-black fs-5 mb-1">Đội Nhóm</div>
                    <div class="text-muted mb-3">Tạo nhóm, rủ bạn bè và quản lý lịch đá.</div>
                    <a class="btn btn-outline-secondary fw-bold rounded-pill" href="#">Khám phá</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" id="support">
                <div class="card-body p-4">
                    <div class="fw-black fs-5 mb-1">Hỗ Trợ</div>
                    <div class="text-muted mb-3">Bạn cần hỗ trợ? Liên hệ hotline hoặc chat với chúng tôi.</div>
                    <a class="btn btn-outline-secondary fw-bold rounded-pill" href="#">Liên hệ</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="d-flex align-items-center justify-content-between mb-3" id="pitch-list">
    <div>
        <h3 class="fw-black mb-1">Danh sách sân</h3>
        <div class="text-muted">Kết quả theo bộ lọc của bạn.</div>
    </div>
</div>

<div class="row g-4">
    <?php if (!empty($pitches)): ?>
        <?php foreach ($pitches as $pitch): ?>
            <?php
            $status = $pitch['status'] ?? 'active';
            $isActive = $status === 'active';
            $badge = $isActive ? 'text-bg-success' : 'text-bg-warning';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <h5 class="fw-black mb-1"><?= htmlspecialchars($pitch['name'], ENT_QUOTES, 'UTF-8') ?></h5>
                                <span class="badge badge-sport rounded-pill"><?= htmlspecialchars($pitch['type'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small">Giá theo giờ</div>
                            <div class="fs-5 fw-black text-sport">
                                <?= number_format((float)$pitch['price_per_hour']) ?>đ
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 pt-0">
                        <?php if ($isActive): ?>
                            <a class="btn btn-primary w-100 fw-black rounded-3" href="<?= BASE_URL ?>index.php?controller=pitch&action=detail&id=<?= $pitch['id'] ?>">
                                Đặt sân
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary w-100 fw-black rounded-3" type="button" disabled>
                                Đang bảo trì
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning rounded-4 mb-0" role="alert">
                Không tìm thấy sân phù hợp. Vui lòng thử lại bộ lọc khác.
            </div>
        </div>
    <?php endif; ?>
</div>