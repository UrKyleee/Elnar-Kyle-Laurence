<?php
session_start();

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php', 'active' => true],
    ['label' => 'About',     'href' => 'about.php'],
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

/*
|--------------------------------------------------------------------------
| Lifestyle Categories
|--------------------------------------------------------------------------
*/

$lifestyle_categories = [
    [
        'number' => '01',
        'title' => 'MOVE',
        'description' => 'Training, movement, active living and the energy that keeps you going.',
        'image' => 'images/Running.webp'
    ],

    [
        'number' => '02',
        'title' => 'STYLE',
        'description' => 'Everyday style, streetwear and the culture surrounding modern movement.',
        'image' => 'images/Under-Armour-Athlete-Robyn-Moodaly-2.jpg'
    ],

    [
        'number' => '03',
        'title' => 'CULTURE',
        'description' => 'People, music, creativity, events and the community behind TENSION.',
        'image' => 'images/morgan.jpg'
    ],

    [
        'number' => '04',
        'title' => 'WELLNESS',
        'description' => 'Better habits, balance, recovery and ways to feel your best every day.',
        'image' => 'images/pic 6.jpg'
    ],
];

/*
|--------------------------------------------------------------------------
| Lifestyle Stories
|--------------------------------------------------------------------------
*/

$lifestyle_stories = [
    [
        'category' => 'MOVE',
        'date' => 'SEPTEMBER 04, 2026',
        'title' => 'Fuel Your Every Move',
        'description' => 'Your lifestyle does not stop when your workout ends. Discover ways to keep your energy moving throughout the day.',
        'image' => 'images/8166e4537552596ae0d19f1c55911e6a.png',
        'featured' => true
    ],

    [
        'category' => 'STYLE',
        'date' => 'AUGUST 30, 2026',
        'title' => 'Style That Keeps Up',
        'description' => 'Discover the pieces, attitudes and everyday choices that define active street style.',
        'image' => 'images/Under-Armour-Athlete-Robyn-Moodaly-2.jpg',
        'featured' => false
    ],

    [
        'category' => 'WELLNESS',
        'date' => 'AUGUST 26, 2026',
        'title' => 'Energy Beyond the Gym',
        'description' => 'Movement, recovery and better routines can change how you approach every day.',
        'image' => 'images/pic 6.jpg',
        'featured' => false
    ],

    [
        'category' => 'CULTURE',
        'date' => 'AUGUST 22, 2026',
        'title' => 'Built Around Community',
        'description' => 'Meet the people and experiences helping shape the TENSION lifestyle.',
        'image' => 'images/morgan.jpg',
        'featured' => false
    ],

    [
        'category' => 'MOVE',
        'date' => 'AUGUST 18, 2026',
        'title' => 'Keep Moving Forward',
        'description' => 'Small progress adds up. Build a lifestyle that keeps you moving.',
        'image' => 'images/Running.webp',
        'featured' => false
    ],

    [
        'category' => 'STYLE',
        'date' => 'AUGUST 14, 2026',
        'title' => 'The Everyday Uniform',
        'description' => 'Comfort, confidence and performance come together in modern everyday wear.',
        'image' => 'images/pic 1.jpg',
        'featured' => false
    ],
];

