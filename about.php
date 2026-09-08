<?php

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php', 'active' => true],
];

$about_stats = [
    ['value' => '25+',    'label' => 'Products'],
    ['value' => '1–2',    'label' => 'Day Shipping'],
    ['value' => '750+',   'label' => 'Happy Customers'],
    ['value' => '4,600+', 'label' => 'Orders Shipped'],
];

$values = [
    [
        'number' => '01',
        'title' => 'FUEL',
        'desc' => 'Energy built to keep you moving when the pressure rises and the goal gets bigger.'
    ],
    [
        'number' => '02',
        'title' => 'FOCUS',
        'desc' => 'A mindset centered on discipline, consistency, and showing up with purpose.'
    ],
    [
        'number' => '03',
        'title' => 'MOVEMENT',
        'desc' => 'From training sessions to everyday life, we believe progress starts with action.'
    ],
    [
        'number' => '04',
        'title' => 'COMMUNITY',
        'desc' => 'A growing culture of athletes, creators, and everyday people chasing something better.'
    ],
];

$flavors = [
    [
        'name' => 'LIME',
        'desc' => 'Zesty. Sharp. Refreshing.',
        'color' => '#AFFA01'
    ],
    [
        'name' => 'GRAPES',
        'desc' => 'Bold. Fruity. Smooth.',
        'color' => '#6B4FA0'
    ],
    [
        'name' => 'APPLE',
        'desc' => 'Crisp. Bright. Clean.',
        'color' => '#A8433C'
    ],
];

$footer_columns = [
    'Policy' => [
        'Shipping & Returns',
        'Store Policy',
        'Payment Methods',
        'Cookies Policy',
        'Terms on Use'
    ],
    'Our Store' => [
        'Salngan, Mayabon Street',
        'Zamboanguita, Negros Oriental',
        'Tel: 09876543210',
        'Email: thetension100@gmail.com'
    ],
    'Customer Service' => [
        'Tel: 09876543210',
        'Email: thetension100@gmail.com'
    ],
];

$social_links = [
    'facebook',
    'instagram',
    'tiktok',
    'pinterest',
    'youtube',
    'twitter'
];

