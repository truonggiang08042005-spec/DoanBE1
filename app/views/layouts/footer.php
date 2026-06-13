</main>

<style>
    /* Thiết kế Footer đồng bộ hệ Dark Gold */
    footer.custom-dark-footer {
        background-color: #141b26 !important;
        border-top: 1px solid rgba(214, 175, 55, 0.15) !important;
        color: #94a3b8;
    }
    footer .footer-title {
        color: #d4af37 !important;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    footer .footer-link {
        color: #cbd5e1 !important;
        transition: all 0.2s ease;
    }
    footer .footer-link:hover {
        color: #d4af37 !important;
        padding-left: 4px; /* Hiệu ứng đẩy nhẹ khi hover */
    }
    
    /* Nút tải app phong cách Luxury */
    .btn-app-download {
        background: #0b0f17 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #e2e8f0 !important;
        transition: all 0.3s ease;
    }
    .btn-app-download:hover {
        border-color: #d4af37 !important;
        color: #d4af37 !important;
        box-shadow: 0 4px 12px rgba(214, 175, 55, 0.1);
    }

    /* Mạng xã hội tối giản */
    .social-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #0b0f17;
        color: #94a3b8;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .social-icon:hover {
        color: #000;
        background: #d4af37;
        box-shadow: 0 0 10px rgba(214, 175, 55, 0.3);
        transform: translateY(-2px);
    }
</style>

<footer class="custom-dark-footer mt-5" id="support">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-title fs-5 mb-3">SÂN CỎ MỖI NGÀY</div>
                <div class="pe-lg-4" style="line-height: 1.6;">Nền tảng kết nối và cung ứng các tổ hợp sân bóng mini cao cấp, mang lại trải nghiệm đặt lịch nhanh chóng, tiện lợi và minh bạch hàng đầu.</div>
                <div class="d-flex gap-2 mt-4">
                    <a href="#" class="social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-icon" title="TikTok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-2">
                <div class="footer-title mb-3">LIÊN HỆ</div>
                <div class="small mb-2 text-light"><i class="bi bi-telephone text-gold me-2"></i>0900 000 000</div>
                <div class="small mb-2 text-light"><i class="bi bi-envelope text-gold me-2"></i>support@sanco.com</div>
                <div class="small text-light"><i class="bi bi-geo-alt text-gold me-2"></i>TP. Hồ Chí Minh, VN</div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="footer-title mb-3">CHÍNH SÁCH & ĐIỀU KHOẢN</div>
                <div class="d-flex flex-column gap-2 small">
                    <a class="footer-link text-decoration-none" href="#">Chính sách bảo mật thông tin</a>
                    <a class="footer-link text-decoration-none" href="#">Điều khoản sử dụng dịch vụ</a>
                    <a class="footer-link text-decoration-none" href="#">Cơ chế giải quyết khiếu nại</a>
                    <a class="footer-link text-decoration-none" href="#">Trung tâm hỗ trợ khách hàng</a>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="footer-title mb-3">TẢI ỨNG DỤNG DI ĐỘNG</div>
                <div class="d-flex flex-column gap-2">
                    <a class="btn btn-app-download rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 py-2" href="#">
                        <i class="bi bi-apple fs-5"></i>App Store (iOS)
                    </a>
                    <a class="btn btn-app-download rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 py-2" href="#">
                        <i class="bi bi-google-play fs-5"></i>Google Play (Android)
                    </a>
                </div>
            </div>
        </div>
        
        <hr class="mt-5 mb-4" style="border-color: rgba(255,255,255,0.06);">
        
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-muted small">
            <div>&copy; <?= date('Y') ?> Sân Cỏ Mỗi Ngày. Mọi quyền được bảo lưu.</div>
            <div style="font-size: 0.8rem; opacity: 0.7;">Bản quyền thiết kế bởi Luxury Sports Style</div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tự động ẩn thông báo sau 5 giây
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>
</body>
</html>