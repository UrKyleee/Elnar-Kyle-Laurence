<?php
session_start();

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php', 'active' => true],
];

$about_stats = [
    ['value' => '25+',    'label' => 'Products Developed'],
    ['value' => '1–2',    'label' => 'Days Fast Shipping'],
    ['value' => '750+',   'label' => 'Happy Athletes'],
    ['value' => '4,600+', 'label' => 'Orders Shipped'],
];

$values = [
    [
        'number' => '01',
        'title'  => 'FUEL',
        'desc'   => 'Clean, uncompromised energy built to keep you moving when the pressure rises and targets demand high performance.'
    ],
    [
        'number' => '02',
        'title'  => 'FOCUS',
        'desc'   => 'A mindset centered on discipline, razor-sharp consistency, and showing up with relentless purpose every single day.'
    ],
    [
        'number' => '03',
        'title'  => 'MOVEMENT',
        'desc'   => 'From intense training sessions to everyday life, we believe real momentum starts with intentional action.'
    ],
    [
        'number' => '04',
        'title'  => 'COMMUNITY',
        'desc'   => 'A growing ecosystem of athletes, creators, and everyday achievers pushing each other toward peak performance.'
    ],
];

$flavors = [
    [
        'name'  => 'LIME',
        'desc'  => 'Zesty. Sharp. Refreshing.',
        'color' => '#AFFA01'
    ],
    [
        'name'  => 'GRAPES',
        'desc'  => 'Bold. Fruity. Smooth.',
        'color' => '#9B51E0'
    ],
    [
        'name'  => 'APPLE',
        'desc'  => 'Crisp. Bright. Clean.',
        'color' => '#FF4D4D'
    ],
];

$footer_columns = [
    'Policy'            => ['Shipping & Returns', 'Store Policy', 'Payment Methods', 'Cookies Policy', 'Terms on Use'],
    'Our Store'         => ['Salngan, Mayabon Street', 'Zamboanguita, Negros Oriental', 'Tel: 09876543210', 'Email: thetension100@gmail.com'],
    'Customer Service'  => ['Tel: 09876543210', 'Email: thetension100@gmail.com'],
];

