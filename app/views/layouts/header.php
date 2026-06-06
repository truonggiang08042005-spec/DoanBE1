<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sân Cỏ Mỗi Ngày</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --emerald: #0b4d3e; --gold: #f5c84c; }
        body { font-family: 'Inter', sans-serif; background: #f6f8fb; padding-top: 92px; }
        .text-emerald { color: var(--emerald); }
        .nav-link { font-weight: 700; color: #334155; }
        .nav-link:hover { color: var(--gold); }
        .brand-badge { background: linear-gradient(135deg, rgba(11,77,62,.12), rgba(245,200,76,.16)); }
        .btn-profile { border-color: #cbd5e1; color: #334155; }
        .btn-profile:hover { border-color: #94a3b8; color: #0f172a; }
    </style>
</head>
<body>

<?php
$isLoggedIn = !empty($_SESSION['user']);
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);
?>

<header class="bg-white border-bottom shadow-sm fixed-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg py-3">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-black text-decoration-none" href="<?= BASE_URL ?>index.php">
                <span class="brand-badge border rounded-4 px-2 py-1 d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-person-running fs-4 text-emerald"></i>
                </span>
                <span class="text-emerald">Sân Cỏ Mỗi Ngày</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#search">Tìm Sân</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#offers">Ưu Đãi</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#tournament">Giải Đấu</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#teams">Đội Nhóm</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php#support">Hỗ Trợ</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-profile rounded-pill d-inline-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="d-none d-sm-inline fw-bold"><?= $isLoggedIn ? htmlspecialchars($_SESSION['user']['fullname'] ?? 'Tài khoản', ENT_QUOTES, 'UTF-8') : 'Tài khoản' ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-2">
                            <?php if ($isLoggedIn): ?>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=booking&action=history"><i class="bi bi-clock-history me-2"></i>Lịch sử đặt sân</a></li>
                                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=admin&action=bookings"><i class="bi bi-speedometer2 me-2"></i>Admin</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-3 fw-bold text-danger" href="<?= BASE_URL ?>index.php?controller=auth&action=logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=auth&action=login"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</a></li>
                                <li><a class="dropdown-item rounded-3 fw-semibold" href="<?= BASE_URL ?>index.php?controller=auth&action=register"><i class="bi bi-person-plus me-2"></i>Đăng ký</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <a class="btn btn-primary fw-black rounded-pill px-3" href="<?= BASE_URL ?>index.php#quick-book">
                        Đặt Sân Ngay
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>

<main class="container py-4">
<?php if (!empty($flashSuccess)): ?>
    <div class="alert alert-success rounded-4" role="alert">
        <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
