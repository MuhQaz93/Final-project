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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item me-3">
                        <span class="nav-link">مرحباً، <?= htmlspecialchars($_SESSION['username']) ?></span>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary btn-sm" href="products.php">إدارة المنتجات</a>
                    </li>
                    <?php if ($_SESSION['role'] == 1): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-danger btn-sm" href="users.php">إدارة المستخدمين</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary btn-sm" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a class="btn btn-primary btn-sm" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success btn-sm" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