/*
|--------------------------------------------------------------------------
| BRAND MARK
|--------------------------------------------------------------------------
*/

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
        <rect x="27" y="8" width="4" height="22"
              transform="rotate(18 27 8)"
              fill="currentColor"/>

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

    <title>TENSION — Lifestyle</title>

    <meta
        name="description"
        content="TENSION Lifestyle — movement, style, culture, wellness and the energy to live beyond the ordinary."
    >

    <!-- Existing TENSION font fallbacks -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet"
    >

    <!-- Existing website stylesheet -->
    <link rel="stylesheet" href="style.css">


    <!-- =====================================================
         LIFESTYLE PAGE STYLES
    ====================================================== -->

    <style>

        /* =====================================================
           BASE
        ====================================================== */

        .lifestyle-page {

            --life-black: #080808;
            --life-white: #ffffff;
            --life-lime: var(--lime);
            --life-gray: #eeeeea;
            --life-dark-gray: #202020;
            --life-muted: #777;

            background: var(--life-gray);
            color: var(--life-black);

            overflow-x: hidden;
        }


        /* =====================================================
           HERO
        ====================================================== */

        .lifestyle-hero {

            position: relative;

            min-height: 820px;

            overflow: hidden;

            display: flex;

            align-items: flex-end;

            background:

                linear-gradient(
                    115deg,
                    #050505 0%,
                    #101010 32%,
                    #303030 48%,
                    #729f16 65%,
                    var(--life-lime) 100%
                );
        }


        .lifestyle-hero::before {

            content: "";

            position: absolute;

            inset: 0;

            background:

                repeating-linear-gradient(
                    115deg,
                    rgba(255,255,255,.045) 0px,
                    rgba(255,255,255,.045) 2px,
                    transparent 2px,
                    transparent 45px
                );

            pointer-events: none;
        }


        .lifestyle-hero::after {

            content: "LIFESTYLE";

            position: absolute;

            right: -40px;

            bottom: -65px;

            font-family: var(--heading-font);

            font-size: clamp(140px, 23vw, 380px);

            line-height: .8;

            font-style: italic;

            color: rgba(255,255,255,.055);

            pointer-events: none;
        }


        .lifestyle-hero-inner {

            position: relative;

            z-index: 5;

            width: 100%;

            max-width: 1500px;

            margin: 0 auto;

            padding: 180px var(--page-padding) 105px;
        }


        .lifestyle-eyebrow {

            display: flex;

            align-items: center;

            gap: 13px;

            margin-bottom: 22px;

            color: rgba(255,255,255,.82);

            font-family: var(--body-font);

            font-size: 16px;

            font-weight: 700;

            font-style: italic;

            text-transform: uppercase;

            letter-spacing: .18em;
        }


        .lifestyle-eyebrow::before {

            content: "";

            width: 42px;

            height: 3px;

            background: var(--life-lime);
        }


        .lifestyle-hero-title {

            max-width: 1050px;

            margin: 0;

            font-family: var(--heading-font);

            font-size: clamp(85px, 12vw, 190px);

            line-height: .82;

            font-style: italic;

            text-transform: uppercase;

            letter-spacing: -.025em;

            color: #fff;
        }


        .lifestyle-hero-title .accent {

            color: var(--life-lime);
        }


        .lifestyle-hero-description {

            max-width: 660px;

            margin: 35px 0 0;

            color: rgba(255,255,255,.76);

            font-family: var(--body-font);

            font-size: 21px;

            line-height: 1.55;

            font-weight: 600;
        }


        .lifestyle-hero-buttons {

            display: flex;

            flex-wrap: wrap;

            gap: 12px;

            margin-top: 35px;
        }


        .lifestyle-btn {

            display: inline-flex;

            align-items: center;

            justify-content: space-between;

            gap: 35px;

            min-width: 180px;

            padding: 15px 20px;

            border-radius: 999px;

            font-family: var(--body-font);

            font-size: 16px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .03em;

            transition:

                transform .2s ease,
                background .2s ease,
                color .2s ease;
        }


        .lifestyle-btn-primary {

            background: var(--life-lime);

            color: #000;
        }


        .lifestyle-btn-primary:hover {

            transform: translateY(-3px);

            background: #fff;
        }


        .lifestyle-btn-outline {

            border: 1px solid rgba(255,255,255,.55);

            color: #fff;

            background: rgba(0,0,0,.12);
        }


        .lifestyle-btn-outline:hover {

            transform: translateY(-3px);

            background: #fff;

            color: #000;
        }


        .lifestyle-hero-bottom {

            position: absolute;

            z-index: 6;

            left: var(--page-padding);

            right: var(--page-padding);

            bottom: 35px;

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            border-top: 1px solid rgba(255,255,255,.25);

            padding-top: 20px;
        }


        .hero-stat {

            color: rgba(255,255,255,.72);

            font-family: var(--body-font);

            font-size: 14px;

            font-weight: 600;

            text-transform: uppercase;

            letter-spacing: .06em;
        }


        .hero-stat strong {

            display: block;

            margin-bottom: 4px;

            color: #fff;

            font-family: var(--heading-font);

            font-size: 28px;

            font-style: italic;
        }


        /* =====================================================
           INTRO
        ====================================================== */

        .lifestyle-intro {

            background: #fff;

            padding: 105px var(--page-padding);
        }


        .lifestyle-intro-grid {

            max-width: 1500px;

            margin: 0 auto;

            display: grid;

            grid-template-columns: .9fr 1.1fr;

            gap: 100px;

            align-items: center;
        }


        .lifestyle-section-label {

            margin-bottom: 20px;

            color: #777;

            font-family: var(--body-font);

            font-size: 15px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .15em;
        }


        .lifestyle-intro-title {

            margin: 0;

            font-family: var(--heading-font);

            font-size: clamp(65px, 7vw, 115px);

            line-height: .85;

            font-style: italic;

            text-transform: uppercase;
        }


        .lifestyle-intro-title span {

            color: var(--life-lime);
        }


        .lifestyle-intro-copy {

            max-width: 720px;
        }


        .lifestyle-intro-copy .lead {

            margin: 0 0 25px;

            font-family: var(--body-font);

            font-size: 25px;

            line-height: 1.45;

            font-weight: 600;
        }


        .lifestyle-intro-copy p {

            margin: 0;

            color: #5d5d5d;

            font-family: var(--body-font);

            font-size: 18px;

            line-height: 1.65;
        }


        /* =====================================================
           CATEGORY SECTION
        ====================================================== */

        .lifestyle-categories {

            padding: 110px var(--page-padding);

            background: var(--life-gray);
        }


        .lifestyle-section-heading {

            max-width: 1500px;

            margin: 0 auto 50px;

            display: flex;

            justify-content: space-between;

            align-items: end;

            gap: 30px;
        }


        .lifestyle-section-heading h2 {

            margin: 0;

            font-family: var(--heading-font);

            font-size: clamp(60px, 7vw, 110px);

            line-height: .85;

            font-style: italic;

            text-transform: uppercase;
        }


        .lifestyle-section-heading h2 span {

            color: var(--life-lime);
        }


        .lifestyle-section-heading p {

            max-width: 420px;

            margin: 0;

            color: #666;

            font-family: var(--body-font);

            font-size: 18px;

            line-height: 1.5;

            font-weight: 600;
        }


        .lifestyle-category-grid {

            max-width: 1500px;

            margin: 0 auto;

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 15px;
        }


        .lifestyle-category {

            position: relative;

            min-height: 430px;

            overflow: hidden;

            background: #111;

            color: #fff;

            border-radius: 4px;

            transition: transform .3s ease;
        }


        .lifestyle-category:hover {

            transform: translateY(-8px);
        }


        .lifestyle-category-image {

            position: absolute;

            inset: 0;

            background-size: cover;

            background-position: center;

            transition: transform .5s ease;
        }


        .lifestyle-category:hover .lifestyle-category-image {

            transform: scale(1.06);
        }


        .lifestyle-category::after {

            content: "";

            position: absolute;

            inset: 0;

            background:

                linear-gradient(
                    to top,
                    rgba(0,0,0,.9),
                    rgba(0,0,0,.15) 65%,
                    rgba(0,0,0,.05)
                );
        }


        .lifestyle-category-content {

            position: absolute;

            z-index: 3;

            left: 27px;

            right: 27px;

            bottom: 28px;
        }


        .lifestyle-category-number {

            display: block;

            margin-bottom: 45px;

            color: var(--life-lime);

            font-family: var(--body-font);

            font-size: 13px;

            font-weight: 700;

            letter-spacing: .15em;
        }


        .lifestyle-category h3 {

            margin: 0 0 9px;

            font-family: var(--heading-font);

            font-size: 43px;

            line-height: .9;

            font-style: italic;
        }


        .lifestyle-category p {

            margin: 0;

            color: rgba(255,255,255,.76);

            font-family: var(--body-font);

            font-size: 15px;

            line-height: 1.45;
        }


        /* =====================================================
           FEATURED STORY
        ====================================================== */

        .lifestyle-featured {

            padding: 120px var(--page-padding);

            background: #fff;
        }


        .lifestyle-featured-grid {

            max-width: 1500px;

            margin: 0 auto;

            display: grid;

            grid-template-columns: 1.4fr .6fr;

            gap: 28px;
        }


        .lifestyle-featured-image {

            min-height: 650px;

            position: relative;

            overflow: hidden;

            background: #222;

            background-size: cover;

            background-position: center;
        }


        .lifestyle-featured-image::after {

            content: "";

            position: absolute;

            inset: 0;

            background: linear-gradient(
                to top,
                rgba(0,0,0,.75),
                transparent 55%
            );
        }


        .featured-badge {

            position: absolute;

            z-index: 3;

            top: 25px;

            left: 25px;

            padding: 9px 14px;

            background: var(--life-lime);

            color: #000;

            border-radius: 999px;

            font-family: var(--body-font);

            font-size: 13px;

            font-weight: 800;

            letter-spacing: .08em;

            text-transform: uppercase;
        }


        .featured-overlay-content {

            position: absolute;

            z-index: 3;

            left: 35px;

            right: 35px;

            bottom: 35px;

            color: #fff;
        }


        .featured-overlay-content .category {

            color: var(--life-lime);

            font-family: var(--body-font);

            font-size: 14px;

            font-weight: 800;

            letter-spacing: .13em;

            text-transform: uppercase;
        }


        .featured-overlay-content h3 {

            max-width: 800px;

            margin: 12px 0 10px;

            font-family: var(--heading-font);

            font-size: clamp(48px, 5vw, 85px);

            line-height: .88;

            font-style: italic;

            text-transform: uppercase;
        }


        .featured-overlay-content p {

            max-width: 650px;

            margin: 0;

            color: rgba(255,255,255,.78);

            font-family: var(--body-font);

            font-size: 17px;

            line-height: 1.5;
        }


        .lifestyle-side-stories {

            display: grid;

            grid-template-rows: 1fr 1fr;

            gap: 28px;
        }


        .lifestyle-side-story {

            display: grid;

            grid-template-columns: 1fr;

            background: var(--life-gray);

            overflow: hidden;
        }


        .lifestyle-side-image {

            min-height: 250px;

            background-size: cover;

            background-position: center;
        }


        .lifestyle-side-content {

            padding: 24px;
        }


        .lifestyle-side-content .meta {

            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 13px;

            color: #777;

            font-family: var(--body-font);

            font-size: 12px;

            font-weight: 700;

            letter-spacing: .08em;

            text-transform: uppercase;
        }


        .lifestyle-side-content h3 {

            margin: 0;

            font-family: var(--heading-font);

            font-size: 31px;

            line-height: .95;

            font-style: italic;

            text-transform: uppercase;
        }


        .lifestyle-side-content a {

            display: inline-flex;

            margin-top: 18px;

            color: #000;

            font-family: var(--body-font);

            font-size: 14px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .06em;
        }


        .lifestyle-side-content a:hover {

            color: #6f9b18;
        }


        /* =====================================================
           MANIFESTO
        ====================================================== */

        .lifestyle-manifesto {

            position: relative;

            overflow: hidden;

            background: var(--life-lime);

            padding: 115px var(--page-padding);
        }


        .lifestyle-manifesto::before {

            content: "TENSION";

            position: absolute;

            left: -25px;

            bottom: -75px;

            font-family: var(--heading-font);

            font-size: clamp(150px, 28vw, 430px);

            line-height: .7;

            font-style: italic;

            color: rgba(0,0,0,.08);

            pointer-events: none;
        }


        .manifesto-inner {

            position: relative;

            z-index: 2;

            max-width: 1300px;

            margin: 0 auto;

            text-align: center;
        }


        .manifesto-label {

            display: inline-block;

            margin-bottom: 30px;

            font-family: var(--body-font);

            font-size: 14px;

            font-weight: 800;

            letter-spacing: .18em;

            text-transform: uppercase;
        }


        .manifesto-inner blockquote {

            margin: 0;

            font-family: var(--heading-font);

            font-size: clamp(48px, 7vw, 105px);

            line-height: .9;

            font-style: italic;

            text-transform: uppercase;
        }


        .manifesto-inner blockquote span {

            color: #fff;
        }


        .manifesto-caption {

            max-width: 560px;

            margin: 35px auto 0;

            font-family: var(--body-font);

            font-size: 18px;

            line-height: 1.55;

            font-weight: 600;
        }


        /* =====================================================
           LATEST STORIES
        ====================================================== */

        .lifestyle-latest {

            padding: 120px var(--page-padding);

            background: var(--life-gray);
        }


        .lifestyle-story-grid {

            max-width: 1500px;

            margin: 0 auto;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }


        .lifestyle-story-card {

            background: #fff;

            border-radius: 4px;

            overflow: hidden;

            transition:

                transform .25s ease,
                box-shadow .25s ease;
        }


        .lifestyle-story-card:hover {

            transform: translateY(-7px);

            box-shadow: 0 20px 45px rgba(0,0,0,.11);
        }


        .lifestyle-story-image {

            position: relative;

            aspect-ratio: 16 / 11;

            background-size: cover;

            background-position: center;
        }


        .story-category {

            position: absolute;

            top: 18px;

            left: 18px;

            padding: 8px 11px;

            background: var(--life-lime);

            color: #000;

            border-radius: 999px;

            font-family: var(--body-font);

            font-size: 11px;

            font-weight: 800;

            letter-spacing: .08em;

            text-transform: uppercase;
        }


        .lifestyle-story-content {

            padding: 25px;
        }


        .lifestyle-story-meta {

            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 13px;

            color: #777;

            font-family: var(--body-font);

            font-size: 11px;

            font-weight: 700;

            letter-spacing: .08em;

            text-transform: uppercase;
        }


        .lifestyle-story-content h3 {

            margin: 0 0 13px;

            font-family: var(--heading-font);

            font-size: 35px;

            line-height: .94;

            font-style: italic;

            text-transform: uppercase;
        }


        .lifestyle-story-content p {

            margin: 0;

            color: #626262;

            font-family: var(--body-font);

            font-size: 16px;

            line-height: 1.5;
        }


        .lifestyle-read {

            display: inline-flex;

            margin-top: 22px;

            padding-bottom: 4px;

            border-bottom: 1px solid #000;

            font-family: var(--body-font);

            font-size: 13px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .07em;
        }


        /* =====================================================
           LIFESTYLE CTA
        ====================================================== */

        .lifestyle-cta {

            position: relative;

            overflow: hidden;

            max-width: 1500px;

            margin: 0 auto;

            padding: 70px;

            background: #000;

            color: #fff;

            display: grid;

            grid-template-columns: 1fr auto;

            align-items: center;

            gap: 40px;
        }


        .lifestyle-cta::after {

            content: "MOVE";

            position: absolute;

            right: -20px;

            bottom: -70px;

            font-family: var(--heading-font);

            font-size: 240px;

            line-height: .8;

            font-style: italic;

            color: rgba(255,255,255,.035);

            pointer-events: none;
        }


        .lifestyle-cta h3 {

            position: relative;

            z-index: 2;

            margin: 0;

            font-family: var(--heading-font);

            font-size: clamp(45px, 5vw, 78px);

            line-height: .88;

            font-style: italic;

            text-transform: uppercase;
        }


        .lifestyle-cta h3 span {

            color: var(--life-lime);
        }


        .lifestyle-cta p {

            position: relative;

            z-index: 2;

            max-width: 700px;

            margin: 16px 0 0;

            color: rgba(255,255,255,.7);

            font-family: var(--body-font);

            font-size: 17px;

            line-height: 1.55;
        }


        .lifestyle-cta .lifestyle-btn {

            position: relative;

            z-index: 4;

            min-width: 200px;
        }


        /* =====================================================
           NEWSLETTER
        ====================================================== */

        .lifestyle-page .newsletter {

            margin: 0;
        }


        /* =====================================================
           RESPONSIVE — 1100PX
        ====================================================== */

        @media (max-width: 1100px) {

            .lifestyle-category-grid {

                grid-template-columns: repeat(2, 1fr);
            }


            .lifestyle-featured-grid {

                grid-template-columns: 1fr;
            }


            .lifestyle-featured-image {

                min-height: 620px;
            }


            .lifestyle-side-stories {

                grid-template-columns: 1fr 1fr;

                grid-template-rows: auto;
            }


            .lifestyle-story-grid {

                grid-template-columns: repeat(2, 1fr);
            }


            .lifestyle-hero-bottom {

                grid-template-columns: repeat(2, 1fr);

                row-gap: 18px;
            }

        }


        /* =====================================================
           RESPONSIVE — 800PX
        ====================================================== */

        @media (max-width: 800px) {

            .lifestyle-hero {

                min-height: 760px;
            }


            .lifestyle-hero-inner {

                padding:

                    150px
                    22px
                    150px;
            }


            .lifestyle-hero-title {

                font-size: clamp(72px, 17vw, 130px);
            }


            .lifestyle-hero-description {

                font-size: 18px;
            }


            .lifestyle-hero-bottom {

                left: 22px;

                right: 22px;

                bottom: 28px;
            }


            .lifestyle-intro {

                padding:

                    80px
                    22px;
            }


            .lifestyle-intro-grid {

                grid-template-columns: 1fr;

                gap: 45px;
            }


            .lifestyle-categories,
            .lifestyle-featured,
            .lifestyle-latest {

                padding:

                    80px
                    22px;
            }


            .lifestyle-section-heading {

                display: block;
            }


            .lifestyle-section-heading p {

                margin-top: 20px;
            }


            .lifestyle-category-grid {

                grid-template-columns: 1fr;
            }


            .lifestyle-category {

                min-height: 390px;
            }


            .lifestyle-side-stories {

                grid-template-columns: 1fr;
            }


            .lifestyle-story-grid {

                grid-template-columns: 1fr;
            }


            .lifestyle-cta {

                margin: 0 22px;

                padding: 50px 30px;

                grid-template-columns: 1fr;
            }


            .lifestyle-manifesto {

                padding: 90px 22px;
            }

        }


        /* =====================================================
           RESPONSIVE — 500PX
        ====================================================== */

        @media (max-width: 500px) {

            .lifestyle-hero-title {

                font-size: 70px;

                letter-spacing: -.02em;
            }


            .lifestyle-hero-buttons {

                display: grid;

                grid-template-columns: 1fr;
            }


            .lifestyle-btn {

                width: 100%;
            }


            .lifestyle-hero-bottom {

                grid-template-columns: 1fr 1fr;
            }


            .hero-stat {

                font-size: 11px;
            }


            .hero-stat strong {

                font-size: 23px;
            }


            .lifestyle-intro-title {

                font-size: 64px;
            }


            .lifestyle-section-heading h2 {

                font-size: 65px;
            }


            .lifestyle-featured-image {

                min-height: 530px;
            }


            .featured-overlay-content {

                left: 23px;

                right: 23px;

                bottom: 25px;
            }


            .featured-overlay-content h3 {

                font-size: 48px;
            }


            .lifestyle-side-image {

                min-height: 220px;
            }


            .lifestyle-cta h3 {

                font-size: 48px;
            }

        }

    </style>

