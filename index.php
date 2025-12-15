<?php include 'header.php'; ?>
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-text">
            <h1>Новый взгляд на технику Apple</h1>
            <p>Лаконичный дизайн, максимум внимания к деталям и только лучшие устройства Apple в одном месте.</p>
            <div class="hero-actions">
                <a href="shop.php" class="btn btn-primary">Перейти в магазин</a>
                <a href="#about" class="btn btn-ghost">Подробнее о нас</a>
            </div>
        </div>
        <div class="hero-slider">
            <div class="slide active" data-title="iPhone 16 Pro">
                <div class="slide-card">
                    <h2>iPhone 16 Pro</h2>
                    <p>Профессиональная мощность в тонком корпусе.</p>
                </div>
            </div>
            <div class="slide" data-title="MacBook Air M4">
                <div class="slide-card">
                    <h2>MacBook Air M4</h2>
                    <p>Легкость, скорость и бесшумная мощь для учебы и работы.</p>
                </div>
            </div>
            <div class="slide" data-title="Apple Watch Series 10">
                <div class="slide-card">
                    <h2>Apple Watch Series 10</h2>
                    <p>Ваш умный ассистент здоровья всегда с вами.</p>
                </div>
            </div>
            <div class="slider-dots"></div>
        </div>
    </div>
</section>

<section id="about" class="section">
    <div class="container two-columns">
        <div>
            <h2>О магазине</h2>
            <p>
                Uni Apple Store — учебный интернет-магазин, созданный как аналог официального сайта Apple.
                Здесь демонстрируются навыки верстки, работы с PHP, JavaScript и базой данных.
            </p>
            <p>
                Проект разделен на несколько страниц: главная, магазин с продукцией, детальные карточки товаров,
                авторизация пользователей, список покупок и форма обратной связи.
            </p>
        </div>
        <div>
            <h2>Почему это удобно</h2>
            <ul class="bullet-list">
                <li>Минималистичный интерфейс в стиле Apple</li>
                <li>Фильтрация и быстрый обзор устройств</li>
                <li>Сохранение списка покупок после авторизации</li>
                <li>Обратная связь для ваших предложений</li>
            </ul>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <h2 class="section-title">Популярные категории</h2>
        <div class="grid-3">
            <a href="shop.php?category=iPhone" class="category-card">
                <h3>iPhone</h3>
                <p>Смартфоны с лучшими камерами и производительностью.</p>
            </a>
            <a href="shop.php?category=MacBook" class="category-card">
                <h3>Mac</h3>
                <p>Ноутбуки и компьютеры для учебы, работы и творчества.</p>
            </a>
            <a href="shop.php?category=Watch" class="category-card">
                <h3>Watch &amp; AirPods</h3>
                <p>Устройства, которые всегда с вами.</p>
            </a>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
