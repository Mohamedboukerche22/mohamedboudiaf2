</main>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>نظام إدارة المدرسة</h3>
                    <p>نظام متكامل لإدارة المدرسة الإلكترونية</p>
                    <p>الإصدار 1.0.0</p>
                </div>
                
                <div class="footer-col">
                    <h3>روابط سريعة</h3>
                    <ul>
                        <li><a href="../index.php"><i class="fas fa-arrow-left"></i> الرئيسية</a></li>
                        <li><a href="../news.php"><i class="fas fa-arrow-left"></i> الأخبار</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="../profile.php"><i class="fas fa-arrow-left"></i> الملف الشخصي</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>اتصل بنا</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> - نظام إدارة المدرسة</p>
                <div class="footer-links">
                    <a href="#">سياسة الخصوصية</a>
                    <a href="#">شروط الاستخدام</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="../assets/js/script.js"></script>
</body>
</html>