<?php
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='alert' style='margin: 2rem auto; max-width: 600px;'>Access Denied. Only the test admin can access this page.</div>";
    include '../includes/footer.php';
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id_to_delete]);
    $prod = $stmt->fetch();
    
    if ($prod) {
        if (file_exists($prod['image'])) {
            unlink($prod['image']);
        }
        
        $del_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $del_stmt->execute([$id_to_delete]);
    }
    header("Location: manage-products.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['product_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid('prod_', true) . '.' . $ext;
            $destination = '../images/products/' . $new_filename;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $destination)) {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $destination]);
                header("Location: manage-products.php");
                exit;
            } else {
                $error = "Error saving the image asset.";
            }
        } else {
            $error = "Unsupported image file extension.";
        }
    } else {
        $error = "Please attach a valid image file.";
    }
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<h2>Product Management</h2>

<div class="form-container" style="margin: 2rem 0;">
    <h3>Add New Product</h3>
    <?php if ($error): ?>
        <div class="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="manage-products.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required style="width:100%; border:1px solid var(--border); border-radius:4px; padding:0.75rem;"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="product_image">Product Image</label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required>
        </div>
        <button type="submit" class="btn">Add Product</button>
    </form>
</div>

<h3>Available Products List</h3>
<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($product['image']); ?>" width="60" height="60" style="object-fit:cover; border-radius:4px;"></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                    <td>
                        <a href="manage-products.php?delete=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No products found in the database.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>