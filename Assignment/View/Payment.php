<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, button { width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; border: none; cursor: pointer; margin-top: 15px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Page</h2>
        <h2>Order Summary</h2>
        <p>Order ID: <?= htmlspecialchars($orderData['orderID']) ?></p>
        <p>Total Amount: <?= number_format($orderData['totalAmount'], 2) ?></p>
        <p>Loyalty Level: <?= htmlspecialchars($orderData['loyaltyLevel']) ?></p>
        <p>Discount: <?= htmlspecialchars($orderData['discountRate'] * 100) ?>%</p>
        <p>Total After Discount: <?= number_format($orderData['totalAmountAfterDiscount'], 2) ?></p>
        <form method="post" action="/Assignment/index.php/UpdatePayment">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" required>
                <option value="">-- Select --</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Online Banking">Online Banking</option>
            </select>

            <div id="card_details" style="display:none;">
                <label for="card_number">Card Number</label>
                <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456">

                <label for="card_name">Cardholder Name</label>
                <input type="text" name="card_name" id="card_name" placeholder="John Doe">
            </div>
            <input type="hidden" name="orderID" value="<?= htmlspecialchars($orderData['orderID'])?>">
            <button type="submit">Pay Now</button>
        </form>
    </div>

    <script>
        const methodSelect = document.getElementById('payment_method');
        const cardDetails = document.getElementById('card_details');

        methodSelect.addEventListener('change', function() {
            if (this.value === 'Credit Card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    </script>
</body>
</html>