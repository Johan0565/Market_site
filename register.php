<?php
require 'db.php';
session_start();

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($password !== $password2) {
        $error = "Пароли не совпадают";
    } else {
        // check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = "Пользователь с таким email уже существует";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)");
            $insert->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => $hash
            ]);
            $userId = $pdo->lastInsertId();
            $_SESSION['user'] = [
                'id' => $userId,
                'name' => $name,
                'email' => $email
            ];
            header("Location: index.php");
            exit;
        }
    }
}
include 'header.php';
?>
<section class="section">
    <div class="container auth-container">
        <h1>Регистрация</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" class="auth-form">
            <label>
                Имя
                <input type="text" name="name" required>
            </label>
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Пароль
                <input type="password" name="password" required>
            </label>
            <label>
                Повторите пароль
                <input type="password" name="password2" required>
            </label>
            <button type="submit" class="btn btn-primary">Создать аккаунт</button>
            <p class="auth-switch">
                Уже зарегистрированы? <a href="login.php">Войти</a>
            </p>
        </form>
    </div>
</section>
<?php include 'footer.php'; ?>
