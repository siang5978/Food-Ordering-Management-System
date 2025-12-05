<link href="/Assignment/View/CSS/Home.css" rel="stylesheet" type="text/css"/>
<h1>Product List</h1>
<hr class="section-divider">

<h1>Foods</h1>
<div class="product-grid">
    <?php if (!empty($foods)): ?>
        <?php foreach ($foods as $product): ?>
            <div class="product-card">
                <a href="/Assignment/index.php/productDetail/<?= urlencode($product->getProductID()) ?>">
                    <h3><?= htmlspecialchars($product->getProductName()) ?></h3>
                    <p class="description"><?= htmlspecialchars($product->getDescription()) ?></p>
                    <div class="product-footer">
                        <span class="stock">Stock: <?= htmlspecialchars($product->getStock()) ?></span>
                        <span class="price">RM<?= number_format($product->getPrice(), 2) ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No food products found</p>
    <?php endif; ?>
</div>

<hr class="section-divider">

<h1>Drinks</h1>
<div class="product-grid">
    <?php if (!empty($drinks)): ?>
        <?php foreach ($drinks as $product): ?>
            <div class="product-card">
                <a href="/Assignment/index.php/productDetail/<?= urlencode($product->getProductID()) ?>">
                    <h3><?= htmlspecialchars($product->getProductName()) ?></h3>
                    <p class="description"><?= htmlspecialchars($product->getDescription()) ?></p>
                    <div class="product-footer">
                        <span class="stock">Stock: <?= htmlspecialchars($product->getStock()) ?></span>
                        <span class="price">RM<?= number_format($product->getPrice(), 2) ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No drink products found</p>
    <?php endif; ?>
</div>
