<?php
require 'db.php';
include 'header.php';

if (!isset($_SESSION['user'])) {
    echo "<p class='container'>Для просмотра списка покупок необходимо <a href='login.php'>войти</a>.</p>";
    include 'footer.php';
    exit;
}

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $removeId = (int) $_POST['remove_id'];
    $del = $pdo->prepare("DELETE FROM cart_items WHERE id = :id AND user_id = :user_id");
    $del->execute(['id' => $removeId, 'user_id' => $userId]);
}

$stmt = $pdo->prepare("
    SELECT ci.id as cart_id, ci.quantity, p.*
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Список покупок</h1>

        <?php if (empty($items)): ?>
            <p>Ваш список пока пуст. Перейдите в <a href="shop.php">магазин</a>, чтобы добавить товары.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="product-table">
                    <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo number_format($item['price'], 0, ',', ' '); ?> ₽</td>
                            <td><?php echo (int) $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> ₽</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="remove_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" class="btn btn-small btn-outline">Убрать</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="cart-total">Итого: <strong><?php echo number_format($total, 0, ',', ' '); ?> ₽</strong></p>
        <?php endif; ?>
    </div>
</section>
<?php include 'footer.php'; ?>
