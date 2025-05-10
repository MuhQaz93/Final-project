<?php
session_start();
require "config.php";
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT product_name, price, description FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $price, $desc);
if (!$stmt->fetch()) {
    header("Location: index.php");
    exit();
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل المنتج</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($name) ?></h2>
            <p class="card-text">السعر: <?= htmlspecialchars($price) ?></p>
            <p class="card-text"><?= nl2br(htmlspecialchars($desc)) ?></p>
            <a href="index.php" class="btn btn-secondary">العودة للمتجر</a>
        </div>
    </div>
</div>
</body>
</html>
