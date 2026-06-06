<?php

require_once dirname(__DIR__) . '/models/Booking.php';
require_once dirname(__DIR__) . '/models/Pitch.php';

class AdminController {
    private $bookingModel;
    private $pitchModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->pitchModel = new Pitch();
    }

    private function requireAdmin() {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden";
            exit();
        }
    }

    public function bookings() {
        $this->requireAdmin();

        $bookings = $this->bookingModel->getAllBookings();

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/bookings.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function updateBookingStatus() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?controller=admin&action=bookings");
            exit();
        }

        $booking_id = (int)($_POST['booking_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($booking_id > 0 && in_array($status, ['CONFIRMED', 'CANCELLED'], true)) {
            $this->bookingModel->updateStatus($booking_id, $status);
        }

        header("Location: " . BASE_URL . "index.php?controller=admin&action=bookings");
        exit();
    }

    public function pitches() {
        $this->requireAdmin();

        $pitches = $this->pitchModel->getAllPitches();

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/pitches.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function createPitch() {
        $this->requireAdmin();

        $error = '';
        $pitch = [
            'name' => '',
            'type' => 'Sân 5',
            'price_per_hour' => '',
            'status' => 'active',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pitch['name'] = trim($_POST['name'] ?? '');
            $pitch['type'] = trim($_POST['type'] ?? '');
            $pitch['price_per_hour'] = trim($_POST['price_per_hour'] ?? '');
            $pitch['status'] = trim($_POST['status'] ?? '');

            if ($pitch['name'] === '' || $pitch['price_per_hour'] === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } elseif (!is_numeric($pitch['price_per_hour']) || (float)$pitch['price_per_hour'] <= 0) {
                $error = 'Giá theo giờ không hợp lệ.';
            } else {
                $created = $this->pitchModel->createPitch($pitch['name'], $pitch['type'], (float)$pitch['price_per_hour'], $pitch['status']);
                if ($created) {
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=pitches");
                    exit();
                }
                $error = 'Không thể tạo sân.';
            }
        }

        $mode = 'create';
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/pitch_form.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function editPitch() {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $pitch = $this->pitchModel->getPitchById($id);
        if (!$pitch) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $price_per_hour = trim($_POST['price_per_hour'] ?? '');
            $status = trim($_POST['status'] ?? '');

            if ($name === '' || $price_per_hour === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } elseif (!is_numeric($price_per_hour) || (float)$price_per_hour <= 0) {
                $error = 'Giá theo giờ không hợp lệ.';
            } else {
                $updated = $this->pitchModel->updatePitch($id, $name, $type, (float)$price_per_hour, $status);
                if ($updated) {
                    header("Location: " . BASE_URL . "index.php?controller=admin&action=pitches");
                    exit();
                }
                $error = 'Không thể cập nhật sân.';
            }

            $pitch['name'] = $name;
            $pitch['type'] = $type;
            $pitch['price_per_hour'] = $price_per_hour;
            $pitch['status'] = $status;
        }

        $mode = 'edit';
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/pitch_form.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function deletePitch() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->pitchModel->deletePitch($id);
            }
        }

        header("Location: " . BASE_URL . "index.php?controller=admin&action=pitches");
        exit();
    }
}

