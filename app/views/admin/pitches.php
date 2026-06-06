<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h4 class="fw-black mb-1">Quản lý danh mục sân</h4>
        <div class="text-muted small">Thêm, sửa, xóa và cập nhật trạng thái sân.</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-success rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=createPitch">
            <i class="bi bi-plus-circle me-1"></i>Thêm sân mới
        </a>
        <a class="btn btn-outline-success rounded-3 fw-bold" href="<?= BASE_URL ?>index.php?controller=admin&action=bookings">
            <i class="bi bi-arrow-left me-1"></i>Về bookings
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Mã</th>
                        <th class="px-4 py-3">Tên sân</th>
                        <th class="px-4 py-3">Loại</th>
                        <th class="px-4 py-3 text-end">Giá/giờ</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pitches)): ?>
                        <?php foreach ($pitches as $p): ?>
                            <?php
                            $status = $p['status'] ?? 'active';
                            $badge = $status === 'active' ? 'text-bg-success' : 'text-bg-warning';
                            ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold">#<?= (int)$p['id'] ?></td>
                                <td class="px-4 py-3 fw-bold"><?= htmlspecialchars($p['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($p['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-end fw-bold text-success"><?= number_format((float)($p['price_per_hour'] ?? 0)) ?>đ</td>
                                <td class="px-4 py-3"><span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a class="btn btn-outline-primary btn-sm fw-bold rounded-3" href="<?= BASE_URL ?>index.php?controller=admin&action=editPitch&id=<?= (int)$p['id'] ?>">
                                            Sửa
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=deletePitch" onsubmit="return confirm('Xóa sân này?');">
                                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                            <button class="btn btn-outline-danger btn-sm fw-bold rounded-3" type="submit">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-4 text-center text-muted" colspan="6">Chưa có sân nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

