<?php
session_start();
require "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $uid = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result($currentRole);
    if ($stmt->fetch()) {
        $newRole = $currentRole == 1 ? 2 : 1;
        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("ii", $newRole, $uid);
        $stmt->execute();
    } else {
        $stmt->close();
    }
    header("Location: users.php");
    exit();
}

$result = $conn->query("SELECT id, username, email, role FROM users");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">إدارة المستخدمين</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>معرف</th>
                <th>اسم المستخدم</th>
                <th>البريد الإلكتروني</th>
                <th>الدور</th>
                <th>إجراء</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($u = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] == 1 ? 'Admin' : 'User' ?></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?toggle=1&id=<?= $u['id'] ?>" class="btn btn-sm <?= $u['role'] == 1 ? 'btn-warning' : 'btn-success' ?>">
                            <?= $u['role'] == 1 ? 'إلغاء الإدارة' : 'ترقية إلى أدمن' ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">لا يمكن تعديل نفسك</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">العودة للصفحة الرئيسية</a>
</div>
</body>
</html>