</head>


<body class="lifestyle-page">

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

<!-- =========================================================
     HERO
========================================================= -->

<section class="lifestyle-hero">

    <div class="lifestyle-hero-inner">

        <span class="lifestyle-eyebrow">
            Live Under Tension
        </span>


        <h1 class="lifestyle-hero-title">

            Live<br>

            <span class="accent">
                Different.
            </span>

        </h1>


        <p class="lifestyle-hero-description">

            The energy doesn't stop when the workout does.
            Explore movement, style, culture and wellness —
            the things that make life worth moving through.

        </p>


        <div class="lifestyle-hero-buttons">

            <a
                href="#lifestyle-explore"
                class="lifestyle-btn lifestyle-btn-primary"
            >

                Explore Lifestyle

                <span>→</span>

            </a>


            <a
                href="#featured-story"
                class="lifestyle-btn lifestyle-btn-outline"
            >

                Featured Story

                <span>↓</span>

            </a>

        </div>

    </div>


    <div class="lifestyle-hero-bottom">

        <div class="hero-stat">

            <strong>01</strong>

            Move

        </div>


        <div class="hero-stat">

            <strong>02</strong>

            Style

        </div>


        <div class="hero-stat">

            <strong>03</strong>

            Culture

        </div>


        <div class="hero-stat">

            <strong>04</strong>

            Wellness

        </div>

    </div>

