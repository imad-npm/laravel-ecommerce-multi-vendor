<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Payment</title>
    <style>
        /* Add some basic styling for the form */
        .form-row {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Enter Your Card Details</h1>

    <form method="POST" action="{{ route('customer.payment.card.process', $order) }}">
        @csrf
        <div class="form-row">
            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" required>
        </div>
        <div class="form-row">
            <label for="expiry_date">Expiry Date</label>
            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
        </div>
        <div class="form-row">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" required>
        </div>

        <button type="submit">Submit Payment</button>
    </form>
</body>
</html>
