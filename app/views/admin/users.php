<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Quản lý Tài khoản (Users)</h4>
        <div class="text-muted small">Danh sách toàn bộ thành viên và quản trị viên trong hệ thống.</div>
    </div>
    <a href="<?= BASE_URL ?>index.php?controller=admin&action=exportUsers" class="btn btn-success rounded-pill fw-bold shadow-sm">
        <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel (CSV)
    </a>
</div>



<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Tài khoản</th>
                    <th class="px-4 py-3">Họ & tên</th>
                    <th class="px-4 py-3">Số điện thoại</th>
                    <th class="px-4 py-3">Vai trò (Role)</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-gold">#<?= (int)$u['id'] ?></td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-white"><?= htmlspecialchars($u['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                            </td>
                            <td class="px-4 py-3 text-white">
                                <?= htmlspecialchars($u['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td class="px-4 py-3 text-light"><?= htmlspecialchars($u['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-4 py-3">
                                <?php if ($u['role'] === 'superadmin'): ?>
                                    <span class="badge bg-danger">Super Admin</span>
                                <?php elseif ($u['role'] === 'admin'): ?>
                                    <span class="badge badge-gold-light">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Khách hàng</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($u['status'] === 'active'): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Bị khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <?php if ($u['role'] !== 'superadmin' || ($_SESSION['user']['role'] === 'superadmin')): ?>
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>index.php?controller=admin&action=editUser&id=<?= $u['id'] ?>" title="Sửa thông tin">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=lockUser" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn <?= $u['status'] === 'active' ? 'KHÓA' : 'MỞ KHÓA' ?> tài khoản này?');">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $u['status'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="<?= $u['status'] === 'active' ? 'Khóa' : 'Mở khóa' ?>">
                                                <i class="bi <?= $u['status'] === 'active' ? 'bi-lock' : 'bi-unlock' ?>"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=deleteUser" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tài khoản này?');">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="px-4 py-5 text-center text-muted" colspan="7">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Chưa có dữ liệu người dùng.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
