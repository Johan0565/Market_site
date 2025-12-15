<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Apple Store Clone</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="main.js"></script>
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <div class="logo">
            <a href="index.php" class="logo-mark"></a>
            <span class="logo-text">Uni Apple Store</span>
        </div>
        <nav class="main-nav">
            <a href="index.php">Главная</a>
            <a href="shop.php">Магазин</a>
            <a href="contact.php">Обратная связь</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="cart.php">Список покупок</a>
            <?php endif; ?>
        </nav>
        <div class="auth-block">
            <?php if (isset($_SESSION['user'])): ?>
                <span class="user-name">Привет, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                <a href="logout.php" class="btn btn-outline btn-small">Выйти</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline btn-small">Войти</a>
                <a href="register.php" class="btn btn-primary btn-small">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main class="page-content">
