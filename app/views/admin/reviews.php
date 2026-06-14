<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Quản lý Đánh giá / Phản hồi</h4>
        <div class="text-muted small">Xem và phản hồi các ý kiến đóng góp từ khách hàng.</div>
    </div>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="table-light-dark">
                <tr>
                    <th class="px-4 py-3">Khách hàng</th>
                    <th class="px-4 py-3">Sân / Ngày đặt</th>
                    <th class="px-4 py-3" style="width: 35%;">Nội dung đánh giá</th>
                    <th class="px-4 py-3 text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $r): ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-white"><?= htmlspecialchars($r['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small">@<?= htmlspecialchars($r['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-gold"><?= htmlspecialchars($r['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="text-muted small">Đá ngày: <?= date('d/m/Y', strtotime($r['booking_date'])) ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-light mb-2"><?= nl2br(htmlspecialchars($r['comment'] ?? '', ENT_QUOTES, 'UTF-8')) ?></div>
                                <?php if (!empty($r['image_path'])): ?>
                                    <a href="<?= BASE_URL . $r['image_path'] ?>" target="_blank" class="text-decoration-none">
                                        <img src="<?= BASE_URL . $r['image_path'] ?>" alt="Review Image" class="rounded-3" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid rgba(255,255,255,0.1);">
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <button type="button" class="btn btn-sm btn-gold rounded-pill px-3" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#replyModal" 
                                        data-review-id="<?= $r['id'] ?>"
                                        data-customer="<?= htmlspecialchars($r['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    Phản hồi
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-4 py-5 text-center text-muted">
                            <i class="bi bi-chat-heart fs-1 d-block mb-3 opacity-50"></i>
                            Tuyệt vời! Hiện tại không có đánh giá nào đang chờ xử lý.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-secondary bg-dark text-light">
            <form method="POST" action="<?= BASE_URL ?>index.php?controller=review&action=replyReview">
                <div class="modal-header border-bottom border-secondary pb-3">
                    <h5 class="modal-title fw-bold text-gold" id="replyModalLabel">Phản hồi khách hàng: <span id="replyCustomerName" class="text-white"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="review_id" id="replyReviewId" value="">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nội dung trả lời <span class="text-danger">*</span></label>
                        <textarea name="admin_reply" class="form-control bg-dark text-white border-secondary rounded-3" rows="5" placeholder="Cảm ơn quý khách đã sử dụng dịch vụ..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary pt-3">
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gold rounded-pill px-4 fw-bold">Gửi phản hồi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var replyModal = document.getElementById('replyModal');
    if (replyModal) {
        replyModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var reviewId = button.getAttribute('data-review-id');
            var customerName = button.getAttribute('data-customer');
            
            replyModal.querySelector('#replyReviewId').value = reviewId;
            replyModal.querySelector('#replyCustomerName').textContent = customerName;
        });
    }
});
</script>
