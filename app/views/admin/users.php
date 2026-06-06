<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-black mb-1">Quản lý Khách hàng</h4>
        <div class="text-muted small">Danh sách tất cả khách hàng đã đăng ký.</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=dashboard">
            <i class="bi bi-speedometer2 me-1"></i>Về Dashboard
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Tài khoản</th>
                        <th class="px-4 py-3">Họ tên</th>
                        <th class="px-4 py-3">Số điện thoại</th>
                        <th class="px-4 py-3">Ngày tham gia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold">#<?= (int)$u['id'] ?></td>
                                <td class="px-4 py-3 fw-bold"><?= htmlspecialchars($u['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($u['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($u['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($u['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-4 text-center text-muted" colspan="5">Chưa có khách hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
