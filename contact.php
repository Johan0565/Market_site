<?php
require 'db.php';
include 'header.php';

$success = "";
$error = "";

if (!isset($_SESSION['user'])) {
    // only a hint, form hidden
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if ($subject === '' || $message === '') {
            $error = "Заполните тему и сообщение.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO feedback (user_id, subject, message) VALUES (:user_id, :subject, :message)");
            $stmt->execute([
                'user_id' => $_SESSION['user']['id'],
                'subject' => $subject,
                'message' => $message
            ]);
            $success = "Сообщение отправлено. Спасибо за обратную связь!";
        }
    }
}
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Обратная связь</h1>
        <p class="section-subtitle">Задайте вопрос или предложите идею по улучшению нашего учебного магазина.</p>

        <?php if (!isset($_SESSION['user'])): ?>
            <p>Форма обратной связи доступна только авторизованным пользователям. Пожалуйста, <a href="login.php">войдите</a>.</p>
        <?php else: ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="feedback-form">
                <label>
                    Тема
                    <input type="text" name="subject" required>
                </label>
                <label>
                    Сообщение
                    <textarea name="message" rows="5" required></textarea>
                </label>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php include 'footer.php'; ?>
