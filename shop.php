<?php
session_start();

// Calculate total cart items for badge
$cart_count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += (int)($item['qty'] ?? 0);
    }
}

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php', 'active' => true],
    ['label' => 'News',      'href' => '#'],
    ['label' => 'Training',  'href' => '#'],
    ['label' => 'Lifestyle', 'href' => '#'],
    ['label' => 'About',     'href' => '#'],
];

$footer_columns = [
    'Policy'            => ['Shipping & Returns', 'Store Policy', 'Payment Methods', 'Cookies Policy', 'Terms of Use'],
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

/**
 * ==========================================================
 * SHOP CATALOG
 * ==========================================================
 */
$shop_products = [
    [
        'id'      => 'grapes-single',
        'name'    => 'Tension Grapes',
        'size'    => 'Single Can · 16oz',
        'flavor'  => 'grapes',
        'flavor_label' => 'Grapes',
        'color'   => '#6B4FA0',
        'image'   => 'images/tension-double-purple.png',
        'desc'    => 'Deep, bold, and intensely fruity, with a rich grape flavor and a smooth, refreshing finish.',
        'price'   => 3.50,
        'compare' => null,
        'badge'   => null,
    ],
    [
        'id'      => 'grapes-6pack',
        'name'    => 'Tension Grapes',
        'size'    => '6-Pack · 16oz cans',
        'flavor'  => 'grapes',
        'flavor_label' => 'Grapes',
        'color'   => '#6B4FA0',
        'image'   => 'images/tension-double-purple.png',
        'desc'    => 'Deep, bold, and intensely fruity, with a rich grape flavor and a smooth, refreshing finish.',
        'price'   => 19.99,
        'compare' => 21.00,
        'badge'   => 'Save 5%',
    ],
    [
        'id'      => 'grapes-12pack',
        'name'    => 'Tension Grapes',
        'size'    => '12-Pack Case · 16oz cans',
        'flavor'  => 'grapes',
        'flavor_label' => 'Grapes',
        'color'   => '#6B4FA0',
        'image'   => 'images/tension-double-purple.png',
        'desc'    => 'Deep, bold, and intensely fruity, with a rich grape flavor and a smooth, refreshing finish.',
        'price'   => 36.99,
        'compare' => 42.00,
        'badge'   => 'Save 12%',
    ],
    [
        'id'      => 'apple-single',
        'name'    => 'Tension Apple',
        'size'    => 'Single Can · 16oz',
        'flavor'  => 'apple',
        'flavor_label' => 'Apple',
        'color'   => '#A8433C',
        'image'   => 'images/tension-double-red.png',
        'desc'    => 'Crisp, bright, and naturally refreshing, with a clean apple flavor and a sharp burst of freshness.',
        'price'   => 3.50,
        'compare' => null,
        'badge'   => null,
    ],
    [
        'id'      => 'apple-6pack',
        'name'    => 'Tension Apple',
        'size'    => '6-Pack · 16oz cans',
        'flavor'  => 'apple',
        'flavor_label' => 'Apple',
        'color'   => '#A8433C',
        'image'   => 'images/tension-double-red.png',
        'desc'    => 'Crisp, bright, and naturally refreshing, with a clean apple flavor and a sharp burst of freshness.',
        'price'   => 19.99,
        'compare' => 21.00,
        'badge'   => 'Save 5%',
    ],
    [
        'id'      => 'apple-12pack',
        'name'    => 'Tension Apple',
        'size'    => '12-Pack Case · 16oz cans',
        'flavor'  => 'apple',
        'flavor_label' => 'Apple',
        'color'   => '#A8433C',
        'image'   => 'images/tension-double-red.png',
        'desc'    => 'Crisp, bright, and naturally refreshing, with a clean apple flavor and a sharp burst of freshness.',
        'price'   => 36.99,
        'compare' => 42.00,
        'badge'   => 'Save 12%',
    ],
    [
        'id'      => 'lime-single',
        'name'    => 'Tension Lime',
        'size'    => 'Single Can · 16oz',
        'flavor'  => 'lime',
        'flavor_label' => 'Lime',
        'color'   => '#8BC53F',
        'image'   => 'images/tension-double-lime.png',
        'desc'    => 'Zesty and refreshing, with a vibrant lime kick that cuts through with a sharp citrus taste.',
        'price'   => 3.50,
        'compare' => null,
        'badge'   => 'Best Seller',
    ],
    [
        'id'      => 'lime-6pack',
        'name'    => 'Tension Lime',
        'size'    => '6-Pack · 16oz cans',
        'flavor'  => 'lime',
        'flavor_label' => 'Lime',
        'color'   => '#8BC53F',
        'image'   => 'images/tension-double-lime.png',
        'desc'    => 'Zesty and refreshing, with a vibrant lime kick that cuts through with a sharp citrus taste.',
        'price'   => 19.99,
        'compare' => 21.00,
        'badge'   => 'Save 5%',
    ],
    [
        'id'      => 'lime-12pack',
        'name'    => 'Tension Lime',
        'size'    => '12-Pack Case · 16oz cans',
        'flavor'  => 'lime',
        'flavor_label' => 'Lime',
        'color'   => '#8BC53F',
        'image'   => 'images/tension-double-lime.png',
        'desc'    => 'Zesty and refreshing, with a vibrant lime kick that cuts through with a sharp citrus taste.',
        'price'   => 36.99,
        'compare' => 42.00,
        'badge'   => 'Save 12%',
    ],
    [
        'id'      => 'variety-12pack',
        'name'    => 'Tension Variety Pack',
        'size'    => '12-Pack Case · 4 of each flavor',
        'flavor'  => 'variety',
        'flavor_label' => 'Variety',
        'color'   => '#AFFA01',
        'image'   => 'images/tension-cans-collection.png',
        'desc'    => "Can't decide? Four Grapes, four Apple, four Lime — every flavor in one case.",
        'price'   => 38.99,
        'compare' => 43.00,
        'badge'   => 'Fan Favorite',
    ],
];

$flavor_filters = [
    'all'     => 'All',
    'grapes'  => 'Grapes',
    'apple'   => 'Apple',
    'lime'    => 'Lime',
    'variety' => 'Variety',
];

$product_count = count($shop_products);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — Shop</title>
    <meta name="description" content="Shop every TENSION flavor — Grapes, Apple, and Lime — as singles, 6-packs, or 12-pack cases.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <style>
:root {
    --lime:  #AFFA01;
    --black: #000000;
    --white: #FFFFFF;

    --heading-font: "Ultimate", "Anton", "Arial Black", Impact, sans-serif;
    --body-font: "Garamond", "EB Garamond", "Times New Roman", serif;

    --page-padding: 24px;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html,
body {
    min-height: 100%;
}

body {
    background: var(--black);
    color: var(--white);
    font-family: var(--body-font);
    overflow-x: hidden;
}

img { max-width: 100%; display: block; }

a {
    color: inherit;
    text-decoration: none;
}

/* =========================
   HEADER
   ========================= */

.site-header {
    position: absolute;
    z-index: 10;
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 26px var(--page-padding);
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--white);
    text-decoration: none;
    font-family: var(--heading-font);
    font-size: 2.5rem;
    letter-spacing: 0.02em;
    white-space: nowrap;
    flex-shrink: 0;
}

.logo-mark { width: 28px; height: 28px; flex-shrink: 0; }
.logo .accent { color: var(--lime); }

.main-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
    background: rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(6px);
    border-radius: 999px;
    padding: 6px;
}

