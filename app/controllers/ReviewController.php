<?php

require_once dirname(__DIR__) . '/models/Review.php';
require_once dirname(__DIR__) . '/models/ActivityLog.php';

class ReviewController {
    private $reviewModel;
    private $activityLogModel;

    public function __construct() {
        $this->reviewModel = new Review();
        $this->activityLogModel = new ActivityLog();
    }

    private function requireAdmin() {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden";
            exit();
        }
    }

    public function reviews() {
        $this->requireAdmin();
        $reviews = $this->reviewModel->getPendingReviews();
        
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/reviews.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function replyReview() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['review_id'] ?? 0);
            $admin_reply = trim($_POST['admin_reply'] ?? '');
            
            if ($id > 0 && $admin_reply !== '') {
                $this->reviewModel->replyReview($id, $admin_reply);
                $this->activityLogModel->logAction($_SESSION['user']['id'], 'REPLY_REVIEW', "Phản hồi đánh giá ID {$id}");
                $_SESSION['flash_success'] = "Đã gửi phản hồi đánh giá thành công.";
            } else {
                $_SESSION['flash_error'] = "Vui lòng nhập nội dung phản hồi.";
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=review&action=reviews");
        exit();
    }
}
