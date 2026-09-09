<?php

/* =========================================================
   TENSION — TRAINING PAGE
   ========================================================= */

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php', 'active' => true],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php'],
];

/* Training categories */
$training_categories = [
    [
        'number' => '01',
        'title' => 'Strength',
        'description' => 'Build a stronger foundation through progressive resistance training, compound movements, and structured workouts.',
        'image' => 'images/pic 1.jpg'
    ],
    [
        'number' => '02',
        'title' => 'Performance',
        'description' => 'Develop power, speed, endurance, and athletic performance through purposeful and challenging training.',
        'image' => 'images/pic 5.jpg'
    ],
    [
        'number' => '03',
        'title' => 'Endurance',
        'description' => 'Improve your stamina and conditioning so you can perform longer, move better, and stay consistent.',
        'image' => 'images/Running.webp'
    ],
    [
        'number' => '04',
        'title' => 'Recovery',
        'description' => 'Train smarter by giving your body the recovery it needs through mobility, rest, stretching, and proper preparation.',
        'image' => 'images/pic 6.jpg'
    ]
];

/* Training principles */
$principles = [
    [
        'number' => '01',
        'title' => 'Move',
        'text' => 'Start with movement. Build consistency before chasing intensity.'
    ],
    [
        'number' => '02',
        'title' => 'Push',
        'text' => 'Challenge yourself and gradually increase the demands of your training.'
    ],
    [
        'number' => '03',
        'title' => 'Recover',
        'text' => 'Give your body time to adapt, rebuild, and come back stronger.'
    ]
];

/* Training stats */
$stats = [
    ['value' => '04', 'label' => 'Training Areas'],
    ['value' => '24/7', 'label' => 'Stay Active'],
    ['value' => '100%', 'label' => 'Your Effort'],
    ['value' => '∞', 'label' => 'Keep Progressing']
];

/* Footer information */
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