.nav-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 18px;
    line-height: 1;
    letter-spacing: 0.02em;
    padding: 12px 18px;
    border-radius: 999px;
    white-space: nowrap;
    transition: background .18s ease, color .18s ease;
}

.nav-link:hover { color: var(--white); }

.nav-link.active {
    background: rgba(255, 255, 255, 0.9);
    color: var(--black);
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
    color: var(--white);
    flex-shrink: 0;
}

.header-actions button,
.header-actions .header-icon-link {
    background: none;
    border: none;
    padding: 0;
    color: inherit;
    cursor: pointer;
    display: flex;
    position: relative;
    align-items: center;
    justify-content: center;
}

.header-actions svg { width: 22px; height: 22px; }

.cart-button { position: relative; }

.cart-count {
    position: absolute;
    top: -8px;
    right: -10px;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    background: var(--lime);
    color: var(--black);
    border-radius: 999px;
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 11px;
    line-height: 18px;
    text-align: center;
}

/* =========================
   HERO STRUCTURE
   ========================= */

.hero {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr;
    background:
        linear-gradient(115deg,
            #0a0a0a 0%,
            #2b2b2b 34%,
            #4a4a4a 45%,
            var(--lime) 46%,
            #96d901 100%);
}

.hero-texture {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background-image: repeating-linear-gradient(
        115deg,
        rgba(255, 255, 255, 0.05) 0px,
        rgba(255, 255, 255, 0.05) 3px,
        transparent 3px,
        transparent 46px
    );
}

.hero-texture::before {
    content: "";
    position: absolute;
    top: -10%;
    left: 6%;
    width: 42%;
    height: 120%;
    background: rgba(255, 255, 255, 0.035);
    clip-path: polygon(20% 0, 40% 0, 0 100%, 0 78%);
}

.hero-visual {
    position: absolute;
    z-index: 2;
    top: 0;
    right: 0;
    width: 46%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-mark-large {
    width: 62%;
    max-width: 460px;
    color: rgba(0, 0, 0, 0.16);
    transform: rotate(-6deg);
}

.hero-content {
    position: relative;
    z-index: 3;
    align-self: center;
    padding: 145px 45px 220px 48px;
    max-width: 780px;
}

.hero-title {
    color: var(--white);
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(80px, 10vw, 150px);
    line-height: 0.95;
    letter-spacing: 0.01em;
    margin-bottom: 30px;
}

.hero-title .accent { color: var(--lime); }

.hero-description {
    max-width: 560px;
    color: var(--white);
    font-family: var(--body-font);
    font-size: 20px;
    font-weight: 600;
    line-height: 1.5;
    letter-spacing: 0.01em;
    margin-bottom: 38px;
}

.hero-buttons {
    display: flex;
    align-items: center;
    gap: 24px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-width: 150px;
    min-height: 46px;
    padding: 14px 26px;
    border-radius: 4px;
    text-decoration: none;
    font-family: var(--heading-font);
    font-weight: 700;
    font-style: italic;
    font-size: 22px;
    line-height: 1;
    letter-spacing: 0.02em;
    transition: transform .18s ease, background .18s ease, color .18s ease;
    cursor: pointer;
    border: none;
}

.btn:hover { transform: translateY(-2px); }

.btn-primary {
    color: var(--black);
    background: var(--lime);
}

.btn-primary span {
    font-family: Arial, sans-serif;
    font-style: normal;
    font-size: 26px;
    line-height: 0;
}

.btn-secondary {
    color: var(--white);
    background: rgba(0, 0, 0, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.6);
}

.btn-secondary:hover { background: rgba(255, 255, 255, 0.15); }

/* =========================
   STATS
   ========================= */

.stats {
    position: absolute;
    z-index: 3;
    left: 24px;
    right: 24px;
    bottom: 46px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    align-items: end;
    pointer-events: none;
}

.stat {
    text-align: center;
    color: var(--white);
}

.stat-1, .stat-2 { color: rgba(255, 255, 255, 0.55); }
.stat-3, .stat-4 { color: var(--white); }

.stat strong {
    display: block;
    font-family: var(--heading-font);
    font-size: clamp(38px, 4.2vw, 68px);
    font-weight: 900;
    font-style: italic;
    line-height: 0.9;
}

.stat span {
    display: block;
    margin-top: 10px;
    font-family: var(--body-font);
    font-size: clamp(13px, 1.3vw, 19px);
    font-weight: 700;
    font-style: italic;
    line-height: 1;
    letter-spacing: 0.02em;
}

a:focus-visible, button:focus-visible, select:focus-visible, input:focus-visible {
    outline: 3px solid var(--lime);
    outline-offset: 2px;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* =========================
   PRODUCT COLLECTION
   ========================= */

.collection-banner {
    background: linear-gradient(180deg, #050505 0%, #6f6f6f 100%);
    color: var(--white);
    display: grid;
    grid-template-columns: .55fr 1fr;
    align-items: center;
    gap: 40px;
    padding: 64px 64px 56px;
}

.collection-mark {
    width: 140px;
    height: 140px;
    color: rgba(255, 255, 255, 0.9);
    transform: rotate(-8deg);
}

.collection-mark svg { width: 100%; height: 100%; }

.collection-copy .eyebrow {
    display: block;
    font-family: var(--body-font);
    font-weight: 700;
    font-style: italic;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    font-size: 20px;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 18px;
}

.collection-copy h2 {
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(40px, 5vw, 80px);
    line-height: 1.15;
    color: var(--white);
}

.collection-copy h2 .accent { color: var(--lime); }

.collection-tagline {
    margin-top: 20px;
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(20px, 2.6vw, 36px);
    color: var(--white);
}

.collection-tagline .accent { color: var(--lime); }

/* =========================
   INSIDER ACCESS / FOOTER
   ========================= */

.newsletter {
    background: var(--lime);
    display: grid;
    grid-template-columns: .8fr 1.2fr;
}

.newsletter-visual {
    background: linear-gradient(160deg, #141414, #2a2a2a);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px;
}

.newsletter-content {
    padding: 80px 64px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.newsletter-content h2 {
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(42px, 5vw, 72px);
    color: var(--white);
    margin-bottom: 16px;
}

.newsletter-content > p {
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 22px;
    color: var(--black);
    max-width: 600px;
    margin-bottom: 32px;
}

.signup-form {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 999px;
    padding: 6px 6px 6px 24px;
    max-width: 480px;
    margin-bottom: 50px;
}

.signup-form input {
    flex: 1;
    border: none;
    background: none;
    font-family: var(--body-font);
    font-size: 16px;
    padding: 12px 0;
}

.signup-form input:focus { outline: none; }

.signup-form button {
    border: none;
    background: var(--black);
    color: var(--white);
    font-family: var(--body-font);
    font-weight: 700;
    text-transform: lowercase;
    padding: 12px 22px;
    border-radius: 999px;
    cursor: pointer;
}

.footer-links-row {
    display: grid;
    grid-template-columns: repeat(3, auto);
    gap: 50px;
}

.footer-links-row h4 {
    font-family: var(--body-font);
    font-weight: 700;
    font-style: italic;
    text-transform: uppercase;
    font-size: 22px;
    margin-bottom: 14px;
    color: var(--black);
}

.footer-links-row a {
    display: block;
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 18px;
    color: rgba(0, 0, 0, 0.75);
    margin-bottom: 8px;
}

.site-footer {
    background: linear-gradient(90deg, #0c0c0c, #2a2a2a);
    color: var(--white);
    padding: 60px 64px 26px;
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr) auto;
    gap: 40px;
}

.footer-grid h4 {
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 22px;
    margin-bottom: 16px;
}

.footer-grid a, .footer-grid p.line {
    display: block;
    font-family: var(--body-font);
    font-size: 16px;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 8px;
}

.footer-brand { text-align: right; }
.footer-brand .logo { color: var(--white); justify-content: flex-end; }

.social-row {
    display: flex;
    gap: 12px;
    margin-top: 14px;
}

.social-row a {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
}

.social-row svg { width: 15px; height: 15px; }

.footer-bottom {
    margin-top: 50px;
    padding-top: 22px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    display: flex;
    justify-content: space-between;
    font-family: var(--body-font);
    font-size: 16px;
    color: rgba(255, 255, 255, 0.6);
}

/* ============================================================
   SHOP PAGE (shop.php)
   ============================================================ */

.shop-hero {
    position: relative;
    padding: 160px var(--page-padding) 60px;
    background:
        linear-gradient(120deg,
            #0a0a0a 0%,
            #232323 40%,
            var(--lime) 130%);
}

.shop-hero-inner { max-width: 900px; }

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 15px;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 26px;
}

.breadcrumb a { color: rgba(255, 255, 255, 0.85); }
.breadcrumb a:hover { color: var(--lime); }

.shop-hero-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(48px, 7vw, 96px);
    line-height: 0.95;
    color: var(--white);
    margin-bottom: 22px;
}

.shop-hero-title .accent { color: var(--lime); }

.shop-hero-desc {
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 20px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.85);
    max-width: 560px;
    margin-bottom: 48px;
}

.shop-hero-stats {
    display: flex;
    gap: 56px;
}

.shop-hero-stats div { display: flex; flex-direction: column; gap: 6px; }

.shop-hero-stats strong {
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(28px, 3vw, 40px);
    color: var(--white);
}

.shop-hero-stats span {
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.6);
}

/* Toolbar */

.shop-toolbar {
    position: sticky;
    top: 0;
    z-index: 5;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 20px var(--page-padding);
    background: #141414;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.filter-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-pill {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: rgba(255, 255, 255, 0.75);
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 15px;
    letter-spacing: 0.01em;
    padding: 10px 20px;
    border-radius: 999px;
    cursor: pointer;
    transition: background .18s ease, color .18s ease, border-color .18s ease;
}

.filter-pill:hover { border-color: var(--lime); color: var(--white); }

.filter-pill.active {
    background: var(--lime);
    border-color: var(--lime);
    color: var(--black);
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 22px;
}

.results-count {
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 15px;
    color: rgba(255, 255, 255, 0.55);
    white-space: nowrap;
}

.sort-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 15px;
    color: rgba(255, 255, 255, 0.75);
}

.sort-label select {
    background: #1f1f1f;
    color: var(--white);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 6px;
    padding: 8px 12px;
    font-family: var(--body-font);
    font-size: 14px;
    cursor: pointer;
}

/* Product Grid */

.shop-grid {
    background: #0d0d0d;
    padding: 56px var(--page-padding) 88px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 28px;
}

.shop-card {
    display: flex;
    flex-direction: column;
    background: #171717;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color .18s ease, transform .18s ease;
}

.shop-card:hover {
    border-color: rgba(175, 250, 1, 0.4);
    transform: translateY(-4px);
}

.shop-card-shot {
    position: relative;
    aspect-ratio: 1/1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px;
}

.shop-card-shot img { height: 100%; width: auto; object-fit: contain; }

.shop-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
    background: var(--black);
    color: var(--lime);
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    padding: 6px 12px;
    border-radius: 999px;
}

