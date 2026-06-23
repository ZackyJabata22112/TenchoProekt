<?php
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

$cart_items = [];
$total_price = 0.00;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', $_SESSION['cart']));
    
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll();

    $counts = array_count_values($_SESSION['cart']);

    foreach ($products as $product) {
        $qty = $counts[$product['id']];
        $item_total = $product['price'] * $qty;
        $total_price += $item_total;

        $product['quantity'] = $qty;
        $product['item_total'] = $item_total;
        $cart_items[] = $product;
    }
}
?>

<h2>Your Shopping Cart</h2>

<?php if (!empty($cart_items)): ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                    <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['item_total'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="font-weight: bold; background-color: #efe5dc;">
                <td colspan="3" style="text-align: right;">Cart Order Grand Total:</td>
                <td>$<?php echo number_format($total_price, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <form action="cart.php" method="POST">
            <button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>
        </form>
        <button class="btn" onclick="alert('Checkout feature coming soon!')">Proceed to Checkout</button>
    </div>
<?php else: ?>
    <div class="form-container" style="text-align: center; margin-top: 3rem;">
        <p style="color: #6d4c41; margin-bottom: 1.5rem;">Your shopping cart is completely empty.</p>
        <a href="products.php" class="btn">Browse Products</a>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>