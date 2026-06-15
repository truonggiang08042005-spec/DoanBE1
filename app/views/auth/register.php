<?php
$patternBg = "https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=luxury%20emerald%20green%20background%20with%20subtle%20abstract%20gold%20football%20patterns%2C%20premium%2C%20ornate%2C%20high%20detail%2C%20no%20text%2C%20flat%20background%20texture&image_size=landscape_16_9";
$woodFrame = "https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=photorealistic%20wood%20frame%20border%20texture%2C%20elegant%20dark%20walnut%2C%20subtle%20grain%2C%20no%20text%2C%20isolated%20texture&image_size=landscape_4_3";
?>

<style>
    .reg-stage {
        min-height: calc(100vh - 140px);
        display: flex;
        align-items: center;
    }
    .reg-frame {
        /* The frame is now just a container for the card's shadow */
        position: relative;
    }
    .reg-card {
        border-radius: 22px;
        position: relative;
        overflow: hidden;
        background-image: url('<?= BASE_URL ?>public/images/image.png');
        background-size: cover;
        background-position: center;
        box-shadow: 0 24px 80px rgba(2, 6, 23, .30);
    }
    .reg-card::before {
        /* This pseudo-element creates the dark overlay for text readability */
        content: "";
        position: absolute;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.6);
        pointer-events: none;
    }
    .reg-inner {
        position: relative;
        z-index: 2;
        padding: 28px;
    }
    @media (min-width: 768px) {
        .reg-inner { padding: 36px; }
    }
    .reg-title {
        color: #f5c84c;
        letter-spacing: .08em;
        font-weight: 900;
        text-transform: uppercase;
    }
    .reg-step {
        color: rgba(255, 255, 255, .80);
        font-weight: 800;
        letter-spacing: .04em;
        font-size: 12px;
    }
    .reg-progress {
        height: 6px;
        background: rgba(255, 255, 255, .14);
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid rgba(245, 200, 76, .35);
    }
    .reg-progress > div {
        height: 100%;
        width: 50%;
        background: linear-gradient(90deg, #f5c84c, #ffd66e);
        box-shadow: 0 0 18px rgba(245, 200, 76, .45);
    }
    .glass-input {
        border-radius: 18px;
        border: 1px solid rgba(245, 200, 76, .40);
        background: rgba(255, 255, 255, .10);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #f8fafc;
        padding-left: 46px;
        padding-right: 14px;
        height: 54px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .08);
    }
    .glass-input::placeholder { color: rgba(248, 250, 252, .60); }
    .glass-input:focus {
        background: rgba(255, 255, 255, .12);
        color: #fff;
        border-color: rgba(245, 200, 76, .70);
        box-shadow: 0 0 0 .25rem rgba(245, 200, 76, .16);
    }
    .glass-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, .65);
        font-size: 18px;
        pointer-events: none;
        filter: drop-shadow(0 2px 10px rgba(0,0,0,.25));
    }
    .btn-reg {
        height: 56px;
        border-radius: 18px;
        font-weight: 900;
        letter-spacing: .06em;
        text-transform: uppercase;
        background: linear-gradient(135deg, #0d6efd, #4aa3ff);
        border: 1px solid rgba(245, 200, 76, .65);
        box-shadow: 0 0 22px rgba(13, 110, 253, .25), 0 0 34px rgba(245, 200, 76, .18);
    }
    .btn-reg:hover {
        background: linear-gradient(135deg, #0b5ed7, #2f93ff);
        border-color: rgba(245, 200, 76, .85);
    }
    .reg-foot, .reg-foot a { color: rgba(255, 255, 255, .82); }
    .reg-foot a { color: #f5c84c; font-weight: 900; text-decoration: none; }
    .reg-foot a:hover { text-decoration: underline; }
    .reg-hint { color: rgba(255,255,255,.65); font-size: 12px; }
    .reg-label { color: rgba(255,255,255,.82); font-weight: 800; font-size: 12px; letter-spacing: .06em; text-transform: uppercase; }
</style>

<div class="reg-stage py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="reg-frame">
                    <div class="reg-card">
                        <div class="reg-inner">
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                <div>
                                    <div class="reg-step">Bước 1 trên 2</div>
                                    <div class="reg-progress mt-2"><div></div></div>
                                </div>
                                <span class="badge rounded-pill text-bg-warning fw-black px-3 py-2">Premium</span>
                            </div>

                            <h2 class="reg-title mb-2">ĐĂNG KÝ TÀI KHOẢN MỚI</h2>
                            <div class="reg-hint mb-4">Vui lòng điền thông tin để tạo tài khoản Sân Cỏ Mỗi Ngày.</div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger rounded-4 mb-4" role="alert">
                                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?= BASE_URL ?>index.php?controller=auth&action=register">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="reg-label mb-1">Tên đăng nhập</div>
                                        <div class="position-relative">
                                            <i class="bi bi-person-fill glass-icon"></i>
                                            <input
                                                name="username"
                                                type="text"
                                                class="form-control glass-input"
                                                value="<?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                placeholder="Nhập tên đăng nhập (vd: sancomoiday)"
                                                required
                                                autocomplete="username"
                                            >
                                        </div>
                                        <div class="reg-hint mt-1">Chỉ gồm chữ/số/_ và tối thiểu 4 ký tự.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="reg-label mb-1">Họ và tên</div>
                                        <div class="position-relative">
                                            <i class="bi bi-person-badge-fill glass-icon"></i>
                                            <input
                                                name="fullname"
                                                type="text"
                                                class="form-control glass-input"
                                                value="<?= htmlspecialchars($fullname ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                placeholder="Nhập họ và tên của bạn"
                                                required
                                                autocomplete="name"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="reg-label mb-1">Số điện thoại</div>
                                        <div class="position-relative">
                                            <i class="bi bi-telephone-fill glass-icon"></i>
                                            <input
                                                name="phone"
                                                type="tel"
                                                class="form-control glass-input"
                                                value="<?= htmlspecialchars($phone ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                placeholder="Nhập số điện thoại liên hệ"
                                                required
                                                autocomplete="tel"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="reg-label mb-1">Mật khẩu</div>
                                        <div class="position-relative">
                                            <i class="bi bi-lock-fill glass-icon"></i>
                                            <input
                                                name="password"
                                                type="password"
                                                class="form-control glass-input"
                                                placeholder="Tạo mật khẩu mới"
                                                required
                                                autocomplete="new-password"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="reg-label mb-1">Xác nhận mật khẩu</div>
                                        <div class="position-relative">
                                            <i class="bi bi-lock-fill glass-icon"></i>
                                            <input
                                                name="confirm_password"
                                                type="password"
                                                class="form-control glass-input"
                                                placeholder="Nhập lại mật khẩu"
                                                required
                                                autocomplete="new-password"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="agreeTerms" required>
                                            <label class="form-check-label reg-foot" for="agreeTerms">
                                                Tôi đồng ý với <a href="#">Điều khoản</a> và <a href="#">Chính sách</a> của Sân Cỏ Mỗi Ngày.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-reg w-100 mt-4">
                                    ĐĂNG KÝ NGAY
                                </button>
                            </form>

                            <div class="reg-foot text-center mt-4">
                                Đã có tài khoản? <a href="<?= BASE_URL ?>index.php?controller=auth&action=login">Đăng nhập ngay</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>