/* Reusable brand mark */
function brand_mark_svg($class = '') {

    $cls = $class
        ? ' class="' . htmlspecialchars($class) . '"'
        : '';

    return '<svg' . $cls . '
        viewBox="0 0 40 40"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true">

        <rect x="4" y="6" width="20" height="3" fill="currentColor"/>
        <rect x="4" y="12" width="14" height="3" fill="currentColor"/>
        <rect x="4" y="30" width="20" height="3" fill="currentColor"/>
        <rect x="4" y="24" width="14" height="3" fill="currentColor"/>
        <rect
            x="27"
            y="8"
            width="4"
            height="22"
            transform="rotate(18 27 8)"
            fill="currentColor"
        />

    </svg>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>TENSION — Training</title>

    <meta
        name="description"
        content="TENSION Training — strength, performance, endurance and recovery."
    >

    <!-- Existing TENSION fonts -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet"
    >

    <!-- Existing stylesheet -->
    <link
        rel="stylesheet"
        href="style.css"
    >

    <!-- =====================================================
         TRAINING PAGE STYLES
         These styles are page-specific and do not require
         changing your existing style.css.
         ===================================================== -->

    <style>

        /* =====================================================
           TRAINING PAGE VARIABLES
           ===================================================== */

        .training-page {
            --training-lime: #AFFA01;
            --training-black: #050505;
            --training-dark: #101010;
            --training-gray: #1b1b1b;
            --training-light: #eeeeee;
            --training-white: #ffffff;

            background: var(--training-black);
            color: var(--training-white);
        }


        /* =====================================================
           HEADER
           ===================================================== */

        .training-page .site-header {
            position: absolute;
        }

        .training-page .nav-link {
            color: rgba(255,255,255,.72);
        }

        .training-page .nav-link:hover {
            color: #ffffff;
        }

        .training-page .nav-link.active {
            background: #ffffff;
            color: #000000;
        }


        /* =====================================================
           HERO
           ===================================================== */

        .training-hero {
            position: relative;
            min-height: 88vh;

            overflow: hidden;

            display: grid;
            grid-template-columns: 1.05fr .95fr;
            align-items: center;

            padding: 150px 6vw 90px;

            background:
                radial-gradient(
                    circle at 80% 45%,
                    rgba(175,250,1,.20),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #050505 0%,
                    #101010 48%,
                    #252525 100%
                );
        }


        .training-hero::before {
            content: "TRAIN";
            position: absolute;

            right: -40px;
            bottom: -100px;

            font-family: var(--heading-font);
            font-size: clamp(180px, 25vw, 420px);
            line-height: .7;

            color: rgba(255,255,255,.025);

            pointer-events: none;
        }


        .training-hero-content {
            position: relative;
            z-index: 2;

            max-width: 800px;
        }


        .training-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 25px;

            font-family: var(--body-font);
            font-size: 16px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;

            color: var(--training-lime);
        }


        .training-eyebrow::before {
            content: "";
            width: 42px;
            height: 2px;

            background: var(--training-lime);
        }


        .training-hero-title {
            font-family: var(--heading-font);
            font-size: clamp(80px, 10vw, 170px);
            line-height: .83;
            font-style: italic;
            text-transform: uppercase;

            margin: 0 0 35px;
        }


        .training-hero-title .lime {
            color: var(--training-lime);
        }


        .training-hero-description {
            max-width: 600px;

            font-family: var(--body-font);
            font-size: clamp(19px, 2vw, 25px);
            line-height: 1.55;

            color: rgba(255,255,255,.75);

            margin-bottom: 35px;
        }


        .training-hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }


        .training-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;

            min-height: 50px;
            padding: 14px 26px;

            border-radius: 3px;

            font-family: var(--heading-font);
            font-size: 19px;
            font-style: italic;
            text-transform: uppercase;

            transition:
                transform .2s ease,
                background .2s ease,
                color .2s ease;
        }


        .training-btn:hover {
            transform: translateY(-3px);
        }


        .training-btn-primary {
            background: var(--training-lime);
            color: #000000;
        }


        .training-btn-secondary {
            border: 1px solid rgba(255,255,255,.45);
            color: #ffffff;
        }


        .training-btn-secondary:hover {
            background: rgba(255,255,255,.1);
        }


        /* =====================================================
           HERO VISUAL
           ===================================================== */

        .training-hero-visual {
            position: relative;

            min-height: 560px;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        .hero-image-frame {
            position: relative;

            width: min(90%, 560px);
            height: 620px;

            overflow: hidden;

            border-radius: 12px;

            background:
                linear-gradient(
                    145deg,
                    #242424,
                    #0b0b0b
                );

            border: 1px solid rgba(255,255,255,.12);
        }


        .hero-image-frame::after {
            content: "";

            position: absolute;
            inset: 0;

            background:
                linear-gradient(
                    180deg,
                    transparent 45%,
                    rgba(0,0,0,.72) 100%
                );

            pointer-events: none;
        }


        .hero-training-image {
            width: 100%;
            height: 100%;

            object-fit: cover;

            filter: grayscale(100%);
            transition: transform .6s ease;
        }


        .hero-image-frame:hover .hero-training-image {
            transform: scale(1.04);
        }


        .hero-image-label {
            position: absolute;

            z-index: 3;

            left: 30px;
            bottom: 28px;
        }


        .hero-image-label strong {
            display: block;

            font-family: var(--heading-font);
            font-size: 42px;
            font-style: italic;
            text-transform: uppercase;
        }


        .hero-image-label span {
            font-family: var(--body-font);
            font-size: 17px;

            color: rgba(255,255,255,.75);
        }


        .hero-lime-shape {
            position: absolute;

            width: 150px;
            height: 150px;

            right: -15px;
            top: 50px;

            background: var(--training-lime);

            clip-path: polygon(
                25% 0,
                100% 0,
                75% 100%,
                0 100%
            );

            opacity: .9;
        }


        /* =====================================================
           STATS
           ===================================================== */

        .training-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);

            border-top: 1px solid rgba(255,255,255,.1);
            border-bottom: 1px solid rgba(255,255,255,.1);

            background: #0b0b0b;
        }


        .training-stat {
            padding: 38px 25px;

            text-align: center;

            border-right: 1px solid rgba(255,255,255,.1);
        }


        .training-stat:last-child {
            border-right: none;
        }


        .training-stat strong {
            display: block;

            font-family: var(--heading-font);
            font-size: clamp(42px, 5vw, 70px);
            font-style: italic;

            color: var(--training-lime);
            line-height: 1;
        }


        .training-stat span {
            display: block;

            margin-top: 10px;

            font-family: var(--body-font);
            font-size: 15px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .12em;

            color: rgba(255,255,255,.6);
        }


        /* =====================================================
           TRAINING PROGRAMS
           ===================================================== */

        .training-programs {
            padding: 110px 6vw;

            background: #eeeeee;
            color: #000000;
        }


        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;

            gap: 40px;

            margin-bottom: 55px;
        }


        .section-heading-left {
            max-width: 700px;
        }


        .section-kicker {
            display: block;

            margin-bottom: 15px;

            font-family: var(--body-font);
            font-size: 16px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .16em;

            color: #6c8f00;
        }


        .section-title {
            font-family: var(--heading-font);
            font-size: clamp(65px, 8vw, 125px);
            line-height: .86;

            font-style: italic;
            text-transform: uppercase;
        }


        .section-title .lime {
            color: #7cae00;
        }


        .section-intro {
            max-width: 460px;

            font-family: var(--body-font);
            font-size: 20px;
            line-height: 1.55;

            color: rgba(0,0,0,.65);
        }


        .training-program-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);

            gap: 24px;
        }


        .training-program-card {
            position: relative;

            min-height: 530px;

            overflow: hidden;

            border-radius: 10px;

            background: #111111;
            color: #ffffff;
        }


        .training-program-card-image {
            position: absolute;
            inset: 0;

            overflow: hidden;
        }


        .training-program-card-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            filter: grayscale(100%);

            transition:
                transform .6s ease,
                filter .6s ease;
        }


        .training-program-card:hover img {
            transform: scale(1.06);
            filter: grayscale(30%);
        }


        .training-program-card-image::after {
            content: "";

            position: absolute;
            inset: 0;

            background:
                linear-gradient(
                    180deg,
                    rgba(0,0,0,.05) 25%,
                    rgba(0,0,0,.9) 100%
                );
        }


        .program-number {
            position: absolute;

            z-index: 3;

            top: 25px;
            right: 28px;

            font-family: var(--heading-font);
            font-size: 30px;
            font-style: italic;

            color: var(--training-lime);
        }


        .training-program-content {
            position: absolute;

            z-index: 3;

            left: 32px;
            right: 32px;
            bottom: 30px;
        }


        .training-program-content h3 {
            font-family: var(--heading-font);
            font-size: 55px;
            font-style: italic;

            text-transform: uppercase;

            margin-bottom: 10px;
        }


        .training-program-content p {
            max-width: 570px;

            font-family: var(--body-font);
            font-size: 18px;
            line-height: 1.5;

            color: rgba(255,255,255,.78);

            margin-bottom: 22px;
        }


        .program-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            font-family: var(--body-font);
            font-size: 15px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .08em;

            color: var(--training-lime);
        }


        .program-link span {
            font-family: Arial, sans-serif;
            font-size: 20px;
        }


        /* =====================================================
           TRAINING PHILOSOPHY
           ===================================================== */

        .training-philosophy {
            padding: 120px 6vw;

            background:
                linear-gradient(
                    135deg,
                    #AFFA01 0%,
                    #baf52b 50%,
                    #82b900 100%
                );

            color: #000000;
        }


        .philosophy-header {
            display: grid;
            grid-template-columns: .8fr 1.2fr;

            gap: 60px;

            margin-bottom: 70px;
        }


        .philosophy-title {
            font-family: var(--heading-font);
            font-size: clamp(65px, 8vw, 120px);
            line-height: .85;

            font-style: italic;
            text-transform: uppercase;
        }


        .philosophy-description {
            align-self: end;

            max-width: 650px;

            font-family: var(--body-font);
            font-size: 24px;
            line-height: 1.55;

            color: rgba(0,0,0,.72);
        }


        .principles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);

            border-top: 1px solid rgba(0,0,0,.3);
        }


        .principle {
            padding: 35px 30px 20px;

            border-right: 1px solid rgba(0,0,0,.3);
        }


        .principle:last-child {
            border-right: none;
        }


        .principle-number {
            display: block;

            font-family: var(--body-font);
            font-size: 15px;
            font-weight: 700;

            margin-bottom: 30px;
        }


        .principle h3 {
            font-family: var(--heading-font);
            font-size: 48px;
            font-style: italic;

            text-transform: uppercase;

            margin-bottom: 12px;
        }


        .principle p {
            max-width: 330px;

            font-family: var(--body-font);
            font-size: 18px;
            line-height: 1.5;

            color: rgba(0,0,0,.7);
        }


        /* =====================================================
           FEATURED TRAINING
           ===================================================== */

        .featured-training {
            display: grid;
            grid-template-columns: 1fr 1fr;

            min-height: 680px;

            background: #111111;
        }


        .featured-training-image {
            min-height: 600px;

            background:
                linear-gradient(
                    rgba(0,0,0,.15),
                    rgba(0,0,0,.15)
                ),
                url('images/ChatGPT Image Aug 12, 2026, 12_42_10 AM.png');

            background-size: cover;
            background-position: center;
        }


        .featured-training-content {
            display: flex;
            flex-direction: column;
            justify-content: center;

            padding: 80px 7vw;

            color: #ffffff;
        }


        .featured-training-content .section-kicker {
            color: var(--training-lime);
        }


        .featured-training-content h2 {
            font-family: var(--heading-font);
            font-size: clamp(70px, 8vw, 120px);
            line-height: .85;

            font-style: italic;
            text-transform: uppercase;

            margin-bottom: 30px;
        }


        .featured-training-content h2 span {
            color: var(--training-lime);
        }


        .featured-training-content p {
            max-width: 530px;

            font-family: var(--body-font);
            font-size: 21px;
            line-height: 1.55;

            color: rgba(255,255,255,.7);

            margin-bottom: 35px;
        }


        .featured-list {
            list-style: none;

            margin: 0 0 35px;
            padding: 0;

            max-width: 500px;
        }


        .featured-list li {
            display: flex;
            align-items: center;
            gap: 14px;

            padding: 13px 0;

            border-bottom: 1px solid rgba(255,255,255,.12);

            font-family: var(--body-font);
            font-size: 18px;
        }


        .featured-list li::before {
            content: "→";

            color: var(--training-lime);

            font-family: Arial, sans-serif;
            font-weight: bold;
        }


        /* =====================================================
           MOTIVATION BANNER
           ===================================================== */

        .training-quote {
            position: relative;

            padding: 120px 6vw;

            overflow: hidden;

            background: #050505;

            text-align: center;
        }


        .training-quote::before {
            content: "";

            position: absolute;

            width: 400px;
            height: 400px;

            left: 50%;
            top: 50%;

            transform: translate(-50%, -50%);

            border: 1px solid rgba(175,250,1,.2);
            border-radius: 50%;
        }


        .training-quote small {
            position: relative;
            z-index: 2;

            display: block;

            margin-bottom: 25px;

            font-family: var(--body-font);
            font-size: 15px;
            font-weight: 700;

            letter-spacing: .18em;
            text-transform: uppercase;

            color: var(--training-lime);
        }


        .training-quote h2 {
            position: relative;
            z-index: 2;

            max-width: 1100px;

            margin: auto;

            font-family: var(--heading-font);
            font-size: clamp(60px, 9vw, 150px);
            line-height: .86;

            font-style: italic;
            text-transform: uppercase;
        }


        .training-quote h2 span {
            color: var(--training-lime);
        }


        .training-quote p {
            position: relative;
            z-index: 2;

            max-width: 580px;

            margin: 30px auto 0;

            font-family: var(--body-font);
            font-size: 20px;
            line-height: 1.5;

            color: rgba(255,255,255,.6);
        }


        /* =====================================================
           FOOTER
           ===================================================== */

        .training-page .site-footer {
            background: linear-gradient(
                90deg,
                #0c0c0c,
                #3b3b3b
            );
        }


        /* =====================================================
           RESPONSIVE
           ===================================================== */

        @media (max-width: 1000px) {

            .training-hero {
                grid-template-columns: 1fr;

                min-height: auto;

                padding-top: 150px;
            }


            .training-hero-content {
                margin-bottom: 50px;
            }


            .training-hero-visual {
                min-height: 500px;
            }


            .training-stats {
                grid-template-columns: repeat(2, 1fr);
            }


            .training-stat:nth-child(2) {
                border-right: none;
            }


            .training-stat:nth-child(-n+2) {
                border-bottom: 1px solid rgba(255,255,255,.1);
            }


            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }


            .philosophy-header {
                grid-template-columns: 1fr;
            }


            .featured-training {
                grid-template-columns: 1fr;
            }


            .featured-training-image {
                min-height: 500px;
            }
        }


        @media (max-width: 700px) {

            .training-hero {
                padding: 130px 22px 70px;
            }


            .training-hero-title {
                font-size: clamp(68px, 19vw, 110px);
            }


            .training-hero-description {
                font-size: 18px;
            }


            .training-hero-visual {
                min-height: 420px;
            }


            .hero-image-frame {
                height: 480px;
                width: 100%;
            }


            .hero-lime-shape {
                width: 90px;
                height: 90px;
                right: -5px;
            }


            .training-stats {
                grid-template-columns: 1fr 1fr;
            }


            .training-stat {
                padding: 30px 12px;
            }


            .training-programs,
            .training-philosophy {
                padding: 75px 22px;
            }


            .training-program-grid {
                grid-template-columns: 1fr;
            }


            .training-program-grid {
                grid-template-columns: 1fr;
            }


            .training-program-card {
                min-height: 460px;
            }


            .training-program-content h3 {
                font-size: 45px;
            }


            .principles-grid {
                grid-template-columns: 1fr;
            }


            .principle {
                border-right: none;
                border-bottom: 1px solid rgba(0,0,0,.25);
            }


            .principle:last-child {
                border-bottom: none;
            }


            .featured-training-content {
                padding: 70px 25px;
            }


            .featured-training-image {
                min-height: 400px;
            }


            .training-quote {
                padding: 90px 22px;
            }


            .training-quote::before {
                width: 280px;
                height: 280px;
            }


            .training-page .main-nav {
                overflow-x: auto;
                max-width: 60vw;
            }


            .training-page .nav-link {
                font-size: 14px;
                padding: 10px 13px;
            }


            .training-page .logo {
                font-size: 1.8rem;
            }


            .training-page .header-actions {
                gap: 10px;
            }


            .training-page .header-actions svg {
                width: 18px;
                height: 18px;
            }
        }

    </style>

