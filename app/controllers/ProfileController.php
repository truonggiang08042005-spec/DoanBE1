<?php

class ProfileController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=auth&action=login');
            exit();
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user']['id']);

        if (!$user) {
            // Xử lý trường hợp không tìm thấy người dùng
            $_SESSION['flash_error'] = 'Không tìm thấy thông tin người dùng.';
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        // Truyền dữ liệu người dùng sang view
        $data = ['user' => $user];
        $this->render('index', $data);
    }

    public function edit() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=auth&action=login');
            exit();
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user']['id']);

        if (!$user) {
            $_SESSION['flash_error'] = 'Không tìm thấy thông tin người dùng.';
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        $data = ['user' => $user];
        $this->render('edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($fullname)) {
            $_SESSION['flash_error'] = 'Họ và tên không được để trống.';
            header('Location: ' . BASE_URL . 'index.php?controller=profile&action=edit');
            exit();
        }

        $userModel = new User();
        if ($userModel->update($userId, $fullname, $phone)) {
            $_SESSION['flash_success'] = 'Cập nhật thông tin thành công!';
            // Cập nhật lại thông tin trong session
            $_SESSION['user']['fullname'] = $fullname;
        } else {
            $_SESSION['flash_error'] = 'Cập nhật thông tin thất bại. Vui lòng thử lại.';
        }

        header('Location: ' . BASE_URL . 'index.php?controller=profile&action=index');
        exit();
    }


    private function render($view, $data = []) {
        // Tạo biến $user một cách tường minh để view có thể truy cập
        $user = $data['user'] ?? null;
        require_once dirname(__DIR__) . '/views/profile/' . $view . '.php';
    }
}
?>