.shop-card-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 20px;
}

.shop-card-size {
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 6px;
}

.shop-card-name {
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: 24px;
    color: var(--white);
    margin-bottom: 10px;
}

.shop-card-desc {
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 16px;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.7);
    flex: 1;
    margin-bottom: 18px;
}

.shop-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.shop-card-price {
    display: flex;
    align-items: baseline;
    gap: 8px;
}

.price-compare {
    font-family: var(--body-font);
    font-size: 14px;
    color: rgba(255, 255, 255, 0.4);
    text-decoration: line-through;
}

.price-now {
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: 20px;
    color: var(--lime);
}

.shop-add-cart {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    color: var(--white);
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 15px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    cursor: pointer;
}

.shop-add-cart svg { width: 18px; height: 18px; }

.shop-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 18px;
    color: rgba(255, 255, 255, 0.5);
}

/* Bundle banner */

.bundle-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    flex-wrap: wrap;
    background: linear-gradient(120deg, #1c2b00 0%, #375600 60%, #5c8f00 100%);
    padding: 64px var(--page-padding);
}

.bundle-copy { max-width: 620px; }

.bundle-copy .eyebrow {
    display: block;
    font-family: var(--body-font);
    font-weight: 700;
    font-style: italic;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    font-size: 16px;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 14px;
}

