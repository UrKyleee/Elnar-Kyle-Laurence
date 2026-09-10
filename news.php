<?php

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php', 'active' => true],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php'],
];

$news_articles = [
    [
        'category' => 'Featured',
        'date' => 'September 3, 2026',
        'title' => 'Fuel the Moment. Meet the New TENSION Experience.',
        'excerpt' => 'Discover the latest TENSION updates, fresh energy, and stories built around training, movement, and the moments that keep you going.',
        'image' => 'images/background-2.jpg',
        'featured' => true,
    ],
    [
        'category' => 'Announcement',
        'date' => 'August 28, 2026',
        'title' => 'TENSION Summer Live Tour Is Coming',
        'excerpt' => 'Get ready for an unforgettable summer. Follow TENSION for announcements, exclusive opportunities, and limited gear drops.',
        'image' => 'images/morgan.jpg',
    ],
    [
        'category' => 'Training',
        'date' => 'August 22, 2026',
        'title' => 'Turn Effort Into Progress',
        'excerpt' => 'Build better habits with purposeful strength work, performance-focused sessions, and smarter recovery.',
        'image' => 'images/pic 1.jpg',
    ],
    [
        'category' => 'Lifestyle',
        'date' => 'August 16, 2026',
        'title' => 'Live Under TENSION',
        'excerpt' => 'The energy does not stop after the workout. Keep moving through early mornings, long days, and everything in between.',
        'image' => 'images/Running.webp',
    ],
    [
        'category' => 'Community',
        'date' => 'August 10, 2026',
        'title' => 'Built for the People Who Keep Moving',
        'excerpt' => 'TENSION is more than a drink. It is a mindset for athletes, gym warriors, and anyone committed to moving forward.',
        'image' => 'images/Under-Armour-Athlete-Robyn-Moodaly-2.jpg',
    ],
    [
        'category' => 'Product',
        'date' => 'August 4, 2026',
        'title' => 'Find Your Flavor',
        'excerpt' => 'Explore TENSION Lime, Grapes, and Apple — bold flavors made for your everyday momentum.',
        'image' => 'images/tension-cans-collection.png',
    ],
];

$categories = ['All', 'Featured', 'Announcement', 'Training', 'Lifestyle', 'Community', 'Product'];

$footer_columns = [
    'Policy' => ['Shipping & Returns', 'Store Policy', 'Payment Methods', 'Cookies Policy', 'Terms on Use'],
    'Our Store' => ['Salngan, Mayabon Street', 'Zamboanguita, Negros Oriental', 'Tel: 09876543210', 'Email: thetension100@gmail.com'],
    'Customer Service' => ['Tel: 09876543210', 'Email: thetension100@gmail.com'],
];

$social_links = ['facebook', 'instagram', 'tiktok', 'pinterest', 'youtube', 'twitter'];

