<?php
session_start();
require "config.php";
$email = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $username, $hash, $role);
        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credentials";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">تسجيل الدخول</h2>
            <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Login</button>
                <a href="register.php" class="d-block text-center mt-3">Create new account</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
