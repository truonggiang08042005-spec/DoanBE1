<?php

require_once dirname(__DIR__) . '/models/Booking.php';
require_once dirname(__DIR__) . '/models/Pitch.php';

class BookingController {
    private $bookingModel;
    private $pitchModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->pitchModel = new Pitch();
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pitch_id       = $_POST['pitch_id'] ?? null;
            $customer_name  = trim($_POST['customer_name'] ?? '');
            $customer_phone = trim($_POST['customer_phone'] ?? '');
            $booking_date   = $_POST['booking_date'] ?? null;
            $start_time     = $_POST['start_time'] ?? null;
            $end_time       = $_POST['end_time'] ?? null;

            $redirectDetail = BASE_URL . "index.php?controller=pitch&action=detail&id=" . urlencode((string)$pitch_id);
            $old = [
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'booking_date' => $booking_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
            ];

            if (!$pitch_id || $customer_name === '' || $customer_phone === '' || !$booking_date || !$start_time || !$end_time) {
                $_SESSION['flash_error'] = "Vui lòng điền đầy đủ thông tin.";
                $_SESSION['old'] = $old;
                header("Location: " . $redirectDetail);
                exit();
            }

            $pitch = $this->pitchModel->getPitchById($pitch_id);
            if (!$pitch) {
                $_SESSION['flash_error'] = "Sân không tồn tại.";
                $_SESSION['old'] = $old;
                header("Location: " . BASE_URL . "index.php");
                exit();
            }

            if (($pitch['status'] ?? 'active') !== 'active') {
                $_SESSION['flash_error'] = "Sân đang bảo trì, vui lòng chọn sân khác.";
                $_SESSION['old'] = $old;
                header("Location: " . $redirectDetail);
                exit();
            }

            $startTs = strtotime($booking_date . ' ' . $start_time);
            $endTs = strtotime($booking_date . ' ' . $end_time);
            if (!$startTs || !$endTs || $endTs <= $startTs) {
                $_SESSION['flash_error'] = "Khung giờ không hợp lệ.";
                $_SESSION['old'] = $old;
                header("Location: " . $redirectDetail);
                exit();
            }

            $hours = ($endTs - $startTs) / 3600;
            if ($hours < 1) {
                $_SESSION['flash_error'] = "Khung giờ đặt tối thiểu là 1 tiếng.";
                $_SESSION['old'] = $old;
                header("Location: " . $redirectDetail);
                exit();
            }

            $isBooked = $this->bookingModel->isPitchBooked($pitch_id, $booking_date, $start_time, $end_time);

            if ($isBooked) {
                $_SESSION['flash_error'] = "Xin lỗi, khung giờ này đã có đội khác đặt trước!";
                $_SESSION['old'] = $old;
                header("Location: " . $redirectDetail);
                exit();
            }

            $user_id = !empty($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
            $total_price = $hours * (float)$pitch['price_per_hour'];

            $result = $this->bookingModel->createBooking($user_id, $pitch_id, $customer_name, $customer_phone, $booking_date, $start_time, $end_time, $total_price);

            if ($result) {
                header("Location: " . BASE_URL . "index.php?controller=booking&action=success");
                exit();
            } else {
                $_SESSION['flash_error'] = "Đã có lỗi xảy ra trong quá trình đặt sân.";
                $_SESSION['old'] = $old;
                header("Location: " . $redirectDetail);
                exit();
            }
        }
    }

    public function success() {
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/booking/success.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function history() {
        if (empty($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=login");
            exit();
        }

        $bookings = $this->bookingModel->getBookingsByUserId((int)$_SESSION['user']['id']);

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/booking/history.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }
}
