<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - YOUR HOTEL</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Nitro Contact CSS -->
    <link rel="stylesheet" href="../assets/css/contact_nitro.css">
</head>

<body class="nitro-contact-page">

    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- ====================== Contact Section ====================== -->
    <section class="nitro-contact-section">
        <div class="nitro-contact-container">
            <div class="nitro-contact-card">
                <!-- Contact Info -->
                <div class="nitro-contact-info">
                    <h2 class="nitro-contact-title">Get in Touch</h2>
                    <p class="nitro-contact-description">Reach out to us anytime and we'll happily answer your questions.</p>
                    <div class="nitro-contact-details">
                        <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                        <p><i class="fas fa-envelope"></i> contact@yourhotel.com</p>
                        <p><i class="fas fa-map-marker-alt"></i> 123 Luxury Street, Surat, India</p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="nitro-contact-form">
                    <h2 class="nitro-form-title">Send Us a Message</h2>
                    <form id="nitro-contact-form" method="POST" action="process_contact.php">
                        <div class="nitro-input-group">
                            <input type="text" name="name" id="name" placeholder="Your Name" required>
                            <span class="nitro-icon"><i class="fas fa-user"></i></span>
                        </div>
                        <div class="nitro-input-group">
                            <input type="email" name="email" id="email" placeholder="Your Email" required>
                            <span class="nitro-icon"><i class="fas fa-envelope"></i></span>
                        </div>
                        <div class="nitro-input-group">
                            <textarea name="message" id="message" placeholder="Your Message" rows="4" required></textarea>
                            <span class="nitro-icon"><i class="fas fa-comment-dots"></i></span>
                        </div>
                        <button type="submit" class="nitro-btn-glow">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>

    <!-- Nitro Contact JS -->
    <script src="../assets/js/contact_nitro.js"></script>
</body>

</html>