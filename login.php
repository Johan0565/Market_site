<?php
require 'db.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверный email или пароль";
    }
}
include 'header.php';
?>
<section class="section">
    <div class="container auth-container">
        <h1>Вход</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" class="auth-form">
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Пароль
                <input type="password" name="password" required>
            </label>
            <button type="submit" class="btn btn-primary">Войти</button>
            <p class="auth-switch">
                Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a>
            </p>
        </form>
    </div>
</section>
<?php include 'footer.php'; ?>
