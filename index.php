<?php
session_start();

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php', 'active' => true],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php'],
];

$stats = [
    ['value' => '25+',    'label' => 'Products'],
    ['value' => '1-2',    'label' => 'Day Shipping'],
    ['value' => '750+',   'label' => 'Happy Customer'],
    ['value' => '4,600+', 'label' => 'Orders Shipped'],
];

$brand_copy = 'TENSION is a powerful energy drink built for athletes, fitness '
    . 'enthusiasts, and gym warriors. It fuels strength, focus, and '
    . 'endurance — keeping you energized through every workout and beyond.';

$brand_variants = [
    ['name' => 'Lime',   'color' => '#AFFA01'],
    ['name' => 'Grapes', 'color' => '#6B4FA0'],
    ['name' => 'Apple',  'color' => '#A8433C'],
];

$products = [
    [
        'name'  => 'Tension Grapes',
        'color' => '#6B4FA0',
        'desc'  => 'Deep, bold, and intensely fruity, with a rich grape flavor that delivers a smooth and refreshing finish.',
        'price' => 3.50,
    ],
    [
        'name'  => 'Tension Apple',
        'color' => '#A8433C',
        'desc'  => 'Crisp, bright, and naturally refreshing, with a clean apple flavor that brings a sharp burst of fruity freshness.',
        'price' => 3.50,
    ],
    [
        'name'  => 'Tension Lime',
        'color' => '#8BC53F',
        'desc'  => 'Zesty and refreshing, with a vibrant lime kick that cuts through with a sharp citrus taste and a clean finish.',
        'price' => 3.50,
    ],
];

$training_features = [
    ['title' => 'Strength',    'desc' => 'Build a stronger foundation with focused resistance training and progressive workouts.'],
    ['title' => 'Performance', 'desc' => 'Improve your power, endurance, and overall performance through purposeful training.'],
    ['title' => 'Recovery',    'desc' => 'Train hard, recover smart. Learn how rest, mobility, and proper recovery support your progress.'],
];

$lifestyle_cards = [
    ['title' => 'Move With Purpose',   'desc' => 'Whether you train, compete, or simply stay active, keep pushing forward and make every movement count.'],
    ['title' => 'Your Pace. Your Way.', 'desc' => 'From early runs to everyday adventures, TENSION fits into a lifestyle built around movement and momentum.'],
];

$footer_columns = [
    'Policy'            => ['Shipping & Returns', 'Store Policy', 'Payment Methods', 'Cookies Policy', 'Terms on Use'],
    'Our Store'         => ['Salngan, Mayabon Street', 'Zamboanguita, Negros Oriental', 'Tel: 09876543210', 'Email: thetension100@gmail.com'],
    'Customer Service'  => ['Tel: 09876543210', 'Email: thetension100@gmail.com'],
];

$social_links = ['facebook', 'instagram', 'tiktok', 'pinterest', 'youtube', 'twitter'];

