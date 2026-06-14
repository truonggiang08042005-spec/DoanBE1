<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Danh mục sân bóng</h4>
        <div class="text-muted small">Quản lý các loại hình sân, hỗ trợ cho việc phân loại và tìm kiếm.</div>
    </div>
    <a class="btn btn-gold rounded-pill fw-bold" href="<?= BASE_URL ?>index.php?controller=category&action=createCategory">
        <i class="bi bi-plus-lg me-1"></i>Thêm danh mục mới
    </a>
</div>



<div class="admin-card p-0">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="table-light-dark">
                <tr>
                    <th scope="col" class="ps-4">ID</th>
                    <th scope="col">Tên danh mục</th>
                    <th scope="col">Mô tả chi tiết</th>
                    <th scope="col" class="text-end pe-4">Thao tác</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?= $cat['id'] ?></td>
                            <td>
                                <div class="fw-bold text-white"><?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?></div>
                            </td>
                            <td class="text-wrap" style="max-width: 300px;">
                                <?php if (!empty($cat['description'])): ?>
                                    <span class="text-white" title="<?= htmlspecialchars($cat['description'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars(mb_strimwidth($cat['description'], 0, 80, "..."), ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">Không có mô tả</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>index.php?controller=category&action=editCategory&id=<?= $cat['id'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>index.php?controller=category&action=deleteCategory" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Hệ thống sẽ chặn nếu danh mục đang được sử dụng.');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-3 opacity-50"></i>
                            Chưa có dữ liệu danh mục. Vui lòng thêm mới.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