</head>


<body class="training-page">


<!-- =========================================================
     HEADER
     ========================================================= -->

<header class="site-header">

    <a
        href="index.php"
        class="logo"
        aria-label="TENSION Home"
    >
        <span class="logo-word">
            <span class="accent">T</span>ension
        </span>
    </a>


    <nav
        class="main-nav"
        aria-label="Main navigation"
    >

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

        <!-- Region -->



        <!-- Account -->
        <button
            type="button"
            aria-label="Account"
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
        </button>


        <!-- Cart -->
        <button
            type="button"
            aria-label="Cart"
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
        </button>

    </div>

</header>


<main>


<!-- =========================================================
     1. TRAINING HERO
     ========================================================= -->

<section class="training-hero">

    <div class="training-hero-content">

        <span class="training-eyebrow">
            TENSION TRAINING
        </span>


        <h1 class="training-hero-title">

            TRAIN<br>

            <span class="lime">
                HARDER.
            </span>

            <br>

            MOVE<br>

            <span class="lime">
                FORWARD.
            </span>

        </h1>


        <p class="training-hero-description">

            Training is more than a workout.
            It is the discipline to keep moving,
            keep improving, and keep pushing beyond
            yesterday's limits.

        </p>


        <div class="training-hero-buttons">

            <a
                href="#programs"
                class="training-btn training-btn-primary"
            >
                Explore Training
                <span>→</span>
            </a>


            <a
                href="#philosophy"
                class="training-btn training-btn-secondary"
            >
                Our Approach
            </a>

        </div>

    </div>


    <div class="training-hero-visual">

        <div class="hero-lime-shape"></div>


        <div class="hero-image-frame">

            <!--
                IMAGE SLOT
                Replace this image with your preferred training image.
            -->

            <img
                src="images/pic 1.jpg"
                alt="Athlete training with TENSION"
                class="hero-training-image"
            >


            <div class="hero-image-label">

                <strong>
                    No Excuses.
                </strong>

                <span>
                    Just progress.
                </span>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     2. TRAINING STATS
     ========================================================= -->