</section>



<!-- =========================================================
     INTRODUCTION
========================================================= -->

<section class="lifestyle-intro">

    <div class="lifestyle-intro-grid">

        <div>

            <div class="lifestyle-section-label">

                01 / THE LIFESTYLE

            </div>


            <h2 class="lifestyle-intro-title">

                More<br>

                Than<br>

                <span>Energy.</span>

            </h2>

        </div>


        <div class="lifestyle-intro-copy">

            <p class="lead">

                TENSION is more than what you drink.
                It's how you choose to move through life.

            </p>


            <p>

                From early morning training sessions to
                late-night conversations, weekend adventures
                and everyday moments — TENSION belongs wherever
                energy, ambition and individuality come together.

                <br><br>

                Lifestyle is about finding your own rhythm,
                staying curious and refusing to settle for
                ordinary.

            </p>

        </div>

    </div>

</section>



<!-- =========================================================
     CATEGORY SECTION
========================================================= -->

<section
    class="lifestyle-categories"
    id="lifestyle-explore"
>

    <div class="lifestyle-section-heading">

        <div>

            <div class="lifestyle-section-label">

                DISCOVER

            </div>


            <h2>

                Your<br>

                <span>World.</span>

            </h2>

        </div>


        <p>

            Explore the different sides of the TENSION lifestyle —
            from movement and performance to style, culture and
            everyday wellness.

        </p>

    </div>


    <div class="lifestyle-category-grid">

        <?php foreach ($lifestyle_categories as $category): ?>

            <a
                href="#latest-stories"
                class="lifestyle-category"
            >

                <div
                    class="lifestyle-category-image"
                    style="
                        background-image:
                        linear-gradient(
                            rgba(0,0,0,.05),
                            rgba(0,0,0,.2)
                        ),
                        url('<?= htmlspecialchars($category['image']) ?>');
                    "
                ></div>


                <div class="lifestyle-category-content">

                    <span class="lifestyle-category-number">

                        <?= htmlspecialchars($category['number']) ?>

                    </span>


                    <h3>

                        <?= htmlspecialchars($category['title']) ?>

                    </h3>


                    <p>

                        <?= htmlspecialchars($category['description']) ?>

                    </p>

                </div>

            </a>

        <?php endforeach; ?>

    </div>

