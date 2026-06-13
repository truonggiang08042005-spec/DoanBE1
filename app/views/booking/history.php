<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-black mb-1">Lịch sử đặt sân</h4>
        <div class="text-muted small">Chỉ hiển thị các đơn đặt thuộc tài khoản hiện tại.</div>
    </div>
    <a class="btn btn-outline-secondary rounded-3" href="<?= BASE_URL ?>index.php">
        <i class="bi bi-house me-1"></i>Trang chủ
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Sân</th>
                        <th class="px-4 py-3">Ngày đá</th>
                        <th class="px-4 py-3">Khung giờ</th>
                        <th class="px-4 py-3 text-end">Tổng tiền</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $b): ?>
                            <?php
                            $status = $b['status'] ?? 'PENDING';
                            $badge = 'text-bg-secondary';
                            if ($status === 'CONFIRMED') $badge = 'text-bg-success';
                            if ($status === 'CANCELLED') $badge = 'text-bg-danger';
                            ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($b['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($b['pitch_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td class="px-4 py-3"><?= htmlspecialchars($b['booking_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars(($b['start_time'] ?? '') . ' - ' . ($b['end_time'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-end fw-bold text-success"><?= number_format((float)($b['total_price'] ?? 0)) ?>đ</td>
                                <td class="px-4 py-3">
                                    <span class="badge rounded-pill <?= $badge ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <?php 
                                        $startTs = strtotime($b['booking_date'] . ' ' . $b['start_time']);
                                    ?>
                                    <?php if ($status === 'CONFIRMED'): ?>
                                        <?php if (isset($reviewsByBooking[$b['id']])): ?>
                                            <?php $myReview = $reviewsByBooking[$b['id']]; ?>
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewReviewModal" 
                                                    data-pitch-name="<?= htmlspecialchars($b['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    data-comment="<?= htmlspecialchars($myReview['comment'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    data-reply="<?= htmlspecialchars($myReview['admin_reply'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    data-status="<?= htmlspecialchars($myReview['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    data-image="<?= !empty($myReview['image_path']) ? BASE_URL . $myReview['image_path'] : '' ?>">
                                                Xem đánh giá
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reviewModal" 
                                                    data-booking-id="<?= (int)$b['id'] ?>"
                                                    data-pitch-name="<?= htmlspecialchars($b['pitch_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                Đánh giá
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($status === 'PENDING' && $startTs > time() + 3600): ?>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?controller=booking&action=cancel" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt sân này không?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hủy</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-4 text-center text-muted" colspan="6">Bạn chưa có đơn đặt sân nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="<?= BASE_URL ?>index.php?controller=booking&action=submitReview" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="reviewModalLabel">Đánh giá Sân <span id="reviewPitchName" class="text-gold"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="booking_id" id="reviewBookingId" value="">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #495057;">Nội dung góp ý / đánh giá <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control rounded-3 text-dark" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về chất lượng sân, dịch vụ..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #495057;">Hình ảnh đính kèm (không bắt buộc)</label>
                        <input type="file" name="review_image" class="form-control rounded-3 text-dark" accept="image/jpeg,image/png,image/webp,image/gif">
                        <div class="form-text" style="color: #6c757d;">Chấp nhận JPG, PNG, WEBP, GIF. Kích thước nhỏ hơn 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gold rounded-pill px-4 fw-bold">Gửi đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Review Modal -->
<div class="modal fade" id="viewReviewModal" tabindex="-1" aria-labelledby="viewReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="viewReviewModalLabel">Đánh giá của bạn về Sân <span id="viewReviewPitchName" class="text-gold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #495057;">Nội dung đánh giá:</label>
                    <div class="p-3 bg-light rounded-3 text-dark border" id="viewReviewComment"></div>
                </div>
                
                <div class="mb-3" id="viewReviewImageContainer" style="display: none;">
                    <label class="form-label fw-semibold" style="color: #495057;">Hình ảnh đính kèm:</label>
                    <div>
                        <img src="" id="viewReviewImage" class="img-fluid rounded-3 border" style="max-height: 200px; object-fit: cover;">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold" style="color: #495057;">Trạng thái:</label>
                    <span id="viewReviewStatus" class="badge"></span>
                </div>

                <div class="mb-3" id="viewReviewReplyContainer" style="display: none;">
                    <label class="form-label fw-semibold text-primary">Phản hồi của Admin:</label>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary border border-primary border-opacity-25" id="viewReviewReply"></div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Review Modal Setup
    var reviewModal = document.getElementById('reviewModal');
    if (reviewModal) {
        reviewModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var bookingId = button.getAttribute('data-booking-id');
            var pitchName = button.getAttribute('data-pitch-name');
            
            reviewModal.querySelector('#reviewBookingId').value = bookingId;
            reviewModal.querySelector('#reviewPitchName').textContent = pitchName;
        });
    }

    // View Review Modal Setup
    var viewReviewModal = document.getElementById('viewReviewModal');
    if (viewReviewModal) {
        viewReviewModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var pitchName = button.getAttribute('data-pitch-name');
            var comment = button.getAttribute('data-comment');
            var reply = button.getAttribute('data-reply');
            var status = button.getAttribute('data-status');
            var image = button.getAttribute('data-image');
            
            viewReviewModal.querySelector('#viewReviewPitchName').textContent = pitchName;
            viewReviewModal.querySelector('#viewReviewComment').textContent = comment;
            
            var imgContainer = viewReviewModal.querySelector('#viewReviewImageContainer');
            var imgTag = viewReviewModal.querySelector('#viewReviewImage');
            if (image) {
                imgTag.src = image;
                imgContainer.style.display = 'block';
            } else {
                imgContainer.style.display = 'none';
                imgTag.src = '';
            }

            var statusTag = viewReviewModal.querySelector('#viewReviewStatus');
            if (status === 'REPLIED') {
                statusTag.className = 'badge bg-success';
                statusTag.textContent = 'Đã phản hồi';
            } else {
                statusTag.className = 'badge bg-warning text-dark';
                statusTag.textContent = 'Chờ phản hồi';
            }

            var replyContainer = viewReviewModal.querySelector('#viewReviewReplyContainer');
            var replyText = viewReviewModal.querySelector('#viewReviewReply');
            if (reply) {
                replyText.textContent = reply;
                replyContainer.style.display = 'block';
            } else {
                replyContainer.style.display = 'none';
                replyText.textContent = '';
            }
        });
    }
});
</script>

