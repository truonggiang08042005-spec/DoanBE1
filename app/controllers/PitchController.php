<?php

require_once dirname(__DIR__) . '/models/Pitch.php';
require_once dirname(__DIR__) . '/models/Booking.php';

class PitchController {
    private $pitchModel;
    private $bookingModel;

    public function __construct() {
        $this->pitchModel = new Pitch();
        $this->bookingModel = new Booking();
    }

    public function detail() {
        $pitch = null;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $pitch = $this->pitchModel->getPitchById($id);
        }

        if (!$pitch) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found - Pitch not found.";
            return;
        }

        $error = $_SESSION['flash_error'] ?? '';
        unset($_SESSION['flash_error']);
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);

        // Load view
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/pitch/detail.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }
}
