<?php
session_start();
require "config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$name  = trim($_POST["name"] ?? "");
$price = trim($_POST["price"] ?? "");
$desc  = trim($_POST["description"] ?? "");
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
    if (empty($name)) {
        $errors[] = "Product name is required";
    }
    if (empty($price) || !is_numeric($price)) {
        $errors[] = "Valid price is required";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, price, description, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi", $name, $price, $desc, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: products.php");
        exit();
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: products.php");
    exit();
}

$result = $conn->prepare("SELECT id, product_name, price FROM products WHERE user_id = ?");
$result->bind_param("i", $user_id);
$result->execute();
$products = $result->get_result();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">منتجاتي</h2>
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <form method="POST" action="products.php" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="name" class="form-control" placeholder="Product Name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" value="<?= htmlspecialchars($price) ?>" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="description" class="form-control" placeholder="Description" value="<?= htmlspecialchars($desc) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" name="add" class="btn btn-success w-100">Add</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['product_name']) ?></td>
                <td><?= htmlspecialchars($p['price']) ?></td>
                <td>
                    <a href="products.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">عودة للمتجر</a>
</div>
</body>
</html>