/** Reusable inline brand mark (the bars + slash logo icon) */
function brand_mark_svg($class = '') {
    $cls = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    return '<svg' . $cls . ' viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
        . '<rect x="4" y="6" width="20" height="3" fill="currentColor"/>'
        . '<rect x="4" y="12" width="14" height="3" fill="currentColor"/>'
        . '<rect x="4" y="30" width="20" height="3" fill="currentColor"/>'
        . '<rect x="4" y="24" width="14" height="3" fill="currentColor"/>'
        . '<rect x="27" y="8" width="4" height="22" transform="rotate(18 27 8)" fill="currentColor"/>'
        . '</svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — Home</title>
    <meta name="description" content="TENSION — quality fitness products for athletes and fitness enthusiasts.">

    <!-- Preview-only fallback fonts standing in for the brand's "Ultimate"
         and Garamond faces until the licensed font files are added
         (see README.txt). Safe to delete once /fonts/Ultimate.woff2 is in place. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- =========================
         HEADER / NAVIGATION
         ========================= -->
    <header class="site-header">

    <!-- LOGO -->
    <a href="index.php" class="logo">
        <span class="logo-word">
            <span class="accent">T</span>ension
        </span>
    </a>


    <!-- MAIN NAVIGATION -->
    <nav class="main-nav" aria-label="Main navigation">

        <?php foreach ($nav_links as $link): ?>

            <a
                href="<?= htmlspecialchars($link['href']) ?>"
                class="nav-link<?= !empty($link['active']) ? ' active' : '' ?>"
            >
                <?= htmlspecialchars(strtoupper($link['label'])) ?>
            </a>

        <?php endforeach; ?>

    </nav>


    <!-- HEADER ACTIONS -->
    <div class="header-actions">

        <!-- REGION -->
        <button
            type="button"
            aria-label="Change region"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
            >
                <circle cx="12" cy="12" r="9"/>
                <path d="M3 12h18"/>
                <path d="M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6-4-9s1.5-6.3 4-9z"/>
            </svg>
        </button>


        <!-- ACCOUNT -->
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>

            <a
                href="logout.php"
                class="header-icon-link"
                aria-label="Logout"
                title="Logout"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                </svg>
            </a>

        <?php else: ?>

            <a
                href="login.php"
                class="header-icon-link"
                aria-label="Login"
                title="Login"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                </svg>
            </a>

        <?php endif; ?>


        <!-- CART -->
        <a
            href="product.php"
            class="header-icon-link"
            aria-label="Shopping Cart"
            title="Shopping Cart"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
            >
                <path d="M6 8h12l-1 12H7L6 8z"/>
                <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
            </svg>
        </a>

    </div>

</header>

    <!-- =========================
         1. HERO
         ========================= -->
    <main class="hero">
        <div class="hero-visual" aria-hidden="true">
            <img src="images/tension-single-can.png" alt="TENSION energy drink can"
                 style="width:1000%; max-width:1700px; height:auto; object-fit:contain; transform:translate(2%, 2%) rotate(0deg); margin-right: 380px;">
        </div>

        <section class="hero-content">
            <h1 class="hero-title">
                LIFT THE<br>
                <span class="accent">PRESSURE</span>
                <em class="hero-kicker-inline"></em>
            </h1>

            <p class="hero-description">
                We provide quality fitness products designed to meet the
                needs of athletes and fitness enthusiasts — from everyday
                training to peak performance.
            </p>

            <div class="hero-buttons">
                <a href="shop.php" class="btn btn-primary">SHOP <span>&rarr;</span></a>
                <a href="#" class="btn btn-secondary">EXPLORE</a>
            </div>
        </section>

       
        <section class="stats" aria-label="TENSION statistics">
            <?php foreach ($stats as $i => $stat): ?>
                <div class="stat stat-<?= $i + 1 ?>">
                    <strong><?= htmlspecialchars($stat['value']) ?></strong>
                    <span><?= htmlspecialchars(strtoupper($stat['label'])) ?></span>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <!-- =========================
         2. BRAND INTRO
         ========================= -->
    <section class="brand-intro" aria-label="About TENSION">
        <div class="brand-panel">

            <h2 class="brand-heading"><span class="accent">T</span>ension</h2>
            <p class="brand-copy"><?= htmlspecialchars($brand_copy) ?></p>
        </div>

        <div class="brand-visual" aria-hidden="true">
            <img src="images/tension-cans-collection.png" alt="TENSION energy drink can collection"
                 style="width:1000%; max-width:1700px; height:auto; object-fit:contain; align-self:center; margin-bottom: 40px; margin-right: 350px;">
        </div>

    </section>

    <!-- =========================
         3. PRODUCT COLLECTION
         ========================= -->
    <section class="collection" aria-label="Product collection">
        <div class="collection-banner">
            <div class="collection-copy">
                <span class="eyebrow">Tension Collection</span>
                <h2>Built for training.<br><span class="accent">Built to last.</span></h2>
                <h3 class="collection-tagline">Classic <span class="accent">Original</span></h3>
            </div>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <div class="product-shot" style="background: <?= htmlspecialchars($product['color']) ?>;">
                        <?php if ($product['name'] === 'Tension Grapes'): ?>
                            <img src="images/tension-double-purple.png" alt="Tension Grapes energy drink can"
                                 style="width:100%; height:100%; object-fit:cover;">
                        <?php elseif ($product['name'] === 'Tension Apple'): ?>
                            <img src="images/tension-double-red.png" alt="Tension Apple energy drink can"
                                 style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <img src="images/tension-double-lime.png" alt="Tension Lime energy drink can"
                                 style="width:100%; height:100%; object-fit:cover;">
                        <?php endif; ?>
                    </div>
                    <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-desc"><?= htmlspecialchars($product['desc']) ?></p>
                    <div class="product-buy">
                        <button type="button" class="add-cart">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                            Add to cart
                        </button>
                        <div class="price-tag">$<?= number_format($product['price'], 2) ?></div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- =========================
         4. PROMO
         ========================= -->
    <section class="promo" aria-label="Promotion" style="background-image: linear-gradient(rgba(25, 25, 25, 0.72), rgba(25, 25, 25, 0.72)), url('images/background-2.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <h2 class="promo-heading">Promo</h2>

    <div class="promo-grid">
        <div class="promo-card">
            <span class="promo-side-label">Featured</span>
            <div class="promo-content">
                <h3 class="promo-kicker">Check out</h3>
                <h4 class="promo-title">TENSION Summer Live Tour</h4>
                <p>Your chance at getting Live Tour tickets or exclusive TENSION gear!</p>
                <a href="#" class="btn btn-primary">Enter Now</a>
            </div>
        </div>

       <div class="promo-visual"
                 aria-hidden="true"
                 style="
                    background-image: linear-gradient(rgba(0, 0, 0, 0.12), rgba(0, 0, 0, 0.12)), url('images/morgan.jpg');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                 ">
                <div class="promo-visual-copy">
                    <span class="promo-tag">
                        Unlock <span class="accent-red">Tour</span> Tickets!
                    </span>

                    <span class="promo-sub">
                        Summer Live Tour · 2026
                    </span>
                </div>
            </div>
        </div>


    <a href="#" class="btn btn-outline-block">
        Book the Fizz
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
</section>

    <!-- =========================
         5. TRAIN WITH TENSION
         ========================= -->
    <section class="training" aria-label="Training" > 
        <div class="training-left">
            <h2>Train With<br>Tension</h2>

            <?php foreach ($training_features as $feature): ?>
                <div class="training-feature">
                    <div class="feature-icon" aria-hidden="true">
                        <?php if ($feature['title'] === 'Strength'): ?>
                            <img src="images/pic 1.jpg" alt="Athlete training in a gym" style="width:100%; height:100%; object-fit:cover;">
                        <?php elseif ($feature['title'] === 'Performance'): ?>
                            <img src="images/pic 5.jpg" alt="Athlete during strength training" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <img src="images/pic 6.jpg" alt="Woman with a healthy drink by the beach" style="width:100%; height:100%; object-fit:cover;">
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4><?= htmlspecialchars(strtoupper($feature['title'])) ?></h4>
                        <p><?= htmlspecialchars($feature['desc']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <a href="#" class="btn btn-outline-dark">
                Start Training
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>

        <div class="training-right">
            <a href="#" class="btn btn-outline-dark training-explore">Explore Training</a>
            <div class="training-tagline">Turn effort into<br><span class="accent">Progress.</span></div>
            <div class="training-visual" aria-hidden="true">
                <img src="images/ChatGPT Image Aug 12, 2026, 12_42_10 AM.png" alt="TENSION energy drink athlete" style="width:100%; height:100%; object-fit:cover; display: block;">
            </div>
        </div>
    </section>

    <!-- =========================
         6. LIVE UNDER TENSION
         ========================= -->
     <section class="lifestyle" aria-label="Lifestyle" style="background-image: linear-gradient(180deg, rgba(0, 12, 0, 0.90) 0%, rgba(18, 55, 0, 0.78) 48%, rgba(150, 220, 20, 0.82) 100%), url('images/dynamic-monochrome-athletes-motion-showcasing-determination-active-lifestyle-white-background_209190-264798.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="lifestyle-head">
            <h2>Live Under<br><span class="accent">Tension</span></h2>
            <p>The energy doesn't stop when the workout does. <span class="accent">TENSION</span> is built for the moments that keep you moving — from early mornings, long days, to late nights and everything in between.</p>
        </div>

        <div class="lifestyle-grid">
            <div class="lifestyle-main" style="aspect-ratio: auto; background: none; border: 0; border-radius: 0; padding: 0; display: block; overflow: visible;">
                <div style="width: 100%; aspect-ratio: 3/4; border-radius: 20px; background-image: url('images/8166e4537552596ae0d19f1c55911e6a.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                <span class="lifestyle-cap" style="display: block; margin-top: 12px;">Fuel Your Every Move</span>
            </div>

            <?php foreach ($lifestyle_cards as $index => $card): ?>
                <div class="lifestyle-card">
                    <div class="lifestyle-block" aria-hidden="true" style="aspect-ratio: 4/4; background-image: url('<?= $index === 0 ? 'images/Under-Armour-Athlete-Robyn-Moodaly-2.jpg' : 'images/Running.webp' ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                    <h4><?= htmlspecialchars($card['title']) ?></h4>
                    <p><?= htmlspecialchars($card['desc']) ?></p>
                    <a href="#" class="read-more">
                        Read more
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- =========================
         7. INSIDER ACCESS + FOOTER
         ========================= -->
   <section class="newsletter" aria-label="Newsletter signup">

    <div class="newsletter-visual"
         aria-hidden="true"
         style="
            background-image: url('images/7p.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
         ">
    </div>

    <div class="newsletter-content">
        <h2>Insider Access</h2>

        <p>
            Catch every flavor drop, giveaway, and headline-making news.
        </p>

        <form class="signup-form" action="#" method="post">
            <label for="newsletter-email" class="sr-only">
                Email address
            </label>

            <input
                type="email"
                id="newsletter-email"
                name="email"
                placeholder="Email"
                required
            >

            <button type="submit">
                get access
            </button>
        </form>

        <div class="footer-links-row">

            <div>
                <h4>Company</h4>
                <a href="#">Products</a>
                <a href="#">Promotion</a>
                <a href="#">Events</a>
            </div>

            <div>
                <h4>Support</h4>
                <a href="#">Find in store</a>
                <a href="#">FAQs</a>
                <a href="#">Contact us</a>
            </div>

            <div>
                <h4>Explore</h4>
            </div>

        </div>
    </div>

</section>

    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <h4>Policy</h4>
                <?php foreach ($footer_columns['Policy'] as $item): ?>
                    <a href="#"><?= htmlspecialchars($item) ?></a>
                <?php endforeach; ?>
            </div>
            <div>
                <h4>Our Store</h4>
                <?php foreach ($footer_columns['Our Store'] as $item): ?>
                    <p class="line"><?= htmlspecialchars($item) ?></p>
                <?php endforeach; ?>
            </div>
            <div>
                <h4>Customer Service</h4>
                <?php foreach ($footer_columns['Customer Service'] as $item): ?>
                    <p class="line"><?= htmlspecialchars($item) ?></p>
                <?php endforeach; ?>
                <div class="social-row">
                    <?php foreach ($social_links as $network): ?>
                        <a href="#" aria-label="<?= htmlspecialchars(ucfirst($network)) ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="12" cy="12" r="9"/></svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="footer-brand">
                <a href="#" class="logo">
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC. All Rights Reserved.</span>
            <span>Do Not Sell or Share My Personal Information</span>
        </div>
    </footer>

</body>
</html>