$social_links = ['facebook', 'instagram', 'tiktok', 'pinterest', 'youtube', 'twitter'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About — TENSION Energy Drink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        :root {
            --lime: #AFFA01;
            --lime-glow: rgba(175, 250, 1, 0.2);
            --bg-dark: #08080a;
            --bg-card: #111115;
            --bg-card-hover: #17171d;
            --border-color: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(175, 250, 1, 0.35);
            
            --heading-font: 'Syne', sans-serif;
            --body-font: 'Inter', sans-serif;
            
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            
            --container-width: 1280px;
            --section-padding: 110px 0;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            font-family: var(--body-font);
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: 100%;
            max-width: var(--container-width);
            margin: 0 auto;
            padding: 0 2rem;
        }

        h1, h2, h3 {
            font-family: var(--heading-font);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            line-height: 0.95;
        }

        .section-heading {
            font-size: clamp(2.5rem, 5vw, 4.25rem);
            margin-bottom: 1rem;
        }

        .section-heading span {
            color: var(--lime);
        }

        .body-lead {
            font-size: 1.125rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--lime);
            margin-bottom: 1.25rem;
        }

        .eyebrow-dark {
            color: var(--text-muted);
        }

        .eyebrow-dark span {
            color: var(--text-primary);
        }

        .eyebrow::before {
            content: "";
            width: 24px;
            height: 2px;
            background: var(--lime);
            display: inline-block;
        }

        .eyebrow-dark::before {
            background: var(--text-muted);
        }

        .image-frame {
            position: relative;
            width: 100%;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%) contrast(1.05);
            transition: var(--transition-smooth);
        }

        .image-frame:hover img {
            filter: grayscale(0%) contrast(1);
            transform: scale(1.04);
        }

        .about-hero {
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            padding: 150px 0 80px;
            background: radial-gradient(circle at 50% 30%, rgba(175, 250, 1, 0.08) 0%, transparent 60%),
                        linear-gradient(180deg, #08080a 0%, #0d0d12 100%);
            overflow: hidden;
        }

        .hero-grid {
            max-width: 800px;
        }

        .about-hero h1 {
            font-size: clamp(3.5rem, 6.5vw, 6.5rem);
            margin-bottom: 1.5rem;
        }

        .about-hero h1 span {
            color: var(--lime);
            display: block;
            text-shadow: 0 0 30px var(--lime-glow);
        }

        .about-hero-description {
            max-width: 580px;
            margin-bottom: 2.5rem;
        }

        .about-hero-actions {
            display: flex;
            gap: 1rem;
        }

        .about-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 2.2rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: var(--transition-smooth);
            cursor: pointer;
        }

        .about-btn.primary {
            background: var(--lime);
            color: #000;
            border: 1px solid var(--lime);
            box-shadow: 0 4px 20px var(--lime-glow);
        }

        .about-btn.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(175, 250, 1, 0.4);
        }

        .about-btn.secondary {
            background: transparent;
            color: #fff;
            border: 1px solid var(--border-color);
        }

        .about-btn.secondary:hover {
            border-color: #fff;
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-3px);
        }

        .about-story {
            padding: var(--section-padding);
            background: #f4f4f6;
            color: #0d0d12;
        }

        .about-story-grid {
            display: grid;
            grid-template-columns: 0.35fr 1fr 0.85fr;
            gap: 3.5rem;
            align-items: start;
        }

        .story-content .section-heading span {
            color: #6da800;
        }

        .story-content p {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #4b4b52;
            margin-bottom: 1.25rem;
        }

        .story-image-frame {
            aspect-ratio: 3/4;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .story-image-frame::after {
            content: "EST. 2026";
            position: absolute;
            left: 20px;
            bottom: 20px;
            padding: 8px 16px;
            background: #000;
            color: var(--lime);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            border-radius: 4px;
        }

        .about-values {
            padding: var(--section-padding);
            background: var(--bg-dark);
        }

        .values-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 3.5rem;
            gap: 2rem;
        }

        .values-heading p {
            max-width: 400px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .value-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 2.25rem 1.75rem;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 280px;
        }

        .value-card:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-hover);
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        }

        .value-number {
            font-family: var(--heading-font);
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--lime);
            margin-bottom: 2rem;
        }

        .value-card h3 {
            font-size: 1.6rem;
            margin-bottom: 0.75rem;
        }

        .value-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .manifesto-wrapper {
            padding: 0 2rem;
            margin: 40px 0;
        }

        .about-manifesto {
            position: relative;
            padding: 100px 0;
            background: var(--lime);
            color: #000;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .about-manifesto::before {
            content: "TENSION";
            position: absolute;
            right: -2%;
            bottom: -15%;
            font-family: var(--heading-font);
            font-size: 20vw;
            font-weight: 800;
            line-height: 1;
            opacity: 0.06;
            pointer-events: none;
        }

        .manifesto-inner {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .manifesto-inner .eyebrow {
            color: #000;
        }

        .manifesto-inner .eyebrow::before {
            background: #000;
        }

        .manifesto-inner h2 {
            font-size: clamp(3rem, 6vw, 5.5rem);
            margin-bottom: 1.5rem;
        }

        .manifesto-inner p {
            max-width: 620px;
            font-size: 1.2rem;
            font-weight: 500;
            line-height: 1.6;
        }

        .about-identity {
            padding: var(--section-padding);
            background: #0b0b0f;
        }

        .identity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4.5rem;
            align-items: center;
        }

        .flavor-list {
            margin-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .flavor-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 0;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition-smooth);
        }

        .flavor-item-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .flavor-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            box-shadow: 0 0 10px currentColor;
            flex-shrink: 0;
        }

        .flavor-name {
            font-family: var(--heading-font);
            font-size: 1.3rem;
            font-weight: 800;
        }

        .flavor-desc {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .flavor-arrow {
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: var(--transition-smooth);
        }

        .flavor-item:hover {
            padding-left: 10px;
        }

        .flavor-item:hover .flavor-arrow {
            color: var(--lime);
            transform: translateX(6px);
        }

        .identity-image-frame {
            aspect-ratio: 4/5;
            max-height: 560px;
        }

        .about-stats {
            padding: 70px 0;
            background: var(--bg-dark);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .about-stat {
            text-align: center;
        }

        .about-stat strong {
            display: block;
            font-family: var(--heading-font);
            font-size: clamp(2.5rem, 4vw, 4.25rem);
            font-weight: 800;
            line-height: 1;
            color: var(--lime);
            margin-bottom: 0.5rem;
            text-shadow: 0 0 20px var(--lime-glow);
        }

        .about-stat span {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-secondary);
        }

        .about-cta {
            padding: 120px 0;
            background: linear-gradient(135deg, #0d0d12 0%, #15151c 100%);
        }

        .cta-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 4rem;
        }

        .cta-side {
            max-width: 340px;
        }

        .cta-side p {
            margin-bottom: 2rem;
        }

        @media (max-width: 1024px) {
            .about-hero-description {
                margin: 0 auto 2.5rem;
            }

            .about-story-grid {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .values-grid, .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .identity-grid {
                grid-template-columns: 1fr;
            }

            .cta-inner {
                flex-direction: column;
                text-align: center;
            }

            .cta-side {
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .values-grid, .stats-grid {
                grid-template-columns: 1fr;
            }

            .manifesto-wrapper {
                padding: 0 1rem;
            }
        }
    </style>
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

    <main>
        <!-- =====================================================
             HERO
             ===================================================== -->
        <section class="about-hero">
            <div class="container">
                <div class="hero-grid">
                    <div class="about-hero-content">
                        <div class="eyebrow">The Brand / Since 2026</div>
                        <h1>More Than <span>Energy.</span></h1>
                        <p class="body-lead about-hero-description">
                            TENSION is engineered for individuals who refuse mediocrity. 
                            We formulate high-octane energy designed for training, focus, 
                            and breaking past your limitations.
                        </p>
                        <div class="about-hero-actions">
                            <a href="shop.php" class="about-btn primary">Shop The Energy</a>
                            <a href="training.php" class="about-btn secondary">Explore Training</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
             STORY
             ===================================================== -->
        <section class="about-story">
            <div class="container">
                <div class="about-story-grid">
                    <div class="eyebrow eyebrow-dark">01 <span>/ The Idea</span></div>
                    
                    <div class="story-content">
                        <h2 class="section-heading">Built Under <span>Pressure.</span> Designed For Progress.</h2>
                        <p>TENSION started with a singular conviction: energy should drive meaningful movement, not temporary spikes followed by crashes.</p>
                        <p>Whether you're stepping into the gym, locking in for a high-stakes project, or pushing through the final stretch, TENSION keeps your momentum unstoppable.</p>
                        <p>We blend formulation precision, clean visuals, and an uncompromising lifestyle to build a culture dedicated to continuous improvement.</p>
                    </div>

                    <div class="image-frame story-image-frame">
                        <img src="images/morgan.jpg" alt="TENSION Athlete Training">
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
             VALUES
             ===================================================== -->
        <section class="about-values">
            <div class="container">
                <div class="values-heading">
                    <div>
                        <div class="eyebrow">02 / Core Values</div>
                        <h2 class="section-heading">The <span>Pressure</span><br>To Progress.</h2>
                    </div>
                    <p class="body-lead">Everything TENSION creates anchors back to one principle: don't wait for motivation. Create momentum.</p>
                </div>

                <div class="values-grid">
                    <?php foreach ($values as $value): ?>
                        <article class="value-card">
                            <div class="value-number"><?= htmlspecialchars($value['number']) ?></div>
                            <div>
                                <h3><?= htmlspecialchars($value['title']) ?></h3>
                                <p><?= htmlspecialchars($value['desc']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- =====================================================
             MANIFESTO
             ===================================================== -->
        <div class="manifesto-wrapper">
            <section class="about-manifesto">
                <div class="manifesto-inner">
                    <div class="eyebrow">TENSION / MANIFESTO</div>
                    <h2>Pressure Creates Progress.</h2>
                    <p>We don't believe progress is supposed to be easy. The challenge is part of the process. The pressure defines the breakthrough. Keep moving. Keep building. Stay under TENSION.</p>
                </div>
            </section>
        </div>

        <!-- =====================================================
             FLAVORS / IDENTITY
             ===================================================== -->
        <section class="about-identity">
            <div class="container">
                <div class="identity-grid">
                    <div class="identity-content">
                        <div class="eyebrow">03 / Flavor Profiles</div>
                        <h2 class="section-heading">Bold <span>By Nature.</span></h2>
                        <p class="body-lead">Every TENSION expression is formulated to offer a distinct, powerful taste profile with crisp performance benefits.</p>

                        <div class="flavor-list">
                            <?php foreach ($flavors as $flavor): ?>
                                <div class="flavor-item">
                                    <div class="flavor-item-left">
                                        <div class="flavor-dot" style="background-color: <?= htmlspecialchars($flavor['color']) ?>; color: <?= htmlspecialchars($flavor['color']) ?>;"></div>
                                        <div>
                                            <div class="flavor-name"><?= htmlspecialchars($flavor['name']) ?></div>
                                            <div class="flavor-desc"><?= htmlspecialchars($flavor['desc']) ?></div>
                                        </div>
                                    </div>
                                    <div class="flavor-arrow">&rarr;</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="image-frame identity-image-frame">
                        <img src="images/tension-cans-collection.png" alt="TENSION Flavors Lineup">
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
             STATS
             ===================================================== -->
        <section class="about-stats">
            <div class="container">
                <div class="stats-grid">
                    <?php foreach ($about_stats as $stat): ?>
                        <div class="about-stat">
                            <strong><?= htmlspecialchars($stat['value']) ?></strong>
                            <span><?= htmlspecialchars($stat['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- =====================================================
             CTA
             ===================================================== -->
        <section class="about-cta">
            <div class="container">
                <div class="cta-inner">
                    <h2 class="section-heading">Stay Under <span>TENSION.</span></h2>
                    <div class="cta-side">
                        <p class="body-lead">Your next breakthrough is waiting. Fuel the movement today.</p>
                        <a href="shop.php" class="about-btn primary">Enter The Shop &rarr;</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- =========================
         INSIDER ACCESS + FOOTER
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