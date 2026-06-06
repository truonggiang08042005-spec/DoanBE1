<?php

require_once dirname(__DIR__) . '/models/Pitch.php';

class HomeController {
    private $pitchModel;

    public function __construct() {
        $this->pitchModel = new Pitch();
    }

    public function index() {
        $keyword = trim($_GET['q'] ?? '');
        $type = trim($_GET['type'] ?? '');
        $location = trim($_GET['location'] ?? '');

        $pitches = $this->pitchModel->searchActivePitches($keyword, $type, $location);
        // Load view
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/home/index.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }
}
