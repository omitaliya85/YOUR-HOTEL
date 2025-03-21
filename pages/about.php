<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - YOUR HOTEL</title>

    <!-- Ultra Nitro CSS -->
    <link rel="stylesheet" href="../assets/css/about_nitro.css">
</head>

<body class="nitro-about-page">

    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- ===================== Hero Section ===================== -->
    <section class="nitro-about-hero">
        <div class="nitro-overlay"></div>
        <div class="nitro-hero-content">
            <h1 class="nitro-title">Welcome to <span>YOUR HOTEL</span></h1>
            <p class="nitro-tagline">Where Luxury Meets Elegance</p>
        </div>
    </section>

    <!-- ===================== About Section ===================== -->
    <section class="nitro-about-section" id="about">
        <h2 class="nitro-heading">About Us</h2>
        <p class="nitro-description">
            Discover an exquisite blend of modern luxury and classic comfort. YOUR HOTEL provides unparalleled service
            and unforgettable experiences, offering guests a peaceful retreat with world-class amenities and
            hospitality.
        </p>

        <div class="nitro-grid">
            <div class="nitro-card">
                <h3>🌟 Our Story</h3>
                <p>
                    Established in 2025, YOUR HOTEL has grown to become a symbol of excellence, offering a
                    sanctuary for travelers seeking sophistication and comfort.
                </p>
            </div>
            <div class="nitro-card">
                <h3>🏆 Awards</h3>
                <p>
                    Winner of multiple hospitality awards, YOUR HOTEL is recognized for its commitment to
                    excellence and superior guest experiences.
                </p>
            </div>
            <div class="nitro-card">
                <h3>💼 Our Team</h3>
                <p>
                    Our team of professionals ensures that every guest enjoys an exceptional stay, delivering
                    personalized services that exceed expectations.
                </p>
            </div>
        </div>
    </section>

    <!-- ===================== Amenities Section ===================== -->
    <section class="nitro-amenities-section" id="amenities">
        <h2 class="nitro-heading">Amenities & Services</h2>
        <div class="nitro-grid">
            <div class="nitro-card">
                <h3>🍽️ Fine Dining</h3>
                <p>
                    Enjoy an extensive selection of gourmet cuisines prepared by our world-class chefs.
                </p>
            </div>
            <div class="nitro-card">
                <h3>🏊‍♂️ Infinity Pool</h3>
                <p>
                    Take a dip and unwind in our luxurious infinity pool with panoramic views.
                </p>
            </div>
            <div class="nitro-card">
                <h3>💆 Spa & Wellness</h3>
                <p>
                    Rejuvenate with exclusive spa treatments and holistic wellness therapies.
                </p>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>

    <!-- Nitro-Level JS -->
    <script src="../assets/js/about_nitro.js"></script>

</body>

</html>