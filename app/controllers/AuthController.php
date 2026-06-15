<?php

require_once dirname(__DIR__) . '/models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    private function sanitizeReturnUrl($url) {
        $url = trim((string)$url);
        if ($url === '') {
            return BASE_URL . "index.php";
        }

        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            if (strpos($url, BASE_URL) === 0) {
                return $url;
            }
            return BASE_URL . "index.php";
        }

        if (strpos($url, 'index.php') === 0) {
            return BASE_URL . $url;
        }

        return BASE_URL . "index.php";
    }

    public function login() {
        $return_url = $this->sanitizeReturnUrl($_POST['return_url'] ?? $_GET['return_url'] ?? '');

        if (!empty($_SESSION['user'])) {
            header("Location: " . $return_url);
            exit();
        }

        $error = '';
        if (isset($_GET['error']) && $_GET['error'] === 'locked') {
            $error = 'Tài khoản của bạn đang bị khóa, vui lòng liên hệ nhân viên CSKH';
        }
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                $error = 'Vui lòng nhập tài khoản và mật khẩu.';
            } else {
                $user = $this->userModel->findByUsername($username);

                if (!$user || !password_verify($password, $user['password_hash'])) {
                    $error = 'Tài khoản hoặc mật khẩu không đúng.';
                } elseif ($user['status'] === 'locked') {
                    $error = 'Tài khoản của bạn đang bị khóa, vui lòng liên hệ nhân viên CSKH';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'fullname' => $user['fullname'],
                        'phone' => $user['phone'],
                        'role' => $user['role'],
                    ];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['flash_success'] = 'Đăng nhập thành công.';

                    header("Location: " . $return_url);
                    exit();
                }
            }
        }

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/auth/login.php';
    }

    public function register() {
        $return_url = $this->sanitizeReturnUrl($_POST['return_url'] ?? $_GET['return_url'] ?? '');

        if (!empty($_SESSION['user'])) {
            header("Location: " . $return_url);
            exit();
        }

        $error = '';
        $username = '';
        $fullname = '';
        $phone = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($username === '' || $fullname === '' || $phone === '' || $password === '' || $confirm_password === '') {
                $error = 'Vui lòng điền đầy đủ thông tin.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]{4,50}$/', $username)) {
                $error = 'Username chỉ gồm chữ/số/_ và tối thiểu 4 ký tự.';
            } elseif (mb_strlen($password) < 6) {
                $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
            } elseif ($password !== $confirm_password) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } else {
                $existing = $this->userModel->findByUsername($username);
                if ($existing) {
                    $error = 'Username đã được sử dụng.';
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $created = $this->userModel->createCustomer($username, $password_hash, $fullname, $phone);

                    if (!$created) {
                        $error = 'Đã có lỗi xảy ra khi tạo tài khoản.';
                    } else {
                        $_SESSION['flash_success'] = 'Đăng ký thành công. Vui lòng đăng nhập để tiếp tục.';
                        header("Location: " . BASE_URL . "index.php?controller=auth&action=login");
                        exit();
                    }
                }
            }
        }

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/auth/register.php';
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_success'] = 'Đã đăng xuất.';

        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}