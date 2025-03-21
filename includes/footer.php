<footer class="hotel-footer">
    <div class="hotel-footer-container">
        <!-- About Us Section -->
        <div class="hotel-footer-section hotel-about">
            <h2>About Us</h2>
            <p>Welcome to <strong>YOUR HOTEL</strong>, where luxury meets comfort. Experience a relaxing stay with top-class amenities and exceptional service.</p>
            <p>Your perfect getaway awaits.</p>
        </div>

        <!-- Quick Links Section -->
        <div class="hotel-footer-section hotel-links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="../pages/index.php">Home</a></li>
                <li><a href="../pages/rooms.php">Rooms</a></li>
                <li><a href="../pages/about.php">About Us</a></li>
                <li><a href="../pages/contact.php">Contact</a></li>
                <li><a href="../pages/booking.php">Book Now</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="hotel-footer-section hotel-contact">
            <h2>Contact Us</h2>
            <p><i class="fas fa-map-marker-alt"></i> 123 Street, City, Country</p>
            <p><i class="fas fa-phone"></i> +123 456 7890</p>
            <p><i class="fas fa-envelope"></i> contact@yourhotel.com</p>
        </div>

        <!-- Social Media Section -->
        <div class="hotel-footer-section hotel-social">
            <h2>Follow Us</h2>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>

        <!-- Newsletter Subscription -->
        <div class="hotel-footer-section hotel-newsletter">
            <h2>Subscribe to Our Newsletter</h2>
            <form action="../scripts/subscribe.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="hotel-footer-bottom">
        <p>&copy; <?= date("Y"); ?> YOUR HOTEL. All rights reserved.</p>
        <p><a href="../pages/privacy.php">Privacy Policy</a> | <a href="../pages/terms.php">Terms & Conditions</a></p>
    </div>

    <!-- Back-to-Top Button -->
    <button id="backToTop" title="Go to top"><i class="fas fa-chevron-up"></i></button>
</footer>

<!-- Footer Scripts -->
<script src="../assets/js/ui-footer.js" defer></script>

<script>
    // Smooth Scroll to Top
    const backToTopButton = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 200) {
            backToTopButton.style.display = "block";
        } else {
            backToTopButton.style.display = "none";
        }
    });

    backToTopButton.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
</script>