<section
    class="training-stats"
    aria-label="Training statistics"
>

    <?php foreach ($stats as $stat): ?>

        <div class="training-stat">

            <strong>
                <?= htmlspecialchars($stat['value']) ?>
            </strong>

            <span>
                <?= htmlspecialchars($stat['label']) ?>
            </span>

        </div>

    <?php endforeach; ?>

</section>


<!-- =========================================================
     3. TRAINING PROGRAMS
     ========================================================= -->

<section
    class="training-programs"
    id="programs"
>

    <div class="section-heading">

        <div class="section-heading-left">

            <span class="section-kicker">
                Find Your Focus
            </span>

            <h2 class="section-title">

                Train<br>

                <span class="lime">
                    With Purpose.
                </span>

            </h2>

        </div>


        <p class="section-intro">

            Whether you're building strength, improving
            athletic performance, increasing endurance,
            or learning how to recover properly, every
            session should have a purpose.

        </p>

    </div>


    <div class="training-program-grid">

        <?php foreach ($training_categories as $category): ?>

            <article class="training-program-card">

                <div class="training-program-card-image">

                    <img
                        src="<?= htmlspecialchars($category['image']) ?>"
                        alt="<?= htmlspecialchars($category['title']) ?> training"
                    >

                </div>


                <span class="program-number">

                    <?= htmlspecialchars($category['number']) ?>

                </span>


                <div class="training-program-content">

                    <h3>
                        <?= htmlspecialchars($category['title']) ?>
                    </h3>


                    <p>
                        <?= htmlspecialchars($category['description']) ?>
                    </p>


                    <a
                        href="#"
                        class="program-link"
                    >
                        Explore Program
                        <span>→</span>
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