function brand_mark_svg($class = '')
{
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

    <title>About — TENSION Energy Drink</title>

    <link rel="stylesheet" href="style.css">

    <style>

        /* =========================================================
           TENSION — ABOUT PAGE
           ========================================================= */

        .about-page {
            background: #080808;
            color: #fff;
        }

        .about-page main {
            overflow: hidden;
        }

        /* =========================================================
           HERO
           ========================================================= */

        .about-hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            padding: 150px 7vw 80px;
            overflow: hidden;
            background:
                radial-gradient(circle at 75% 45%, rgba(175,250,1,.13), transparent 30%),
                linear-gradient(135deg, #080808 0%, #111 58%, #191919 100%);
        }

        .about-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .18;
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 70px 70px;
        }

        .about-hero::after {
            content: "";
            position: absolute;
            width: 48vw;
            height: 130%;
            right: -18vw;
            top: -15%;
            background: var(--lime);
            transform: skewX(-18deg);
            opacity: .96;
        }

        .about-hero-content {
            width: 55%;
            position: relative;
            z-index: 2;
        }

        .about-eyebrow {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 25px;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        .about-eyebrow::before {
            content: "";
            width: 45px;
            height: 2px;
            background: var(--lime);
        }

        .about-hero h1 {
            margin: 0;
            font-family: var(--heading-font);
            font-size: clamp(4rem, 9vw, 9.5rem);
            line-height: .82;
            letter-spacing: -.045em;
            text-transform: uppercase;
        }

        .about-hero h1 span {
            color: var(--lime);
            display: block;
        }

        .about-hero-description {
            max-width: 620px;
            margin-top: 35px;
            font-family: var(--body-font);
            font-size: 21px;
            line-height: 1.55;
            color: rgba(255,255,255,.72);
        }

        .about-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 35px;
        }

        .about-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 175px;
            padding: 15px 24px;
            border: 1px solid rgba(255,255,255,.3);
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            transition: .25s ease;
        }

        .about-btn.primary {
            background: var(--lime);
            color: #000;
            border-color: var(--lime);
        }

        .about-btn:hover {
            transform: translateY(-3px);
        }

        .about-btn.secondary:hover {
            background: #fff;
            color: #000;
        }

        /* HERO ART */

        .about-hero-art {
            position: absolute;
            z-index: 3;
            width: min(430px, 38vw);
            height: 600px;
            right: 7vw;
            top: 50%;
            transform: translateY(-50%) rotate(5deg);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-ring {
            position: absolute;
            width: 420px;
            height: 420px;
            border: 1px solid rgba(0,0,0,.35);
            border-radius: 50%;
        }

        .hero-ring::before,
        .hero-ring::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(0,0,0,.2);
        }

        .hero-ring::before {
            inset: 30px;
        }

        .hero-ring::after {
            inset: 70px;
        }

        .hero-can {
            width: 230px;
            height: 460px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(30px 30px 30px rgba(0,0,0,.45));
        }

        .hero-number {
            position: absolute;
            bottom: 45px;
            right: 0;
            z-index: 5;
            padding: 14px 18px;
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
        }

        .hero-number strong {
            color: var(--lime);
            font-size: 20px;
            margin-right: 5px;
        }

        .hero-scroll {
            position: absolute;
            bottom: 30px;
            left: 7vw;
            z-index: 5;
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .2em;
            color: rgba(255,255,255,.55);
            writing-mode: vertical-rl;
        }

        /* =========================================================
           STORY
           ========================================================= */

        .about-story {
            position: relative;
            padding: 130px 7vw;
            background: #f4f4f1;
            color: #0a0a0a;
        }

        .about-story-grid {
            max-width: 1400px;
            margin: auto;
            display: grid;
            grid-template-columns: .7fr 1.4fr .9fr;
            gap: 55px;
            align-items: start;
        }

        .section-index {
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .2em;
            text-transform: uppercase;
        }

        .section-index span {
            color: #777;
        }

        .story-content h2 {
            font-family: var(--heading-font);
            font-size: clamp(3rem, 5vw, 6rem);
            line-height: .9;
            letter-spacing: -.04em;
            text-transform: uppercase;
        }

        .story-content h2 span {
            color: #87c500;
        }

        .story-content p {
            max-width: 680px;
            margin-top: 30px;
            font-family: var(--body-font);
            font-size: 20px;
            line-height: 1.65;
            color: #555;
        }

        .story-image {
            position: relative;
            min-height: 500px;
            overflow: hidden;
            background: #111;
        }

        .story-image img {
            width: 100%;
            height: 100%;
            min-height: 500px;
            object-fit: cover;
            filter: grayscale(100%);
            transition: .5s ease;
        }

        .story-image:hover img {
            filter: grayscale(0%);
            transform: scale(1.04);
        }

        .story-image::after {
            content: "EST. 2026";
            position: absolute;
            left: 20px;
            bottom: 20px;
            padding: 8px 12px;
            background: var(--lime);
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .14em;
        }

        /* =========================================================
           VALUES
           ========================================================= */

        .about-values {
            padding: 120px 7vw;
            background: #0b0b0b;
        }

        .values-heading {
            max-width: 1400px;
            margin: auto auto 65px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 30px;
        }

        .values-heading h2 {
            margin-top: 15px;
            font-family: var(--heading-font);
            font-size: clamp(3rem, 6vw, 7rem);
            line-height: .85;
            text-transform: uppercase;
            letter-spacing: -.04em;
        }

        .values-heading h2 span {
            color: var(--lime);
        }

        .values-heading p {
            max-width: 350px;
            color: rgba(255,255,255,.5);
            font-family: var(--body-font);
            font-size: 18px;
            line-height: 1.5;
        }

        .values-grid {
            max-width: 1400px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid rgba(255,255,255,.2);
            border-left: 1px solid rgba(255,255,255,.2);
        }

        .value-card {
            min-height: 330px;
            padding: 30px;
            border-right: 1px solid rgba(255,255,255,.2);
            border-bottom: 1px solid rgba(255,255,255,.2);
            position: relative;
            transition: .3s ease;
        }

        .value-card:hover {
            background: var(--lime);
            color: #000;
            transform: translateY(-8px);
        }

        .value-number {
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .15em;
            opacity: .5;
        }

        .value-card h3 {
            margin-top: 100px;
            font-family: var(--heading-font);
            font-size: 38px;
            letter-spacing: -.02em;
        }

        .value-card p {
            margin-top: 14px;
            color: rgba(255,255,255,.55);
            font-family: var(--body-font);
            font-size: 17px;
            line-height: 1.45;
        }

        .value-card:hover p {
            color: rgba(0,0,0,.7);
        }

        /* =========================================================
           MANIFESTO
           ========================================================= */

        .about-manifesto {
            position: relative;
            padding: 150px 7vw;
            background: var(--lime);
            color: #000;
            overflow: hidden;
        }

        .about-manifesto::before {
            content: "T";
            position: absolute;
            right: -40px;
            top: -100px;
            font-family: var(--heading-font);
            font-size: 45rem;
            line-height: 1;
            opacity: .08;
        }

        .manifesto-inner {
            max-width: 1300px;
            margin: auto;
            position: relative;
            z-index: 2;
        }

        .manifesto-label {
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .2em;
        }

        .manifesto-inner h2 {
            max-width: 1100px;
            margin-top: 30px;
            font-family: var(--heading-font);
            font-size: clamp(4rem, 8vw, 9rem);
            line-height: .82;
            letter-spacing: -.05em;
            text-transform: uppercase;
        }

        .manifesto-inner p {
            max-width: 620px;
            margin-top: 40px;
            font-family: var(--body-font);
            font-size: 21px;
            line-height: 1.55;
        }

        /* =========================================================
           FLAVOR / IDENTITY
           ========================================================= */

        .about-identity {
            padding: 120px 7vw;
            background: #151515;
        }

        .identity-grid {
            max-width: 1400px;
            margin: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 90px;
            align-items: center;
        }

        .identity-content .section-index {
            color: #fff;
        }

        .identity-content h2 {
            margin-top: 20px;
            font-family: var(--heading-font);
            font-size: clamp(3.5rem, 6vw, 7rem);
            line-height: .85;
            text-transform: uppercase;
            letter-spacing: -.04em;
        }

        .identity-content h2 span {
            color: var(--lime);
        }

        .identity-content > p {
            max-width: 580px;
            margin-top: 30px;
            font-family: var(--body-font);
            font-size: 19px;
            line-height: 1.55;
            color: rgba(255,255,255,.6);
        }

        .flavor-list {
            margin-top: 45px;
            border-top: 1px solid rgba(255,255,255,.2);
        }

        .flavor-item {
            display: grid;
            grid-template-columns: 50px 1fr auto;
            align-items: center;
            gap: 18px;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,.2);
            transition: .25s ease;
        }

        .flavor-item:hover {
            padding-left: 12px;
        }

        .flavor-dot {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.4);
        }

        .flavor-name {
            font-family: var(--heading-font);
            font-size: 24px;
        }

        .flavor-desc {
            color: rgba(255,255,255,.45);
            font-family: var(--body-font);
            font-size: 16px;
        }

        .flavor-arrow {
            font-size: 20px;
            transition: .25s ease;
        }

        .flavor-item:hover .flavor-arrow {
            color: var(--lime);
            transform: translateX(6px);
        }

        .identity-image {
            position: relative;
        }

        .identity-image img {
            width: 100%;
            height: 650px;
            object-fit: cover;
            filter: grayscale(100%);
            transition: .5s ease;
        }

        .identity-image:hover img {
            filter: grayscale(0%);
        }

        .identity-image::before {
            content: "TENSION";
            position: absolute;
            z-index: 2;
            top: 25px;
            left: 25px;
            padding: 8px 12px;
            background: #fff;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .15em;
        }

        /* =========================================================
           STATS
           ========================================================= */

        .about-stats {
            background: #050505;
            padding: 70px 7vw;
            border-top: 1px solid rgba(255,255,255,.12);
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .stats-grid {
            max-width: 1400px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .about-stat {
            padding: 20px 30px;
            border-right: 1px solid rgba(255,255,255,.15);
        }

        .about-stat:last-child {
            border-right: none;
        }

        .about-stat strong {
            display: block;
            font-family: var(--heading-font);
            font-size: clamp(3rem, 5vw, 5.5rem);
            line-height: .9;
            color: var(--lime);
        }

        .about-stat span {
            display: block;
            margin-top: 12px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
        }

        /* =========================================================
           CTA
           ========================================================= */

        .about-cta {
            position: relative;
            padding: 130px 7vw;
            background:
                linear-gradient(120deg, #111 0%, #111 65%, var(--lime) 65%);
        }

        .cta-inner {
            max-width: 1400px;
            margin: auto;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 50px;
        }

        .cta-inner h2 {
            max-width: 850px;
            font-family: var(--heading-font);
            font-size: clamp(4rem, 8vw, 9rem);
            line-height: .82;
            text-transform: uppercase;
            letter-spacing: -.05em;
        }

        .cta-inner h2 span {
            color: var(--lime);
        }

        .cta-side {
            min-width: 240px;
        }

        .cta-side p {
            margin-bottom: 25px;
            font-family: var(--body-font);
            font-size: 18px;
            line-height: 1.45;
            color: rgba(255,255,255,.6);
        }

        .cta-side .about-btn {
            background: var(--lime);
            color: #000;
            border-color: var(--lime);
        }

        /* =========================================================
           FOOTER REFINEMENT
           ========================================================= */

        .about-page .site-footer {
            position: relative;
            z-index: 5;
        }

        .about-page .footer-brand .logo {
            color: #fff;
        }

        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 1100px) {

            .about-hero-content {
                width: 65%;
            }

            .about-hero-art {
                opacity: .45;
                right: -5vw;
            }

            .about-story-grid {
                grid-template-columns: 1fr 1.7fr;
            }

            .story-image {
                grid-column: 1 / -1;
                max-height: 500px;
            }

            .values-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .identity-grid {
                gap: 50px;
            }

        }

        @media (max-width: 800px) {

            .about-hero {
                padding: 140px 25px 80px;
                min-height: 850px;
            }

            .about-hero::after {
                width: 80vw;
                right: -35vw;
            }

            .about-hero-content {
                width: 100%;
            }

            .about-hero h1 {
                font-size: clamp(4rem, 18vw, 7rem);
            }

            .about-hero-description {
                font-size: 18px;
            }

            .about-hero-art {
                width: 280px;
                height: 400px;
                right: -40px;
                top: 58%;
                opacity: .25;
            }

            .hero-can {
                width: 170px;
                height: 350px;
            }

            .hero-ring {
                width: 300px;
                height: 300px;
            }

            .about-story,
            .about-values,
            .about-identity,
            .about-manifesto,
            .about-cta {
                padding-left: 25px;
                padding-right: 25px;
            }

            .about-story-grid,
            .identity-grid {
                grid-template-columns: 1fr;
            }

            .values-heading {
                display: block;
            }

            .values-heading p {
                margin-top: 25px;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .value-card {
                min-height: 260px;
            }

            .value-card h3 {
                margin-top: 60px;
            }

            .identity-image img {
                height: 500px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .about-stat {
                padding: 25px 15px;
                border-bottom: 1px solid rgba(255,255,255,.15);
            }

            .about-stat:nth-child(2) {
                border-right: none;
            }

            .cta-inner {
                display: block;
            }

            .cta-side {
                margin-top: 45px;
            }

            .about-cta {
                background: #111;
            }

        }

        @media (max-width: 600px) {

            .about-hero-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .about-btn {
                width: 100%;
            }

            .story-image,
            .story-image img {
                min-height: 380px;
            }

            .flavor-item {
                grid-template-columns: 40px 1fr 20px;
            }

            .flavor-desc {
                font-size: 14px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .about-stat strong {
                font-size: 40px;
            }

            .footer-grid {
                grid-template-columns: 1fr !important;
            }

            .footer-brand {
                text-align: left !important;
            }

            .footer-brand .logo {
                justify-content: flex-start !important;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 12px;
            }
        }

    </style>
</head>

<body class="about-page">

    <!-- =====================================================
         HEADER
         ===================================================== -->

    <header class="site-header">

        <a href="index.php" class="logo" aria-label="TENSION Home">
            <span class="logo-word">
                <span class="accent">T</span>ension
            </span>
        </a>

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

        <div class="header-actions">

            <button type="button" aria-label="Change region">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M3 12h18"/>
                    <path d="M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6.3-4-9s1.5-6.3 4-9z"/>
                </svg>
            </button>

            <button type="button" aria-label="Account">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                </svg>
            </button>

            <button type="button" aria-label="Cart">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <path d="M6 8h12l-1 12H7L6 8z"/>
                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                </svg>
            </button>

        </div>

    </header>


    <main>

        <!-- =====================================================
             HERO
             ===================================================== -->

        <section class="about-hero">

            <div class="about-hero-content">

                <div class="about-eyebrow">
                    The Brand / Since 2026
                </div>

                <h1>
                    More Than
                    <span>Energy.</span>
                </h1>

                <p class="about-hero-description">
                    TENSION is built for people who refuse to stay still.
                    We create energy for training, movement, ambition,
                    and every moment where you choose to push further.
                </p>

                <div class="about-hero-actions">

                    <a href="shop.php" class="about-btn primary">
                        Shop The Energy
                    </a>

                    <a href="training.php" class="about-btn secondary">
                        Explore Training
                    </a>

                </div>

            </div>


            <div class="about-hero-art">

                <div class="hero-ring"></div>

                <img
                    src="images/tension-cans-collection.png"
                    alt="TENSION Energy Drink collection"
                    class="hero-can"
                >

                <div class="hero-number">
                    <strong>01</strong> WHO WE ARE
                </div>

            </div>


            <div class="hero-scroll">
                Scroll To Discover
            </div>

        </section>


        <!-- =====================================================
             STORY
             ===================================================== -->

        <section class="about-story">

            <div class="about-story-grid">

                <div class="section-index">
                    01 <span>/ The Idea</span>
                </div>

                <div class="story-content">

                    <h2>
                        Built Under
                        <span>Pressure.</span>
                        Designed For Progress.
                    </h2>

                    <p>
                        TENSION started with a simple idea:
                        energy should feel like movement.
                    </p>

                    <p>
                        Whether you're preparing for a hard training session,
                        chasing a personal goal, working late, or simply
                        refusing to slow down, TENSION exists to keep
                        momentum on your side.
                    </p>

                    <p>
                        We combine bold flavor, strong visual identity,
                        and an unapologetic mindset to create more than
                        an energy drink — we are building a culture around
                        progress.
                    </p>

                </div>

                <div class="story-image">

                    <img
                        src="images/morgan.jpg"
                        alt="TENSION lifestyle"
                    >

                </div>

            </div>

        </section>


        <!-- =====================================================
             VALUES
             ===================================================== -->

        <section class="about-values">

            <div class="values-heading">

                <div>

                    <div class="section-index">
                        02 <span>/ What Drives Us</span>
                    </div>

                    <h2>
                        The <span>Pressure</span><br>
                        To Progress.
                    </h2>

                </div>

                <p>
                    Everything TENSION represents comes back to one
                    principle: don't wait for motivation.
                    Create momentum.
                </p>

            </div>


            <div class="values-grid">

                <?php foreach ($values as $value): ?>

                    <article class="value-card">

                        <div class="value-number">
                            <?= htmlspecialchars($value['number']) ?>
                        </div>

                        <h3>
                            <?= htmlspecialchars($value['title']) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($value['desc']) ?>
                        </p>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>


        <!-- =====================================================
             MANIFESTO
             ===================================================== -->

        <section class="about-manifesto">

            <div class="manifesto-inner">

                <div class="manifesto-label">
                    TENSION / MANIFESTO
                </div>

                <h2>
                    Pressure
                    Creates
                    Progress.
                </h2>

                <p>
                    We don't believe progress is supposed to be easy.
                    The challenge is part of the process. The pressure
                    is part of the story. Keep moving. Keep building.
                    Stay under TENSION.
                </p>

            </div>

        </section>


        <!-- =====================================================
             FLAVORS / BRAND IDENTITY
             ===================================================== -->

        <section class="about-identity">

            <div class="identity-grid">

                <div class="identity-content">

                    <div class="section-index">
                        03 <span>/ The Identity</span>
                    </div>

                    <h2>
                        Bold
                        <span>By Nature.</span>
                    </h2>

                    <p>
                        Every TENSION flavor is designed to have its own
                        personality while staying unmistakably part of
                        the same world.
                    </p>


                    <div class="flavor-list">

                        <?php foreach ($flavors as $flavor): ?>

                            <div class="flavor-item">

                                <div
                                    class="flavor-dot"
                                    style="background: <?= htmlspecialchars($flavor['color']) ?>;"
                                ></div>

                                <div>

                                    <div class="flavor-name">
                                        <?= htmlspecialchars($flavor['name']) ?>
                                    </div>

                                    <div class="flavor-desc">
                                        <?= htmlspecialchars($flavor['desc']) ?>
                                    </div>

                                </div>

                                <div class="flavor-arrow">
                                    →
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>


                <div class="identity-image">

                    <img
                        src="images/tension-cans-collection.png"
                        alt="TENSION Energy Drink flavors"
                    >

                </div>

            </div>

        </section>


        <!-- =====================================================
             STATS
             ===================================================== -->

        <section class="about-stats">

            <div class="stats-grid">

                <?php foreach ($about_stats as $stat): ?>

                    <div class="about-stat">

                        <strong>
                            <?= htmlspecialchars($stat['value']) ?>
                        </strong>

                        <span>
                            <?= htmlspecialchars($stat['label']) ?>
                        </span>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>


        <!-- =====================================================
             FINAL CTA
             ===================================================== -->

        <section class="about-cta">

            <div class="cta-inner">

                <h2>
                    Stay Under
                    <span>TENSION.</span>
                </h2>

                <div class="cta-side">

                    <p>
                        Your next goal is waiting.
                        Fuel the movement and keep going.
                    </p>

                    <a href="shop.php" class="about-btn">
                        Enter The Shop →
                    </a>

                </div>

            </div>

        </section>

    </main>


    <!-- =====================================================
         FOOTER
         ===================================================== -->

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

                        <a
                            href="#"
                            aria-label="<?= htmlspecialchars(ucfirst($network)) ?>"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.4"
                            >
                                <circle cx="12" cy="12" r="9"/>
                            </svg>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>


            <div class="footer-brand">

                <a href="index.php" class="logo">

                    <span class="logo-word">
                        <span class="accent">T</span>ension
                    </span>

                </a>

            </div>

        </div>


        <div class="footer-bottom">

            <span>
                &copy; <?= date('Y') ?> —
                TENSION Energy Drink Company LLC.
                All Rights Reserved.
            </span>

            <span>
                Do Not Sell or Share My Personal Information
            </span>

        </div>

    </footer>

</body>

</html>