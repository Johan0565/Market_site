<?php
require 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "<p class='container'>Товар не найден.</p>";
    include 'footer.php';
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p class='container'>Товар не найден.</p>";
    include 'footer.php';
    exit;
}
?>
<section class="section">
    <div class="container product-layout">
        <div class="product-image-large placeholder-large">
            <img src="<?php echo htmlspecialchars($product['image']); ?>"
     alt="<?php echo htmlspecialchars($product['name']); ?>"
     class="productCart-thumb-img">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="product-price"><?php echo number_format($product['price'], 0, ',', ' '); ?> ₽</p>
            <p class="product-short"><?php echo htmlspecialchars($product['short_description']); ?></p>

            <div class="product-specs">
                <h3>Характеристики</h3>
                <p><?php echo nl2br(htmlspecialchars($product['specs'])); ?></p>
            </div>

            <p class="product-stock">
                В наличии: 
                <?php if ((int)$product['stock'] > 0): ?>
                    <span class="stock-positive"><?php echo (int)$product['stock']; ?> шт.</span>
                <?php else: ?>
                    <span class="stock-negative">нет в наличии</span>
                <?php endif; ?>
            </p>

            <?php if (isset($_SESSION['user'])): ?>
                <form method="post" action="cart_add.php" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <label>
                        Количество:
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo max(1, (int)$product['stock']); ?>">
                    </label>
                    <button type="submit" class="btn btn-primary">Добавить в список покупок</button>
                </form>
            <?php else: ?>
                <p class="auth-hint">
                    Чтобы добавлять товары в список покупок, пожалуйста, <a href="login.php">войдите</a> или 
                    <a href="register.php">зарегистрируйтесь</a>.
                </p>
            <?php endif; ?>

        </div>
    </div>

    <div class="container">
        <h2>Описание</h2>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    </div>
</section>
<?php include 'footer.php'; ?>
