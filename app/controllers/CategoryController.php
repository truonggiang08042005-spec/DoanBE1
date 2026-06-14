<?php

require_once dirname(__DIR__) . '/models/Category.php';
require_once dirname(__DIR__) . '/models/Pitch.php';
require_once dirname(__DIR__) . '/models/ActivityLog.php';

class CategoryController {
    private $categoryModel;
    private $pitchModel;
    private $activityLogModel;

    public function __construct() {
        $this->categoryModel = new Category();
        $this->pitchModel = new Pitch();
        $this->activityLogModel = new ActivityLog();
    }

    private function requireAdmin() {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden";
            exit();
        }
    }

    public function categories() {
        $this->requireAdmin();
        $categories = $this->categoryModel->getAllCategories();
        
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/categories.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function createCategory() {
        $this->requireAdmin();

        $error = '';
        $category = ['name' => '', 'description' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category['name'] = trim($_POST['name'] ?? '');
            $category['description'] = trim($_POST['description'] ?? '');

            if ($category['name'] === '') {
                $error = 'Vui lòng nhập tên danh mục.';
            } else {
                $created = $this->categoryModel->createCategory($category['name'], $category['description']);
                if ($created) {
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'CREATE_CATEGORY', "Tạo danh mục mới: {$category['name']}");
                    $_SESSION['flash_success'] = "Thêm danh mục thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=category&action=categories");
                    exit();
                }
                $error = 'Không thể tạo danh mục.';
            }
        }

        $mode = 'create';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/category_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function editCategory() {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $error = 'Vui lòng nhập tên danh mục.';
            } else {
                $updated = $this->categoryModel->updateCategory($id, $name, $description);
                if ($updated) {
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'UPDATE_CATEGORY', "Cập nhật danh mục ID {$id}");
                    $_SESSION['flash_success'] = "Cập nhật danh mục thành công.";
                    header("Location: " . BASE_URL . "index.php?controller=category&action=categories");
                    exit();
                }
                $error = 'Không thể cập nhật danh mục.';
            }
            $category['name'] = $name;
            $category['description'] = $description;
        }

        $mode = 'edit';
        include dirname(__DIR__) . '/views/layouts/admin_header.php';
        include dirname(__DIR__) . '/views/admin/category_form.php';
        include dirname(__DIR__) . '/views/layouts/admin_footer.php';
    }

    public function deleteCategory() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                // Check if any pitches are using this category. If yes, prevent delete.
                $pitches = $this->pitchModel->getAllPitches();
                $hasPitches = false;
                foreach ($pitches as $p) {
                    if ((int)$p['category_id'] === $id) {
                        $hasPitches = true; break;
                    }
                }
                
                if ($hasPitches) {
                    $_SESSION['flash_error'] = "Không thể xóa danh mục này vì đang có sân thuộc danh mục này.";
                } else {
                    $this->categoryModel->deleteCategory($id);
                    $this->activityLogModel->logAction($_SESSION['user']['id'], 'DELETE_CATEGORY', "Xóa danh mục ID {$id}");
                    $_SESSION['flash_success'] = "Đã xóa danh mục thành công.";
                }
            }
        }
        header("Location: " . BASE_URL . "index.php?controller=category&action=categories");
        exit();
    }
}
