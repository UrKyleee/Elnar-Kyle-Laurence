<?php

require 'database/config.php';


/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

$pdo = getConnection();


/*
|--------------------------------------------------------------------------
| GET PRODUCTS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        p.id,
        p.product_code,
        p.name,
        p.size,
        p.flavor_label,
        p.color,
        p.image,
        p.description,
        p.price,
        p.compare_price,
        p.quantity,
        p.badge,
        p.status,
        c.name AS category
    FROM products p

    INNER JOIN categories c
        ON p.category_id = c.id

    ORDER BY p.id ASC
";


$stmt = $pdo->query($sql);

$products = $stmt->fetchAll();

?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Products | TENSION</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            width: min(1400px, 92%);
            margin: 0 auto;
            padding: 50px 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            gap: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 38px;
            letter-spacing: -1px;
        }

        .header p {
            color: #888;
            margin: 8px 0 0;
        }

        .add-btn {
            display: inline-block;
            padding: 12px 20px;
            background: #AFFA01;
            color: #000;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(
                auto-fill,
                minmax(280px, 1fr)
            );
            gap: 24px;
        }

        .product-card {
            background: #111;
            border: 1px solid #242424;
            border-radius: 16px;
            overflow: hidden;
            transition: 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: #444;
        }

        .product-image {
            height: 280px;
            background: #181818;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
        }

        .no-image {
            color: #666;
            font-size: 14px;
        }

        .badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #AFFA01;
            color: #000;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 5px;
            text-transform: uppercase;
        }

        .product-content {
            padding: 22px;
        }

        .category {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-name {
            font-size: 22px;
            margin: 8px 0;
        }

        .size {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .flavor {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #aaa;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .flavor-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .description {
            color: #888;
            font-size: 14px;
            line-height: 1.6;
            min-height: 68px;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .price {
            font-size: 22px;
            font-weight: bold;
        }

        .compare {
            color: #666;
            text-decoration: line-through;
            font-size: 14px;
        }

        .stock {
            margin-top: 15px;
            font-size: 13px;
        }

        .stock.available {
            color: #AFFA01;
        }

        .stock.low {
            color: #ffb000;
        }

        .stock.out {
            color: #ff5555;
        }

        .empty {
            padding: 60px;
            text-align: center;
            border: 1px dashed #333;
            border-radius: 15px;
            color: #777;
        }

        @media (max-width: 700px) {

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header h1 {
                font-size: 30px;
            }

        }

    </style>

</head>

<body>

<div class="container">


    <!-- =========================================
         HEADER
    ========================================== -->

    <div class="header">

        <div>

            <h1>TENSION Products</h1>

            <p>
                Manage and view your current product inventory.
            </p>

        </div>

        <a
            href="shop.php"
            class="add-btn"
        >
            View Shop
        </a>

    </div>


    <!-- =========================================
         PRODUCTS
    ========================================== -->

    <?php if (empty($products)): ?>

        <div class="empty">

            <h2>No products found.</h2>

            <p>
                Add products to your database to display them here.
            </p>

        </div>

    <?php else: ?>

        <div class="products">

            <?php foreach ($products as $product): ?>

                <article class="product-card">


                    <!-- IMAGE -->

                    <div class="product-image">

                        <?php if (!empty($product['badge'])): ?>

                            <span class="badge">
                                <?= htmlspecialchars(
                                    $product['badge']
                                ) ?>
                            </span>

                        <?php endif; ?>


                        <?php if (!empty($product['image'])): ?>

                            <img
                                src="<?= htmlspecialchars(
                                    $product['image']
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $product['name']
                                ) ?>"
                            >

                        <?php else: ?>

                            <span class="no-image">
                                No Image
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- PRODUCT INFORMATION -->

                    <div class="product-content">

                        <div class="category">
                            <?= htmlspecialchars(
                                $product['category']
                            ) ?>
                        </div>


                        <h2 class="product-name">
                            <?= htmlspecialchars(
                                $product['name']
                            ) ?>
                        </h2>


                        <?php if (!empty($product['size'])): ?>

                            <div class="size">
                                <?= htmlspecialchars(
                                    $product['size']
                                ) ?>
                            </div>

                        <?php endif; ?>


                        <?php if (!empty($product['flavor_label'])): ?>

                            <div class="flavor">

                                <span
                                    class="flavor-color"
                                    style="background: <?= htmlspecialchars(
                                        $product['color']
                                    ) ?>;"
                                ></span>

                                <?= htmlspecialchars(
                                    $product['flavor_label']
                                ) ?>

                            </div>

                        <?php endif; ?>


                        <p class="description">

                            <?= htmlspecialchars(
                                $product['description'] ?? ''
                            ) ?>

                        </p>


                        <!-- PRICE -->

                        <div class="price-row">

                            <span class="price">

                                $
                                <?= number_format(
                                    $product['price'],
                                    2
                                ) ?>

                            </span>


                            <?php if (
                                !empty($product['compare_price'])
                            ): ?>

                                <span class="compare">

                                    $
                                    <?= number_format(
                                        $product['compare_price'],
                                        2
                                    ) ?>

                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- STOCK -->

                        <?php

                        $quantity = (int) $product['quantity'];

                        if ($quantity <= 0) {

                            $stockClass = 'out';

                            $stockText = 'Out of stock';

                        } elseif ($quantity <= 10) {

                            $stockClass = 'low';

                            $stockText =
                                $quantity . ' left in stock';

                        } else {

                            $stockClass = 'available';

                            $stockText =
                                $quantity . ' in stock';
                        }

                        ?>

                        <div
                            class="stock <?= $stockClass ?>"
                        >

                            <?= htmlspecialchars(
                                $stockText
                            ) ?>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


</div>

</body>

</html>