</section>


<!-- =========================================================
     4. TRAINING PHILOSOPHY
     ========================================================= -->

<section
    class="training-philosophy"
    id="philosophy"
>

    <div class="philosophy-header">

        <h2 class="philosophy-title">

            The<br>
            Tension<br>
            Method.

        </h2>


        <p class="philosophy-description">

            Progress does not happen overnight.
            It comes from showing up, challenging
            yourself, and allowing your body enough
            time to recover and adapt.

            <br><br>

            <strong>
                Train with intention. Recover with purpose.
                Repeat.

            </strong>

        </p>

    </div>


    <div class="principles-grid">

        <?php foreach ($principles as $principle): ?>

            <article class="principle">

                <span class="principle-number">

                    <?= htmlspecialchars($principle['number']) ?>

                </span>


                <h3>

                    <?= htmlspecialchars($principle['title']) ?>

                </h3>


                <p>

                    <?= htmlspecialchars($principle['text']) ?>

                </p>

            </article>

        <?php endforeach; ?>

    </div>

</section>


<!-- =========================================================
     5. FEATURED TRAINING
     ========================================================= -->

<section class="featured-training">

    <!-- IMAGE SLOT -->

    <div
        class="featured-training-image"
        role="img"
        aria-label="Athlete training with TENSION"
    ></div>


    <div class="featured-training-content">

        <span class="section-kicker">
            Featured Training
        </span>


        <h2>

            Turn Effort<br>

            Into <span>Progress.</span>

        </h2>


        <p>

            Your best performance starts with a
            commitment to yourself. Build a routine
            that challenges your body while giving
            you the structure needed to keep improving.

        </p>


        <ul class="featured-list">

            <li>
                Build strength and stability
            </li>

            <li>
                Improve athletic performance
            </li>

            <li>
                Develop endurance and conditioning
            </li>

            <li>
                Prioritize mobility and recovery
            </li>

        </ul>


        <a
            href="#programs"
            class="training-btn training-btn-primary"
        >
            Start Training
            <span>→</span>
        </a>

    </div>

