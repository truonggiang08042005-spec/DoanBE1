<?php
$pendingReviewsCount = 0;
if (file_exists(dirname(dirname(__DIR__)) . '/models/Review.php')) {
    require_once dirname(dirname(__DIR__)) . '/models/Review.php';
    $tempReviewModel = new Review();
    $pendingReviewsCount = $tempReviewModel->getPendingReviewsCount();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Admin - Sân Cỏ Mỗi Ngày</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { 
            --dark-bg: #0b0f17; 
            --card-bg: #141b26; 
            --gold: #d4af37; 
            --gold-hover: #f3e5ab;
            --sidebar-width: 260px;
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark-bg); 
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Override dark text to white/light */
        .text-muted {
            color: rgba(255, 255, 255, 0.75) !important;
        }
        .text-dark, .text-body {
            color: #ffffff !important;
        }

        /* Sidebar Styling */
        #admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--card-bg);
            border-right: 1px solid rgba(214, 175, 55, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(214, 175, 55, 0.15);
            text-decoration: none;
        }

        .brand-badge { 
            background: rgba(214, 175, 55, 0.1); 
            border: 1px solid rgba(214, 175, 55, 0.2) !important;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        .brand-badge i { color: var(--gold) !important; font-size: 1.2rem; }

        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            gap: 12px;
        }

        .sidebar-link i {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            color: var(--gold);
            background: rgba(214, 175, 55, 0.05);
            border-right: 3px solid var(--gold);
        }

        .sidebar-link.active {
            color: var(--gold);
            background: rgba(214, 175, 55, 0.1);
            border-right: 3px solid var(--gold);
            font-weight: 600;
        }

        .sidebar-heading {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 700;
            margin-top: 1rem;
        }

        /* Main Content Styling */
        #admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Topbar Header within Main Content */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Global Admin Card Styling */
        .admin-card {
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Tables in Admin */
        .table {
            color: #e2e8f0;
            margin-bottom: 0;
        }
        .table th {
            background-color: rgba(0,0,0,0.2) !important;
            color: #94a3b8 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            font-weight: 600;
            padding: 1rem;
        }
        .table td {
            background-color: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            vertical-align: middle;
            padding: 1rem;
        }
        .table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        /* Custom scrollbar for sidebar */
        #admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        #admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        #admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
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

        @media (max-width: 991.98px) {
            #admin-sidebar {
                transform: translateX(-100%);
            }
            #admin-main {
                margin-left: 0;
            }
            #admin-sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

<?php
$currentAction = $_GET['action'] ?? 'dashboard';
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
?>

<!-- Sidebar -->
<aside id="admin-sidebar">
    <a href="<?= BASE_URL ?>index.php" class="sidebar-brand">
        <span class="brand-badge">
            <i class="bi bi-trophy-fill"></i>
        </span>
        <span style="color: var(--gold); font-weight: 800; letter-spacing: 0.5px;">ADMIN PANEL</span>
    </a>

    <div class="px-4 py-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-light" style="width: 40px; height: 40px;">
                <i class="bi bi-person-fill fs-5"></i>
            </div>
            <div>
                <div class="fw-bold text-light"><?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></div>
                <div class="small text-success d-flex align-items-center gap-1">
                    <span class="bg-success rounded-circle d-inline-block" style="width: 8px; height: 8px;"></span>
                    Online
                </div>
            </div>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="sidebar-heading">Tổng Quan</li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=dashboard" class="sidebar-link <?= $currentAction === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="sidebar-heading">Quản Lý Giao Dịch</li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=bookings" class="sidebar-link <?= $currentAction === 'bookings' ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i>
                <span>Quản lý Đặt sân</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=reviews" class="sidebar-link <?= $currentAction === 'reviews' ? 'active' : '' ?>">
                <i class="bi bi-chat-heart"></i>
                <span>Quản lý Đánh giá</span>
                <?php if ($pendingReviewsCount > 0): ?>
                    <span class="badge bg-danger rounded-pill ms-auto"><?= $pendingReviewsCount ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=vouchers" class="sidebar-link <?= in_array($currentAction, ['vouchers', 'createVoucher', 'editVoucher']) ? 'active' : '' ?>">
                <i class="bi bi-ticket-perforated"></i>
                <span>Quản lý Khuyến mãi</span>
            </a>
        </li>
        <li class="sidebar-heading">Quản Lý Hệ Thống</li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=pitches" class="sidebar-link <?= in_array($currentAction, ['pitches', 'createPitch', 'editPitch']) ? 'active' : '' ?>">
                <i class="bi bi-grid"></i>
                <span>Quản lý Sân bóng</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=categories" class="sidebar-link <?= in_array($currentAction, ['categories', 'createCategory', 'editCategory']) ? 'active' : '' ?>">
                <i class="bi bi-tags"></i>
                <span>Quản lý Danh mục</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" class="sidebar-link <?= $currentAction === 'users' ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Quản lý Khách hàng</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=activityLogs" class="sidebar-link <?= $currentAction === 'activityLogs' ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <span>Lịch sử hoạt động</span>
            </a>
        </li>

        <li class="sidebar-heading">Tài Khoản</li>
        <li class="sidebar-item">
            <a href="<?= BASE_URL ?>index.php" class="sidebar-link">
                <i class="bi bi-house"></i>
                <span>Về Trang Chủ</span>
            </a>
        </li>
        <li class="sidebar-item mt-2">
            <a href="<?= BASE_URL ?>index.php?controller=auth&action=logout" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span>Đăng xuất</span>
            </a>
        </li>
    </ul>
</aside>

<!-- Main Content -->
<main id="admin-main">
    <!-- Header của Main Content -->
    <header class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0">Hệ Thống Quản Trị</h3>
            <div class="text-muted small">Chào mừng trở lại, quản lý hệ thống hiệu quả!</div>
        </div>
        <div>
            <!-- Mobile Toggle Button -->
            <button class="btn btn-outline-secondary d-lg-none" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>
    </header>

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
    
    <!-- Bắt đầu phần nội dung riêng của từng view -->