</section>



<!-- =========================================================
     FEATURED STORY
========================================================= -->

<section
    class="lifestyle-featured"
    id="featured-story"
>

    <div class="lifestyle-section-heading">

        <div>

            <div class="lifestyle-section-label">

                02 / FEATURED

            </div>


            <h2>

                Life In<br>

                <span>Motion.</span>

            </h2>

        </div>


        <p>

            Stories from people, places and experiences
            that embody the TENSION mindset.

        </p>

    </div>


    <div class="lifestyle-featured-grid">


        <!-- MAIN FEATURE -->

        <?php foreach ($lifestyle_stories as $story): ?>

            <?php if ($story['featured']): ?>

                <article>

                    <div
                        class="lifestyle-featured-image"
                        style="
                            background-image:
                            linear-gradient(
                                rgba(0,0,0,.03),
                                rgba(0,0,0,.1)
                            ),
                            url('<?= htmlspecialchars($story['image']) ?>');
                        "
                    >

                        <span class="featured-badge">

                            Featured Story

                        </span>


                        <div class="featured-overlay-content">

                            <span class="category">

                                <?= htmlspecialchars($story['category']) ?>

                            </span>


                            <h3>

                                <?= htmlspecialchars($story['title']) ?>

                            </h3>


                            <p>

                                <?= htmlspecialchars($story['description']) ?>

                            </p>

                        </div>

                    </div>

                </article>

            <?php endif; ?>

        <?php endforeach; ?>


        <!-- SIDE STORIES -->

        <div class="lifestyle-side-stories">

            <?php
            $sideCounter = 0;

            foreach ($lifestyle_stories as $story):

                if ($story['featured']) {
                    continue;
                }

                if ($sideCounter >= 2) {
                    break;
                }

                $sideCounter++;
            ?>

                <article class="lifestyle-side-story">

                    <div
                        class="lifestyle-side-image"
                        style="
                            background-image:
                            url('<?= htmlspecialchars($story['image']) ?>');
                        "
                    ></div>


                    <div class="lifestyle-side-content">

                        <div class="meta">

                            <span>
                                <?= htmlspecialchars($story['category']) ?>
                            </span>

                            <span>
                                <?= htmlspecialchars($story['date']) ?>
                            </span>

                        </div>


                        <h3>

                            <?= htmlspecialchars($story['title']) ?>

                        </h3>


                        <a href="#latest-stories">

                            Read Story →

                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>



