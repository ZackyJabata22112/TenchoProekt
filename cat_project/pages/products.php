<?php
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    $product_id = intval($_POST['product_id']);
    $_SESSION['cart'][] = $product_id;
    header("Location: products.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<h2>Cat Products Catalog</h2>

<div class="grid">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product">
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="card-price">$<?php echo htmlspecialchars($product['price']); ?></div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="products.php" method="POST" style="margin-top: 1rem;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn" style="width: 100%;">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <p style="font-size: 0.85rem; color: #888; margin-top: 1rem; font-style: italic;">Log in to buy</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products have been added yet.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>