</section>


<!-- =========================================================
     6. MOTIVATION
     ========================================================= -->

<section class="training-quote">

    <small>
        The TENSION Mindset
    </small>


    <h2>

        Push Past<br>

        <span>Your Limits.</span>

    </h2>


    <p>

        The workout ends.
        The mindset doesn't.

        Keep moving.
        Keep improving.
        Keep living under TENSION.

    </p>

</section>


</main>


<!-- =========================================================
     NEWSLETTER
     ========================================================= -->

<section
    class="newsletter"
    aria-label="Newsletter signup"
>

    <div
        class="newsletter-visual"
        aria-hidden="true"
        style="
            background-image:
                url('images/7p.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        "
    ></div>


    <div class="newsletter-content">

        <h2>
            Insider Access
        </h2>


        <p>
            Catch every flavor drop, giveaway,
            training update, and headline-making news.
        </p>


        <form
            class="signup-form"
            action="#"
            method="post"
        >

            <label
                for="newsletter-email"
                class="sr-only"
            >
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

                <h4>
                    Company
                </h4>

                <a href="#">
                    Products
                </a>

                <a href="#">
                    Promotion
                </a>

                <a href="#">
                    Events
                </a>

            </div>


            <div>

                <h4>
                    Support
                </h4>

                <a href="#">
                    Find in store
                </a>

                <a href="#">
                    FAQs
                </a>

                <a href="#">
                    Contact us
                </a>

            </div>


            <div>

                <h4>
                    Explore
                </h4>

                <a href="training.php">
                    Training
                </a>

                <a href="#">
                    Lifestyle
                </a>

                <a href="news.php">
                    News
                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     FOOTER
     ========================================================= -->

<footer class="site-footer">

    <div class="footer-grid">


        <!-- POLICY -->

        <div>

            <h4>
                Policy
            </h4>

            <?php foreach ($footer_columns['Policy'] as $item): ?>

                <a href="#">
                    <?= htmlspecialchars($item) ?>
                </a>

            <?php endforeach; ?>

        </div>


        <!-- STORE -->

        <div>

            <h4>
                Our Store
            </h4>

            <?php foreach ($footer_columns['Our Store'] as $item): ?>

                <p class="line">

                    <?= htmlspecialchars($item) ?>

                </p>

            <?php endforeach; ?>

        </div>


        <!-- CUSTOMER SERVICE -->

        <div>

            <h4>
                Customer Service
            </h4>

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

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                        </svg>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>


        <!-- BRAND -->

        <div class="footer-brand">

            <a
                href="index.php"
                class="logo"
                aria-label="TENSION Home"
            >

                <span class="logo-word">

                    <span class="accent">
                        T
                    </span>ension

                </span>

            </a>

        </div>


    </div>


    <div class="footer-bottom">

        <span>

            &copy;
            <?= date('Y') ?>
            — TENSION Energy Drink Company LLC.
            All Rights Reserved.

        </span>


        <span>

            Do Not Sell or Share My Personal Information

        </span>

    </div>

</footer>


</body>

</html>