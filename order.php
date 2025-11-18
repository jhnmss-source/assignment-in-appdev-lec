<?php
// Define the menu items and their prices (Order => Price)
$menu = [
    'Burger' => 50.00,
    'Fries' => 75.00,
    'Steak' => 150.00,
];

// Initialize variables for results
$result_output = '';
$selected_order = '';
$quantity = '';
$cash = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize and validate inputs
    $selected_order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_SPECIAL_CHARS);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $cash = filter_input(INPUT_POST, 'cash', FILTER_VALIDATE_FLOAT);

    // Basic validation check
    if ($quantity === false || $quantity <= 0 || $cash === false || $cash <= 0 || !isset($menu[$selected_order])) {
        $result_output = '<p style="color: red; font-weight: bold;">❌ Error: Please enter valid positive values for Quantity and Cash, and select an item.</p>';
    } else {
        // 2. Perform Calculations
        $price = $menu[$selected_order];
        $total_cost = $price * $quantity;
        $change = $cash - $total_cost;

        // 3. Check for sufficient cash
        if ($change < 0) {
            $result_output = '<p style="color: red; font-weight: bold;">❌ Payment Failed: Cash amount (₱' . number_format($cash, 2) . ') is insufficient. Total cost is ₱' . number_format($total_cost, 2) . '.</p>';
        } else {
            // 4. Display Successful Result
            $result_output = "
                <div style='margin-top: 20px; padding: 10px; border: 1px solid black;'>
                    <p>✅ **Order Summary:**</p>
                    <ul>
                        <li>**Item:** $selected_order (₱" . number_format($price, 2) . " each)</li>
                        <li>**Quantity:** $quantity</li>
                        <li>**Total Cost:** ₱" . number_format($total_cost, 2) . "</li>
                        <li>**Cash Paid:** ₱" . number_format($cash, 2) . "</li>
                        <li>**Change Due:** <span style='color: green; font-weight: bold;'>₱" . number_format($change, 2) . "</span></li>
                    </ul>
                </div>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Order System</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { font-size: 2em; margin-bottom: 20px; }
        table { border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid black; padding: 8px 15px; text-align: left; }
        th { background-color: #f2f2f2; }
        label, select, input[type="number"], input[type="text"] {
            display: block;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        select, input[type="number"], input[type="text"] {
            padding: 5px;
            width: 200px;
            box-sizing: border-box;
            border: 1px solid #000;
        }
        input[type="submit"] {
            padding: 8px 15px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h1>Menu</h1>

    <table>
        <tr>
            <th>Order</th>
            <th>Amount</th>
        </tr>
        <?php foreach ($menu as $item => $price): ?>
        <tr>
            <td><?php echo htmlspecialchars($item); ?></td>
            <td><?php echo number_format($price, 0); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        
        <label for="order">Select an order</label>
        <select id="order" name="order">
            <?php foreach ($menu as $item => $price): ?>
            <option value="<?php echo htmlspecialchars($item); ?>" 
                    <?php if ($selected_order === $item) echo 'selected'; ?>>
                <?php echo htmlspecialchars($item); ?>
            </option>
            <?php endforeach; ?>
        </select>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" min="1" required>

        <label for="cash">Cash</label>
        <input type="text" id="cash" name="cash" value="<?php echo htmlspecialchars($cash); ?>" required>

        <input type="submit" value="Submit">
    </form>
    
    <?php echo $result_output; ?>

</body>
</html>