.bundle-copy h2 {
    font-family: var(--heading-font);
    font-weight: 900;
    font-style: italic;
    font-size: clamp(32px, 3.4vw, 46px);
    line-height: 1.15;
    color: var(--white);
    margin-bottom: 16px;
}

.bundle-copy h2 .accent { color: var(--lime); }

.bundle-copy p {
    font-family: var(--body-font);
    font-weight: 600;
    font-size: 18px;
    line-height: 1.55;
    color: rgba(255, 255, 255, 0.85);
}

/* Toast */

.toast {
    position: fixed;
    left: 50%;
    bottom: 32px;
    z-index: 50;
    transform: translate(-50%, 16px);
    opacity: 0;
    pointer-events: none;
    background: var(--lime);
    color: var(--black);
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 15px;
    padding: 14px 26px;
    border-radius: 999px;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
    transition: transform .22s ease, opacity .22s ease;
    max-width: calc(100vw - 48px);
    text-align: center;
}

.toast.show {
    transform: translate(-50%, 0);
    opacity: 1;
}

/* Responsive */

@media (max-width: 1024px) {
    .main-nav { display: none; }
    .hero-content { padding: 130px 28px 200px 28px; max-width: 100%; }
    .shop-grid { grid-template-columns: repeat(2, 1fr); }
    .newsletter { grid-template-columns: 1fr; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 640px) {
    .site-header { padding: 18px 16px; }
    .logo { font-size: 1.8rem; }
    .shop-grid { grid-template-columns: 1fr; }
    .shop-toolbar { flex-direction: column; align-items: flex-start; }
    .toolbar-right { width: 100%; justify-content: space-between; }
    .footer-grid { grid-template-columns: 1fr; }
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
                <a href="<?= htmlspecialchars($link['href']) ?>" class="nav-link<?= !empty($link['active']) ? ' active' : '' ?>">
                    <?= htmlspecialchars(strtoupper($link['label'])) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- HEADER ACTIONS -->
        <div class="header-actions">
            <button type="button" aria-label="Change region">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M3 12h18"/>
                    <path d="M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6-4-9s1.5-6.3 4-9z"/>
                </svg>
            </button>

            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <a href="logout.php" class="header-icon-link" aria-label="Logout" title="Logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                    </svg>
                </a>
            <?php else: ?>
                <a href="login.php" class="header-icon-link" aria-label="Login" title="Login">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                    </svg>
                </a>
            <?php endif; ?>

            <!-- CART -->
            <a href="cart.php" class="header-icon-link cart-button" aria-label="Shopping Cart" title="Shopping Cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M6 8h12l-1 12H7L6 8z"/>
                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                </svg>
                <span class="cart-count" id="cart-count"><?= $cart_count ?></span>
            </a>
        </div>
    </header>

    <!-- =========================
         SHOP HERO
         ========================= -->
    <section class="shop-hero" aria-label="Shop TENSION">
        <div class="shop-hero-inner">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="index.php">Home</a> / <span>Shop</span>
            </nav>

            <h1 class="shop-hero-title">Shop <span class="accent">Tension</span></h1>
            <p class="shop-hero-desc">
                Three flavors. Three sizes. One goal — fuel that keeps up
                with you. Grab a single can to try it, or stock the fridge
                with a case.
            </p>

            <div class="shop-hero-stats">
                <div><strong>3</strong><span>Flavors</span></div>
                <div><strong>1–2</strong><span>Day Shipping</span></div>
                <div><strong><?= $product_count ?></strong><span>Products</span></div>
            </div>
        </div>
    </section>

    <!-- =========================
         SHOP TOOLBAR — filter + sort
         ========================= -->
    <section class="shop-toolbar" aria-label="Filter and sort products">
        <div class="filter-pills" role="group" aria-label="Filter by flavor">
            <?php foreach ($flavor_filters as $key => $label): ?>
                <button type="button" class="filter-pill<?= $key === 'all' ? ' active' : '' ?>" data-filter="<?= htmlspecialchars($key) ?>">
                    <?= htmlspecialchars($label) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="toolbar-right">
            <span class="results-count" id="results-count"><?= $product_count ?> products</span>
            <label class="sort-label" for="sort-select">Sort
                <select id="sort-select">
                    <option value="featured">Featured</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="name-asc">Name: A–Z</option>
                </select>
            </label>
        </div>
    </section>

    <!-- =========================
         SHOP GRID
         ========================= -->
    <main>
        <section class="shop-grid" id="shop-grid" aria-label="Product catalog">
            <?php foreach ($shop_products as $product): ?>
                <article class="shop-card"
                          data-flavor="<?= htmlspecialchars($product['flavor']) ?>"
                          data-price="<?= htmlspecialchars($product['price']) ?>"
                          data-name="<?= htmlspecialchars($product['name'] . ' ' . $product['size']) ?>">

                    <div class="shop-card-shot" style="background: <?= htmlspecialchars($product['color']) ?>;">
                        <?php if ($product['badge']): ?>
                            <span class="shop-badge"><?= htmlspecialchars($product['badge']) ?></span>
                        <?php endif; ?>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name'] . ' — ' . $product['size']) ?>">
                    </div>

                    <div class="shop-card-body">
                        <span class="shop-card-size"><?= htmlspecialchars($product['size']) ?></span>
                        <h3 class="shop-card-name"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="shop-card-desc"><?= htmlspecialchars($product['desc']) ?></p>

                        <div class="shop-card-footer">
                            <div class="shop-card-price">
                                <?php if ($product['compare']): ?>
                                    <span class="price-compare">$<?= number_format($product['compare'], 2) ?></span>
                                <?php endif; ?>
                                <span class="price-now">$<?= number_format($product['price'], 2) ?></span>
                            </div>

                            <form method="post" action="cart.php" style="margin: 0; padding: 0;">
                                <input type="hidden" name="add_to_cart" value="1">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                <input type="hidden" name="size" value="<?= htmlspecialchars($product['size']) ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="add-cart shop-add-cart"
                                        data-name="<?= htmlspecialchars($product['name'] . ' — ' . $product['size']) ?>">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                                    Add to cart
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <p class="shop-empty" id="shop-empty" hidden>No products match that filter yet — try another flavor.</p>
        </section>
    </main>

    <!-- =========================
         BUNDLE BANNER
         ========================= -->
    <section class="bundle-banner" aria-label="Case savings">
        <div class="bundle-copy">
            <span class="eyebrow">Stock The Fridge</span>
            <h2>Buy a case, <span class="accent">save more.</span></h2>
            <p>Every flavor is cheaper by the case — and the Variety Pack means nobody in the house has to compromise.</p>
        </div>
        <a href="#shop-grid" class="btn btn-primary">Shop Cases <span>&rarr;</span></a>
    </section>

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
            <p>Catch every flavor drop, giveaway, and headline-making news.</p>

            <form class="signup-form" action="#" method="post">
                <label for="newsletter-email" class="sr-only">Email address</label>
                <input type="email" id="newsletter-email" name="email" placeholder="Email address" required>
                <button type="submit">get access</button>
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
                    <a href="#">Community</a>
                    <a href="#">Athletes</a>
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
                <a href="index.php" class="logo">
                    <?= brand_mark_svg('logo-mark') ?>
                    <span><span class="accent">T</span>ension</span>
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC. All Rights Reserved.</span>
            <span>Do Not Sell or Share My Personal Information</span>
        </div>
    </footer>

    <div class="toast" id="cart-toast" role="status" aria-live="polite"></div>

    <script>
    (function () {
        var grid = document.getElementById('shop-grid');
        var cards = Array.prototype.slice.call(grid.querySelectorAll('.shop-card'));
        var emptyMsg = document.getElementById('shop-empty');
        var resultsCount = document.getElementById('results-count');
        var pills = Array.prototype.slice.call(document.querySelectorAll('.filter-pill'));
        var sortSelect = document.getElementById('sort-select');
        var activeFilter = 'all';

        function applyFilter() {
            var visible = 0;
            cards.forEach(function (card) {
                var matches = activeFilter === 'all' || card.dataset.flavor === activeFilter;
                card.style.display = matches ? '' : 'none';
                if (matches) visible++;
            });
            emptyMsg.hidden = visible !== 0;
            resultsCount.textContent = visible + (visible === 1 ? ' product' : ' products');
        }

        pills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                pills.forEach(function (p) { p.classList.remove('active'); });
                pill.classList.add('active');
                activeFilter = pill.dataset.filter;
                applyFilter();
            });
        });

        sortSelect.addEventListener('change', function () {
            var value = sortSelect.value;
            var sorted = cards.slice();

            if (value === 'price-asc') {
                sorted.sort(function (a, b) { return parseFloat(a.dataset.price) - parseFloat(b.dataset.price); });
            } else if (value === 'price-desc') {
                sorted.sort(function (a, b) { return parseFloat(b.dataset.price) - parseFloat(a.dataset.price); });
            } else if (value === 'name-asc') {
                sorted.sort(function (a, b) { return a.dataset.name.localeCompare(b.dataset.name); });
            } else {
                sorted = cards;
            }

            sorted.forEach(function (card) { grid.insertBefore(card, emptyMsg); });
        });

        applyFilter();
    })();
    </script>

</body>
</html>