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
            $slot_time      = $_POST['slot_time'] ?? null;
            $voucher_id     = (int)($_POST['voucher_id'] ?? 0);
            $start_time = null;
            $end_time = null;
            if ($slot_time) {
                $parts = explode('-', $slot_time);
                if (count($parts) === 2) {
                    $start_time = trim($parts[0]);
                    $end_time = trim($parts[1]);
                }
            }

            $redirectDetail = BASE_URL . "index.php?controller=pitch&action=detail&id=" . urlencode((string)$pitch_id);
            $old = [
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'booking_date' => $booking_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'voucher_id' => $voucher_id
            ];

            if (!$pitch_id || $customer_name === '' || $customer_phone === '' || !$booking_date || !$start_time || !$end_time) {
                $_SESSION['flash_error'] = "Vui lòng điền đầy đủ thông tin (đặc biệt là Khung giờ).";
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

            if ($startTs < time()) {
                $_SESSION['flash_error'] = "Không thể đặt sân ở thời điểm trong quá khứ.";
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

            // Voucher Logic
            $discount_amount = 0;
            $user_id = !empty($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;

            if ($voucher_id > 0) {
                if (!$user_id) {
                    $_SESSION['flash_error'] = "Vui lòng đăng nhập để sử dụng mã giảm giá.";
                    $_SESSION['old'] = $old;
                    header("Location: " . $redirectDetail);
                    exit();
                }

                require_once dirname(__DIR__) . '/models/Voucher.php';
                $voucherModel = new Voucher();
                $claimedVouchers = $voucherModel->getUserClaimedVouchers($user_id);
                $validVoucher = null;
                foreach ($claimedVouchers as $v) {
                    if ((int)$v['id'] === $voucher_id) {
                        $validVoucher = $v;
                        break;
                    }
                }

                if (!$validVoucher) {
                    $_SESSION['flash_error'] = "Mã giảm giá không hợp lệ hoặc bạn chưa lưu mã này.";
                    $_SESSION['old'] = $old;
                    header("Location: " . $redirectDetail);
                    exit();
                }
                $discount_amount = (float)$validVoucher['discount_amount'];
            }
            $base_price = $hours * (float)$pitch['price_per_hour'];
            $total_price = max(0, $base_price - $discount_amount);

            $result = $this->bookingModel->createBooking($user_id, $pitch_id, $customer_name, $customer_phone, $booking_date, $start_time, $end_time, $total_price, $voucher_id, $discount_amount);

            if ($result) {
                if ($voucher_id > 0 && isset($voucherModel)) {
                    $voucherModel->incrementUsedCount($voucher_id);
                    $voucherModel->markVoucherAsUsed($user_id, $voucher_id);
                }
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

        $user_id = (int)$_SESSION['user']['id'];
        $bookings = $this->bookingModel->getBookingsByUserId($user_id);
        
        require_once dirname(__DIR__) . '/models/Review.php';
        $reviewModel = new Review();
        $userReviews = $reviewModel->getReviewsByUserId($user_id);
        
        $reviewsByBooking = [];
        foreach ($userReviews as $r) {
            $reviewsByBooking[$r['booking_id']] = $r;
        }

        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/booking/history.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function cancel() {
        if (empty($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = (int)($_POST['booking_id'] ?? 0);
            if ($booking_id > 0) {
                $booking = $this->bookingModel->getBookingByIdAndUserId($booking_id, $_SESSION['user']['id']);
                
                if ($booking && $booking['status'] === 'PENDING') {
                    $startTs = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
                    if ($startTs > time() + 3600) { // Can cancel if more than 1 hour before start
                        $this->bookingModel->updateStatus($booking_id, 'CANCELLED');
                        $_SESSION['flash_success'] = "Hủy đặt sân thành công.";
                    } else {
                        $_SESSION['flash_error'] = "Chỉ có thể hủy sân trước giờ đá ít nhất 1 tiếng.";
                    }
                } else {
                    $_SESSION['flash_error'] = "Không thể hủy đơn đặt sân này.";
                }
            }
        }
        
        header("Location: " . BASE_URL . "index.php?controller=booking&action=history");
        exit();
    }

    public function submitReview() {
        if (empty($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = (int)($_POST['booking_id'] ?? 0);
            $comment = trim($_POST['comment'] ?? '');
            
            if ($booking_id > 0 && $comment !== '') {
                require_once dirname(__DIR__) . '/models/Review.php';
                $reviewModel = new Review();
                
                $booking = $this->bookingModel->getBookingByIdAndUserId($booking_id, $_SESSION['user']['id']);
                
                if ($booking && $booking['status'] === 'CONFIRMED') {
                    if ($reviewModel->hasReviewedBooking($booking_id)) {
                        $_SESSION['flash_error'] = "Bạn đã gửi đánh giá cho đơn đặt sân này rồi.";
                    } else {
                        $image_path = null;
                        if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
                            $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/reviews/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }
                            $fileName = time() . '_' . basename($_FILES['review_image']['name']);
                            $targetPath = $uploadDir . $fileName;
                            
                            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                            $fileType = mime_content_type($_FILES['review_image']['tmp_name']);
                            
                            if (in_array($fileType, $allowedTypes)) {
                                if (move_uploaded_file($_FILES['review_image']['tmp_name'], $targetPath)) {
                                    $image_path = 'public/uploads/reviews/' . $fileName;
                                }
                            }
                        }
                        
                        $reviewModel->createReview($_SESSION['user']['id'], $booking['pitch_id'], $booking_id, $comment, $image_path);
                        $_SESSION['flash_success'] = "Đánh giá của bạn đã được gửi thành công. Cảm ơn bạn đã đóng góp ý kiến!";
                    }
                } else {
                    $_SESSION['flash_error'] = "Không thể đánh giá sân này. Chỉ áp dụng cho các đơn đặt đã hoàn thành.";
                }
            } else {
                $_SESSION['flash_error'] = "Vui lòng nhập nội dung đánh giá.";
            }
        }
        
        header("Location: " . BASE_URL . "index.php?controller=booking&action=history");
        exit();
    }
}
