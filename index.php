<?php
session_start();
require "config.php";
$result = $conn->query("SELECT id, product_name, price FROM products");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">متجر</a>
        <div class="ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="me-3">مرحباً، <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="products.php" class="btn btn-outline-primary btn-sm me-2">إدارة المنتجات</a>
                <?php if ($_SESSION['role'] == 1): ?>
                    <a href="users.php" class="btn btn-outline-danger btn-sm me-2">إدارة المستخدمين</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm me-2">Login</a>
                <a href="register.php" class="btn btn-success btn-sm">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container py-4">
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h5>
                    <p class="card-text">السعر: <?= htmlspecialchars($row['price']) ?></p>
                    <a href="product.php?id=<?= $row['id'] ?>" class="btn btn-primary">عرض التفاصيل</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
