<link href="/Assignment/View/CSS/Product_Detail.css" rel="stylesheet" type="text/css"/>

<div class="product-detail-page">
    <div class="product-image">
        <?php if ($product->getType() === 'Drink'): ?>
            <img src="/Assignment/drink.jpg" alt="Drink Image"/>
        <?php else: ?>
            <img src="/Assignment/food.jpg" alt="Food Image"/>
        <?php endif; ?>
    </div>

    <div class="product-detail-content">
        <div class="product-info">
            <h2><?= htmlspecialchars($product->getProductName()) ?></h2>
            
            <p><strong>Type:</strong> <?= htmlspecialchars($product->getType()) ?></p>

            <?php if ($product->getType() === 'Drink'): ?>
                <p><strong>Volume:</strong> <?= htmlspecialchars($product->getVolume()) ?> ml</p>
            <?php endif; ?>

            <div class="description">
                <p><strong>Description:</strong></p>
                <p><?= htmlspecialchars($product->getDescription()) ?></p>
            </div>

            <div class="stock-price">
                <span class="stock">Stock: <?= htmlspecialchars($product->getStock()) ?></span>
                <span class="price">RM<?= number_format($product->getPrice(), 2) ?></span>
            </div>
        </div>

        <a href="/Assignment/index.php/AddCart/<?= urlencode($product->getProductID()) ?>" class="add-cart-btn">Add to Cart</a>
    </div>
</div>
