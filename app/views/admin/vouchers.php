<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Quản lý Khuyến mãi</h4>
        <div class="text-light opacity-75 small">Tạo và quản lý các mã giảm giá cho người dùng.</div>
    </div>
    <a class="btn btn-gold rounded-pill fw-bold shadow-sm" href="<?= BASE_URL ?>index.php?controller=voucher&action=createVoucher">
        <i class="bi bi-plus-circle me-1"></i>Tạo mã mới
    </a>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="table-light-dark">
                <tr>
                    <th class="px-4 py-3">Mã giảm giá</th>
                    <th class="px-4 py-3">Số tiền giảm</th>
                    <th class="px-4 py-3">Đã dùng / Giới hạn</th>
                    <th class="px-4 py-3">Hạn sử dụng</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                <?php if (!empty($vouchers)): ?>
                    <?php foreach ($vouchers as $v): ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-gold"><?= htmlspecialchars($v['code'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-4 py-3 text-success fw-bold"><?= number_format((float)($v['discount_amount'] ?? 0)) ?>đ</td>
                            <td class="px-4 py-3">
                                <?= (int)$v['used_count'] ?> / <?= (int)$v['usage_limit'] === 0 ? 'KGH' : (int)$v['usage_limit'] ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php 
                                    $exp = strtotime($v['expires_at']);
                                    $isExpired = $exp < time();
                                ?>
                                <span class="<?= $isExpired ? 'text-danger' : 'text-light' ?>">
                                    <?= date('d/m/Y H:i', $exp) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($v['status'] === 'active' && !$isExpired): ?>
                                    <span class="badge bg-success rounded-pill">Hoạt động</span>
                                <?php elseif ($isExpired): ?>
                                    <span class="badge bg-danger rounded-pill">Hết hạn</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary rounded-pill">Đã khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="<?= BASE_URL ?>index.php?controller=voucher&action=editVoucher&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="<?= BASE_URL ?>index.php?controller=voucher&action=deleteVoucher" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-light opacity-50">
                            <i class="bi bi-ticket-perforated fs-1 d-block mb-3 opacity-50"></i>
                            Chưa có mã giảm giá nào được tạo.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
