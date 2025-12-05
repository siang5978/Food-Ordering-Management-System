<link href="/Assignment/View/CSS/History.css" rel="stylesheet" type="text/css" />
<h2 class="order-history-title">Order History</h2>

<?php if (empty($history)): ?>
    <p class="order-history-empty">You have no order history yet.</p>
<?php else: ?>
<?php foreach ($history as $record): ?>
    <div class="order-history-block">
        <h3 class="order-history-order-id">Order #<?= htmlspecialchars($record['order']->getOrderID()) ?></h3>
        <p class="order-history-total">Total: RM <?= number_format($record['order']->getTotalAmount(), 2) ?></p>
        <p class="order-history-date">Date: <?= htmlspecialchars($record['order']->getOrderDate()) ?></p>

        <table class="order-history-table">
            <thead>
                <tr>
                    <th class="order-history-th">Product Name</th>
                    <th class="order-history-th">Quantity</th>
                    <th class="order-history-th">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($record['items'] as $row): ?>
                    <tr>
                        <td class="order-history-td"><?= htmlspecialchars($row['productName']) ?></td>
                        <td class="order-history-td"><?= $row['item']->getQuantity() ?></td>
                        <td class="order-history-td">RM <?= number_format($row['item']->getPrice(), 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