<!-- =========================================================
     MANIFESTO
========================================================= -->

<section class="lifestyle-manifesto">

    <div class="manifesto-inner">

        <div class="manifesto-label">

            THE TENSION MINDSET

        </div>


        <blockquote>

            Don't wait<br>

            for life<br>

            to <span>move.</span>

        </blockquote>


        <p class="manifesto-caption">

            Create the momentum. Chase the experience.
            Find your people. Push beyond the expected.
            Make every moment count.

        </p>

    </div>

</section>



<!-- =========================================================
     LATEST STORIES
========================================================= -->

<section
    class="lifestyle-latest"
    id="latest-stories"
>

    <div class="lifestyle-section-heading">

        <div>

            <div class="lifestyle-section-label">

                03 / THE JOURNAL

            </div>


            <h2>

                Latest<br>

                <span>Stories.</span>

            </h2>

        </div>


        <p>

            Fresh ideas, stories and inspiration for
            living life with more energy.

        </p>

    </div>


    <div class="lifestyle-story-grid">

        <?php
        $storyCounter = 0;

        foreach ($lifestyle_stories as $story):

            if ($story['featured']) {
                continue;
            }

            if ($storyCounter >= 6) {
                break;
            }

            $storyCounter++;
        ?>

            <article class="lifestyle-story-card">


                <div
                    class="lifestyle-story-image"
                    style="
                        background-image:
                        url('<?= htmlspecialchars($story['image']) ?>');
                    "
                >

                    <span class="story-category">

                        <?= htmlspecialchars($story['category']) ?>

                    </span>

                </div>


                <div class="lifestyle-story-content">

                    <div class="lifestyle-story-meta">

                        <span>

                            <?= htmlspecialchars($story['category']) ?>

                        </span>

                        <span>

                            <?= htmlspecialchars($story['date']) ?>

                        </span>

                    </div>


                    <h3>

                        <?= htmlspecialchars($story['title']) ?>

                    </h3>


                    <p>

                        <?= htmlspecialchars($story['description']) ?>

                    </p>


                    <a
                        href="#"
                        class="lifestyle-read"
                    >

                        Read Article →

                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

</section>



<!-- =========================================================
     CTA
========================================================= -->

<section
    style="
        background:#eeeeea;
        padding:0 var(--page-padding) 110px;
    "
>

    <div class="lifestyle-cta">

        <div>

            <h3>

                Keep<br>

                <span>Moving.</span>

            </h3>


            <p>

                Discover the products, stories and experiences
                that make up the world of TENSION.

            </p>

        </div>


        <a
            href="shop.php"
            class="lifestyle-btn lifestyle-btn-primary"
        >

            Shop TENSION

            <span>→</span>

        </a>

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