<?php
require 'db.php';
include 'header.php';

// Фильтры
$category  = isset($_GET['category']) && $_GET['category'] !== '' ? $_GET['category'] : null;
$search    = isset($_GET['q']) ? trim($_GET['q']) : '';
$minPrice  = (isset($_GET['min_price']) && $_GET['min_price'] !== '') ? (int) $_GET['min_price'] : null;
$maxPrice  = (isset($_GET['max_price']) && $_GET['max_price'] !== '') ? (int) $_GET['max_price'] : null;

// Пагинация
$perPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$conditions = [];
$params = [];

if ($category) {
    $conditions[] = "category = :category";
    $params['category'] = $category;
}

if ($search !== '') {
    $conditions[] = "(name LIKE :q OR short_description LIKE :q OR description LIKE :q)";
    $params['q'] = '%' . $search . '%';
}

if ($minPrice !== null && $minPrice >= 0) {
    $conditions[] = "price >= :min_price";
    $params['min_price'] = $minPrice;
}

if ($maxPrice !== null && $maxPrice >= 0) {
    $conditions[] = "price <= :max_price";
    $params['max_price'] = $maxPrice;
}

$whereSql = '';
if (!empty($conditions)) {
    $whereSql = 'WHERE ' . implode(' AND ', $conditions);
}

// Общее количество товаров
$countSql = "SELECT COUNT(*) FROM products {$whereSql}";
$countStmt = $pdo->prepare($countSql);
foreach ($params as $key => $value) {
    $countStmt->bindValue(':' . $key, $value);
}
$countStmt->execute();
$totalProducts = (int) $countStmt->fetchColumn();

$totalPages = max(1, (int) ceil($totalProducts / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;

// Список товаров для текущей страницы
$dataSql = "SELECT * FROM products {$whereSql} ORDER BY category, name LIMIT :limit OFFSET :offset";
$dataStmt = $pdo->prepare($dataSql);
foreach ($params as $key => $value) {
    $dataStmt->bindValue(':' . $key, $value);
}
$dataStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$dataStmt->execute();
$products = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

// Текущие параметры запроса без номера страницы (для ссылок пагинации)
$queryWithoutPage = $_GET;
unset($queryWithoutPage['page']);
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Магазин</h1>
        <p class="section-subtitle">Выберите устройство Apple, которое подходит именно вам.</p>

        <form method="get" class="shop-filters">
            <div class="shop-filters-row">
                <div class="shop-filter-group shop-filter-search">
                    <label>
                        <span>Поиск</span>
                        <input type="text" name="q" placeholder="Название или описание" value="<?php echo htmlspecialchars($search); ?>">
                    </label>
                </div>
                <div class="shop-filter-group">
                    <label>
                        <span>Категория</span>
                        <select name="category">
                            <option value="">Все категории</option>
                            <option value="iPhone" <?php if ($category === 'iPhone') echo 'selected'; ?>>iPhone</option>
                            <option value="iPad" <?php if ($category === 'iPad') echo 'selected'; ?>>iPad</option>
                            <option value="MacBook" <?php if ($category === 'MacBook') echo 'selected'; ?>>Mac</option>
                            <option value="Watch" <?php if ($category === 'Watch') echo 'selected'; ?>>Watch &amp; AirPods</option>
                        </select>
                    </label>
                </div>
                <div class="shop-filter-group shop-filter-price">
                    <label>
                        <span>Цена от</span>
                        <input type="number" name="min_price" min="0" placeholder="0" value="<?php echo $minPrice !== null ? htmlspecialchars($minPrice) : ''; ?>">
                    </label>
                    <label>
                        <span>до</span>
                        <input type="number" name="max_price" min="0" placeholder="∞" value="<?php echo $maxPrice !== null ? htmlspecialchars($maxPrice) : ''; ?>">
                    </label>
                </div>
                <div class="shop-filter-actions">
                    <button type="submit" class="btn btn-small btn-primary">Применить</button>
                    <?php if (!empty($_GET)): ?>
                        <a href="shop.php" class="btn btn-small btn-ghost">Сбросить</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Краткое описание</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-thumb-img">
                            <?php else: ?>
                                <div class="product-thumb placeholder-thumb">
                                    <?php echo htmlspecialchars($product['category'][0]); ?>
                                </div>
                            <?php endif; ?>

                        </td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo number_format($product['price'], 0, ',', ' '); ?> ₽</td>
                        <td><?php echo htmlspecialchars($product['short_description']); ?></td>
                        <td>
                            <a class="btn btn-small btn-outline" href="product.php?id=<?php echo $product['id']; ?>">
                                Подробнее
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6">Товары не найдены.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <div class="pagination-inner">
                    <?php if ($page > 1): ?>
                        <?php
                        $prevParams = $queryWithoutPage;
                        $prevParams['page'] = $page - 1;
                        ?>
                        <a class="pagination-link pagination-prev" href="shop.php?<?php echo htmlspecialchars(http_build_query($prevParams)); ?>">Назад</a>
                    <?php endif; ?>

                    <div class="pagination-pages">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <?php
                            $pageParams = $queryWithoutPage;
                            $pageParams['page'] = $p;
                            ?>
                            <a class="pagination-link <?php if ($p == $page) echo 'is-active'; ?>" href="shop.php?<?php echo htmlspecialchars(http_build_query($pageParams)); ?>">
                                <?php echo $p; ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <?php if ($page < $totalPages): ?>
                        <?php
                        $nextParams = $queryWithoutPage;
                        $nextParams['page'] = $page + 1;
                        ?>
                        <a class="pagination-link pagination-next" href="shop.php?<?php echo htmlspecialchars(http_build_query($nextParams)); ?>">Вперёд</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
<?php include 'footer.php'; ?>
