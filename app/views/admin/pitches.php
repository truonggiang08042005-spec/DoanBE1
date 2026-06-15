<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Quản lý Sân bóng</h4>
        <div class="text-muted small">Thêm, sửa, xóa và cập nhật trạng thái sân.</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-gold rounded-pill fw-bold px-4" href="<?= BASE_URL ?>index.php?controller=pitch&action=createPitch">
            <i class="bi bi-plus-circle me-1"></i>Thêm sân mới
        </a>
    </div>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="px-4 py-3">Mã</th>
                    <th class="px-4 py-3">Ảnh</th>
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
                        $badge = $status === 'active' ? 'bg-success' : 'bg-warning';
                        ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-gold">#<?= (int)$p['id'] ?></td>
                            <td class="px-4 py-3">
                                <?php if (!empty($p['image'])): ?>
                                    <img src="<?= BASE_URL . 'public/uploads/' . htmlspecialchars($p['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-dark rounded" style="width: 80px; height: 60px;"></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 fw-bold text-white"><?= htmlspecialchars($p['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-4 py-3 text-light"><?= htmlspecialchars($p['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-4 py-3 text-end fw-bold text-success"><?= number_format((float)($p['price_per_hour'] ?? 0)) ?>đ</td>
                            <td class="px-4 py-3"><span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a class="btn btn-outline-primary btn-sm fw-bold rounded-3" href="<?= BASE_URL ?>index.php?controller=pitch&action=editPitch&id=<?= (int)$p['id'] ?>">
                                        Sửa
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>index.php?controller=pitch&action=deletePitch" onsubmit="return confirm('Xóa sân này?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                        <button class="btn btn-outline-danger btn-sm fw-bold rounded-3" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="px-4 py-5 text-center text-muted" colspan="7">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Chưa có sân nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>