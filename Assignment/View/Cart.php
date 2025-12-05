<link href="/Assignment/View/CSS/Cart.css" rel="stylesheet" type="text/css" />

<div class="cart-container">
    <h2>Your Shopping Cart</h2>
    <?php if (!empty($errorMessage)): ?>
        <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <?php if (empty($CartItems)): ?>
        <p class="empty-cart">Your cart is empty.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (RM)</th>
                    <th>Quantity</th>
                    <th>Total (RM)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $grandTotal = 0;
                    foreach ($CartItems as $row): 
                        $cart = $row['item'];
                        $product = $row['product'];
                        $lineTotal = $product->getPrice() * $cart->getQuantity();
                        $grandTotal += $lineTotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($product->getProductName()) ?></td>
                    <td><?= number_format($product->getPrice(), 2) ?></td>
                    <td>
                        <form method="POST" action="/Assignment/index.php/UpdateCartItem" class="quantity-form">
                            <input type="hidden" name="cartid" value="<?= $cart->getCartID() ?>">
                            <input type="number" name="quantity" value="<?= $cart->getQuantity() ?>" min="1" class="quantity-input">
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>
                    <td><?= number_format($lineTotal, 2) ?></td>
                    <td>
                        <form method="POST" action="/Assignment/index.php/RemoveCartItem">
                            <input type="hidden" name="cartid" value="<?= $cart->getCartID() ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total-label">Grand Total:</td>
                    <td colspan="2" class="total-value">RM <?= number_format($grandTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="checkout-actions">
            <form method="POST" action="/Assignment/index.php/Checkout">
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<script>
document.querySelectorAll(".quantity-form").forEach(function(form) {
    form.addEventListener("submit", function(e) {
        let qtyInput = form.querySelector(".quantity-input");
        let quantity = qtyInput.value.trim();

        if (quantity === "") {
            alert("Quantity cannot be empty.");
            e.preventDefault();
            return;
        }

        if (!/^[1-9][0-9]*$/.test(quantity)) {
            alert("Quantity must be a whole number greater than 0 (no decimals allowed).");
            e.preventDefault();
            return;
        }
    });
});
</script>
