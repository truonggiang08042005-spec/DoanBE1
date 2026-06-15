<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sân Cỏ Mỗi Ngày - Luxury Sports</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { 
            --dark-bg: #0b0f17; 
            --card-bg: #141b26; 
            --gold: #d4af37; 
            --gold-hover: #f3e5ab;
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark-bg); 
            color: #ffffff;
            padding-top: 92px; 
        }

        /* Override dark text to white/light */
        .text-muted {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        /* Tối ưu hóa Header */
        header.fixed-top {
            background-color: rgba(20, 27, 38, 0.95) !important;
            border-bottom: 1px solid rgba(214, 175, 55, 0.15) !important;
            backdrop-filter: blur(10px);
        }

        /* Định hình thương hiệu */
        .text-gold { color: var(--gold) !important; }
        .brand-badge { 
            background: rgba(214, 175, 55, 0.1); 
            border: 1px solid rgba(214, 175, 55, 0.2) !important;
        }
        .brand-badge i { color: var(--gold) !important; }

        /* Menu điều hướng */
        .nav-link { 
            font-weight: 700; 
            color: #94a3b8 !important; 
            transition: color 0.3s ease;
        }
        .nav-link:hover, .nav-link:focus { 
            color: var(--gold) !important; 
        }

        /* Nút Tài khoản & Dropdown */
        .btn-profile { 
            border-color: rgba(255, 255, 255, 0.1) !important; 
            color: #e2e8f0 !important; 
            background: rgba(255, 255, 255, 0.02);
        }
        .btn-profile:hover { 
            border-color: var(--gold) !important; 
            color: var(--gold) !important; 
        }
        
        .dropdown-menu {
            background-color: var(--card-bg) !important;
            border: 1px solid rgba(214, 175, 55, 0.15) !important;
        }
        .dropdown-item {
            color: #cbd5e1 !important;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: rgba(214, 175, 55, 0.1) !important;
            color: var(--gold) !important;
        }
        .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.08);
        }

        /* Nút bấm Gold Premium */
        .btn-gold {
            background: linear-gradient(135deg, #d4af37, #aa7c11);
            color: #000 !important;
            border: none;
            font-weight: 800;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #f3e5ab, #d4af37);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
            transform: translateY(-1px);
        }

        /* Tinh chỉnh Alert thông báo */
        .alert-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: rgba(25, 135, 84, 0.2);
            color: #a3cfbb;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.2);
            color: #ea868f;
        }
    </style>
</head>
<body>

<?php
$isLoggedIn = !empty($_SESSION['user']);
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
?>

<header class="fixed-top shadow-sm">
    <div class="container">
        <nav class="navbar navbar-expand-lg py-3">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-black text-decoration-none" href="<?= BASE_URL ?>index.php">
                <span class="brand-badge border rounded-4 px-2 py-1 d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-trophy-fill fs-4"></i>
                </span>
                <span class="text-gold fw-bold tracking-wide">SÂN CỎ MỖI NGÀY</span>
            </a>

            <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#search">Tìm Sân</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold text-gold" href="<?= BASE_URL ?>index.php?controller=home&action=vouchers"><i class="bi bi-gift me-1"></i>Kho Voucher</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#teams">Đội Nhóm</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#support">Hỗ Trợ</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-profile rounded-pill d-inline-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-gold"></i>
                            <span class="d-none d-sm-inline fw-bold"><?= $isLoggedIn ? htmlspecialchars($_SESSION['user']['fullname'] ?? 'Tài khoản', ENT_QUOTES, 'UTF-8') : 'Tài khoản' ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                            <?php if ($isLoggedIn): ?>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=profile&action=index"><i class="bi bi-person-circle me-2"></i>Hồ sơ của tôi</a></li>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=home&action=vouchers"><i class="bi bi-wallet2 me-2 text-success"></i>Ví Voucher</a></li>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=booking&action=history"><i class="bi bi-clock-history me-2"></i>Lịch sử đặt sân</a></li>
                                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=admin&action=dashboard"><i class="bi bi-speedometer2 me-2 text-gold"></i>Hệ thống Admin</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-3 fw-bold text-danger" href="<?= BASE_URL ?>index.php?controller=auth&action=logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=auth&action=login"><i class="bi bi-box-arrow-in-right me-2 text-gold"></i>Đăng nhập</a></li>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=auth&action=register"><i class="bi bi-person-plus me-2 text-gold"></i>Đăng ký thành viên</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <a class="btn btn-gold rounded-pill px-4" href="<?= BASE_URL ?>index.php#quick-book">
                        Đặt Sân Ngay
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>

<main class="container py-4">
<?php if (!empty($flashSuccess)): ?>
    <div class="alert alert-success rounded-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
<?php if (!empty($flashError)): ?>
    <div class="alert alert-danger rounded-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>