/** Reusable inline brand mark. */
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
    <title>TENSION — News</title>
    <meta name="description" content="TENSION News — latest announcements, training stories, product updates, events, and lifestyle.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        /* =========================================================
           TENSION NEWS PAGE
           Uses the existing brand system from style.css while
           keeping the news page self-contained.
           ========================================================= */

        .news-page {
            --news-gray: #161616;
            --news-light: #f0f0f0;
            background: #090909;
            color: #fff;
            min-height: 100vh;
        }

        .news-header {
            position: relative;
            min-height: 650px;
            overflow: hidden;
            background:
                linear-gradient(115deg, #050505 0%, #171717 42%, #323232 54%, #7fae18 100%);
        }

        .news-header::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: .22;
            background: repeating-linear-gradient(
                115deg,
                rgba(255,255,255,.06) 0px,
                rgba(255,255,255,.06) 2px,
                transparent 2px,
                transparent 42px
            );
            pointer-events: none;
        }

        .news-header::after {
            content: "NEWS";
            position: absolute;
            right: -25px;
            bottom: -75px;
            font-family: var(--heading-font);
            font-size: clamp(180px, 25vw, 430px);
            line-height: .8;
            font-style: italic;
            color: rgba(255,255,255,.055);
            pointer-events: none;
        }

        .news-topbar {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 26px var(--page-padding);
        }

        .news-topbar .logo {
            font-size: 2.5rem;
        }

        .news-topbar .main-nav {
            background: rgba(255,255,255,.13);
        }

        .news-topbar .nav-link {
            color: rgba(255,255,255,.72);
        }

        .news-topbar .nav-link:hover {
            color: #fff;
        }

        .news-topbar .nav-link.active {
            color: #000;
            background: rgba(255,255,255,.94);
        }

        .news-topbar .header-actions {
            color: #fff;
        }

        .news-hero {
            position: relative;
            z-index: 2;
            max-width: 1450px;
            margin: 0 auto;
            padding: 95px 64px 110px;
        }

        .news-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-family: var(--body-font);
            font-size: 17px;
            font-weight: 700;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: .18em;
            color: rgba(255,255,255,.8);
        }

        .news-eyebrow::before {
            content: "";
            width: 42px;
            height: 3px;
            background: var(--lime);
        }

        .news-hero h1 {
            max-width: 950px;
            font-family: var(--heading-font);
            font-size: clamp(90px, 11vw, 190px);
            line-height: .84;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -.02em;
        }

        .news-hero h1 .accent {
            color: var(--lime);
        }

        .news-hero-copy {
            max-width: 680px;
            margin-top: 34px;
            font-family: var(--body-font);
            font-size: 22px;
            line-height: 1.55;
            font-weight: 600;
            color: rgba(255,255,255,.86);
        }

        .news-scroll {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-top: 42px;
            padding: 13px 20px;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 999px;
            font-family: var(--body-font);
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .news-scroll span {
            color: var(--lime);
            font-size: 22px;
        }

        .news-content {
            background: #f1f1f1;
            color: #000;
            padding: 85px 64px 110px;
        }

        .news-content-inner {
            max-width: 1450px;
            margin: 0 auto;
        }

        .news-section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 35px;
        }

        .news-section-heading h2 {
            font-family: var(--heading-font);
            font-size: clamp(65px, 6vw, 105px);
            line-height: .9;
            font-style: italic;
            text-transform: uppercase;
        }

        .news-section-heading h2 .accent {
            color: #78a900;
        }

        .news-section-heading p {
            max-width: 500px;
            font-family: var(--body-font);
            font-size: 20px;
            line-height: 1.5;
            font-weight: 600;
            color: rgba(0,0,0,.68);
        }

        .news-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 45px;
        }

        .news-filter button {
            border: 1px solid #b8b8b8;
            background: transparent;
            color: #111;
            border-radius: 999px;
            padding: 11px 18px;
            font-family: var(--body-font);
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: .2s ease;
        }

        .news-filter button:hover,
        .news-filter button.active {
            background: #000;
            color: var(--lime);
            border-color: #000;
        }

        .featured-news {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            min-height: 500px;
            margin-bottom: 65px;
            overflow: hidden;
            background: #111;
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0,0,0,.13);
        }

        .featured-image {
            min-height: 500px;
            background-size: cover;
            background-position: center;
        }

        .featured-copy {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 55px;
            background: linear-gradient(145deg, #0a0a0a, #252525);
        }

        .article-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
            font-family: var(--body-font);
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .article-meta .category {
            color: #000;
            background: var(--lime);
            border-radius: 999px;
            padding: 6px 11px;
        }

        .article-meta .date {
            color: rgba(255,255,255,.6);
        }

        .featured-copy h3 {
            font-family: var(--heading-font);
            font-size: clamp(44px, 4vw, 68px);
            line-height: .95;
            font-style: italic;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .featured-copy p {
            max-width: 560px;
            color: rgba(255,255,255,.75);
            font-family: var(--body-font);
            font-size: 19px;
            line-height: 1.55;
            font-weight: 600;
            margin-bottom: 28px;
        }

        .news-read {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: fit-content;
            padding: 12px 18px;
            background: var(--lime);
            color: #000;
            border-radius: 4px;
            font-family: var(--heading-font);
            font-size: 20px;
            font-style: italic;
            text-transform: uppercase;
            transition: transform .2s ease, background .2s ease;
        }

        .news-read:hover {
            transform: translateY(-2px);
            background: #fff;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .news-card {
            background: #fff;
            border: 1px solid #dedede;
            border-radius: 16px;
            overflow: hidden;
            transition: transform .22s ease, box-shadow .22s ease;
        }

        .news-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 18px 38px rgba(0,0,0,.12);
        }

        .news-card-image {
            aspect-ratio: 16/10;
            background-size: cover;
            background-position: center;
        }

        .news-card-body {
            padding: 25px;
        }

        .news-card .article-meta {
            margin-bottom: 13px;
        }

        .news-card .article-meta .date {
            color: #777;
        }

        .news-card h3 {
            font-family: var(--heading-font);
            font-size: 30px;
            line-height: 1;
            font-style: italic;
            text-transform: uppercase;
            margin-bottom: 13px;
        }

        .news-card p {
            color: #555;
            font-family: var(--body-font);
            font-size: 17px;
            line-height: 1.5;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .news-card .news-read {
            background: #000;
            color: var(--lime);
            font-size: 17px;
            border-radius: 999px;
            padding: 10px 16px;
        }

        .news-cta {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 30px;
            margin-top: 75px;
            padding: 48px 55px;
            border-radius: 18px;
            background: #000;
            color: #fff;
        }

        .news-cta::after {
            content: "TENSION";
            position: absolute;
            right: -25px;
            bottom: -40px;
            font-family: var(--heading-font);
            font-size: 130px;
            font-style: italic;
            color: rgba(255,255,255,.05);
        }

        .news-cta h3 {
            position: relative;
            z-index: 1;
            font-family: var(--heading-font);
            font-size: clamp(38px, 4vw, 60px);
            font-style: italic;
            text-transform: uppercase;
        }

        .news-cta p {
            position: relative;
            z-index: 1;
            max-width: 680px;
            margin-top: 8px;
            color: rgba(255,255,255,.72);
            font-family: var(--body-font);
            font-size: 18px;
            line-height: 1.5;
            font-weight: 600;
        }

        .news-cta .btn {
            position: relative;
            z-index: 2;
            min-width: 175px;
        }

        /* Keep the existing footer/header structure visually consistent. */
        .news-page .newsletter {
            margin: 0;
        }

        @media (max-width: 1050px) {
            .news-topbar {
                flex-wrap: wrap;
            }

            .news-topbar .main-nav {
                order: 3;
                width: 100%;
                overflow-x: auto;
                justify-content: flex-start;
            }

            .news-hero {
                padding-top: 70px;
            }

            .featured-news {
                grid-template-columns: 1fr;
            }

            .featured-image {
                min-height: 360px;
            }

            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .news-cta {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .news-topbar {
                padding: 18px 18px;
            }

            .news-topbar .logo {
                font-size: 2rem;
            }

            .news-topbar .header-actions {
                gap: 12px;
            }

            .news-hero,
            .news-content {
                padding-left: 22px;
                padding-right: 22px;
            }

            .news-hero {
                padding-top: 55px;
                padding-bottom: 75px;
            }

            .news-hero h1 {
                font-size: clamp(70px, 18vw, 120px);
            }

            .news-hero-copy {
                font-size: 19px;
            }

            .news-section-heading {
                display: block;
            }

            .news-section-heading p {
                margin-top: 20px;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .featured-copy {
                padding: 32px 25px;
            }

            .featured-image {
                min-height: 280px;
            }

            .news-cta {
                padding: 35px 25px;
            }

            .news-cta::after {
                font-size: 75px;
            }
        }
    </style>
</head>

<body>

<div class="news-page">

    <!-- HEADER -->
    <header class="news-header">
        <div class="news-topbar">

            <a href="index.php" class="logo" aria-label="TENSION Home">
                <span class="logo-word"><span class="accent">T</span>ension</span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                <?php foreach ($nav_links as $link): ?>
                    <a href="<?= htmlspecialchars($link['href']) ?>"
                       class="nav-link<?= !empty($link['active']) ? ' active' : '' ?>">
                        <?= htmlspecialchars(strtoupper($link['label'])) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="header-actions">

                <button type="button" aria-label="Account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                    </svg>
                </button>

                <button type="button" aria-label="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M6 8h12l-1 12H7L6 8z"/>
                        <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                    </svg>
                </button>

            </div>
        </div>

        <!-- NEWS HERO -->
        <section class="news-hero">
            <span class="news-eyebrow">TENSION Journal</span>

            <h1>
                What's<br>
                <span class="accent">Happening.</span>
            </h1>

            <p class="news-hero-copy">
                Stay connected with the latest from TENSION — product drops,
                events, training inspiration, community stories, and everything
                that keeps the energy moving.
            </p>

            <a href="#latest-news" class="news-scroll">
                Explore the latest <span>&darr;</span>
            </a>
        </section>
    </header>

    <!-- NEWS CONTENT -->
    <main id="latest-news" class="news-content">
        <div class="news-content-inner">

            <div class="news-section-heading">
                <h2>Latest <span class="accent">News</span></h2>

                <p>
                    Fresh updates, stories, announcements, and inspiration
                    from the world of TENSION.
                </p>
            </div>

            <!-- CATEGORY FILTER -->
            <div class="news-filter" aria-label="News categories">
                <?php foreach ($categories as $index => $category): ?>
                    <button type="button"
                            class="<?= $index === 0 ? 'active' : '' ?>"
                            data-category="<?= htmlspecialchars($category) ?>">
                        <?= htmlspecialchars($category) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- FEATURED ARTICLE -->
            <?php foreach ($news_articles as $article): ?>
                <?php if (!empty($article['featured'])): ?>

                    <article class="featured-news"
                             data-category="<?= htmlspecialchars($article['category']) ?>">

                        <div class="featured-image"
                             style="background-image: linear-gradient(rgba(0,0,0,.08), rgba(0,0,0,.18)), url('<?= htmlspecialchars($article['image']) ?>');">
                        </div>

                        <div class="featured-copy">

                            <div class="article-meta">
                                <span class="category">
                                    <?= htmlspecialchars($article['category']) ?>
                                </span>

                                <span class="date">
                                    <?= htmlspecialchars($article['date']) ?>
                                </span>
                            </div>

                            <h3><?= htmlspecialchars($article['title']) ?></h3>

                            <p><?= htmlspecialchars($article['excerpt']) ?></p>

                            <a href="#" class="news-read">
                                Read Story <span>&rarr;</span>
                            </a>

                        </div>
                    </article>

                <?php endif; ?>
            <?php endforeach; ?>

            <!-- ARTICLE GRID -->
            <div class="news-grid">

                <?php foreach ($news_articles as $article): ?>

                    <?php if (empty($article['featured'])): ?>

                        <article class="news-card"
                                 data-category="<?= htmlspecialchars($article['category']) ?>">

                            <div class="news-card-image"
                                 style="background-image: linear-gradient(rgba(0,0,0,.04), rgba(0,0,0,.15)), url('<?= htmlspecialchars($article['image']) ?>');">
                            </div>

                            <div class="news-card-body">

                                <div class="article-meta">
                                    <span class="category">
                                        <?= htmlspecialchars($article['category']) ?>
                                    </span>

                                    <span class="date">
                                        <?= htmlspecialchars($article['date']) ?>
                                    </span>
                                </div>

                                <h3><?= htmlspecialchars($article['title']) ?></h3>

                                <p><?= htmlspecialchars($article['excerpt']) ?></p>

                                <a href="#" class="news-read">
                                    Read More <span>&rarr;</span>
                                </a>

                            </div>
                        </article>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

            <!-- NEWS CTA -->
            <section class="news-cta">

                <div>
                    <h3>Never Miss the Next Drop.</h3>

                    <p>
                        Join Insider Access for flavor drops, giveaways,
                        events, exclusive updates, and headline-making news.
                    </p>
                </div>

                <!-- UPDATED: GET ACCESS NOW REDIRECTS TO LOGIN.PHP -->
                <a href="login.php" class="btn btn-primary">
                    Get Access <span>&rarr;</span>
                </a>

            </section>

        </div>
    </main>

    <!-- NEWSLETTER / SAME FOOTER STRUCTURE -->
    <section id="newsletter" class="newsletter" aria-label="Newsletter signup">

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

            <div class="footer-links-row">

                <div>
                    <h4>Company</h4>
                    <a href="shop.php">Products</a>
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
                    <a href="news.php">News</a>
                    <a href="#">Training</a>
                    <a href="#">Lifestyle</a>
                </div>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">

        <div class="footer-grid">

            <div>
                <h4>Policy</h4>

                <?php foreach ($footer_columns['Policy'] as $item): ?>
                    <a href="#">
                        <?= htmlspecialchars($item) ?>
                    </a>
                <?php endforeach; ?>

            </div>

            <div>
                <h4>Our Store</h4>

                <?php foreach ($footer_columns['Our Store'] as $item): ?>
                    <p class="line">
                        <?= htmlspecialchars($item) ?>
                    </p>
                <?php endforeach; ?>

            </div>

            <div>
                <h4>Customer Service</h4>

                <?php foreach ($footer_columns['Customer Service'] as $item): ?>
                    <p class="line">
                        <?= htmlspecialchars($item) ?>
                    </p>
                <?php endforeach; ?>

                <div class="social-row">

                    <?php foreach ($social_links as $network): ?>

                        <a href="#"
                           aria-label="<?= htmlspecialchars(ucfirst($network)) ?>">

                            <svg viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.4">

                                <circle cx="12" cy="12" r="9"/>

                            </svg>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

            <div class="footer-brand">
                <a href="index.php"
                   class="logo"
                   aria-label="TENSION Home"></a>
            </div>

        </div>

        <div class="footer-bottom">
            <span>
                &copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC.
                All Rights Reserved.
            </span>

            <span>
                Do Not Sell or Share My Personal Information
            </span>
        </div>

    </footer>

</div>

<script>
    // Simple category filtering for the news cards.
    const filterButtons = document.querySelectorAll('.news-filter button');
    const cards = document.querySelectorAll('.news-card');
    const featured = document.querySelector('.featured-news');

    filterButtons.forEach(button => {

        button.addEventListener('click', () => {

            const selected = button.dataset.category;

            filterButtons.forEach(item =>
                item.classList.remove('active')
            );

            button.classList.add('active');

            if (featured) {

                featured.style.display =
                    selected === 'All' ||
                    featured.dataset.category === selected
                        ? 'grid'
                        : 'none';

            }

            cards.forEach(card => {

                card.style.display =
                    selected === 'All' ||
                    card.dataset.category === selected
                        ? 'block'
                        : 'none';

            });

        });

    });
</script>

</body>
</html>
```
