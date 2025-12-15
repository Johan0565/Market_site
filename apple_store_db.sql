CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE apple_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    specs TEXT NOT NULL,
    stock INT NOT NULL DEFAULT 0
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO products (name, category, price, short_description, description, specs, stock) VALUES
('iPhone 16 Pro 256 ГБ', 'iPhone', 129990,
 'Профессиональная камера и чип последнего поколения.',
 'iPhone 16 Pro — флагманский смартфон Apple с улучшенной системой камер, ярким дисплеем и мощным процессором. Подходит как для учебы, так и для создания контента.',
 '• Дисплей 6.3" OLED 120 Гц\n• Чип A18 Pro\n• Память 256 ГБ\n• Основная камера 48 Мп\n• Поддержка 5G',
 8),
('iPhone 16 128 ГБ', 'iPhone', 97990,
 'Оптимальный выбор по цене и возможностям.',
 'iPhone 16 — сбалансированная модель с современным дизайном, быстрой работой и качественной камерой.',
 '• Дисплей 6.1" OLED\n• Чип A18\n• Память 128 ГБ\n• Камера 48 Мп',
 12),
('MacBook Air 13" M4', 'MacBook', 139990,
 'Легкий и мощный ноутбук для учебы и работы.',
 'MacBook Air на чипе M4 сочетает высокую производительность и рекордное время автономной работы.',
 '• Дисплей 13.6" Liquid Retina\n• Чип Apple M4\n• 8‑ядерный CPU\n• Память 8 ГБ, SSD 256 ГБ',
 5),
('MacBook Pro 14" M4 Pro', 'MacBook', 199990,
 'Для тех, кому нужна максимальная мощность.',
 'MacBook Pro с чипом M4 Pro подходит для программирования, монтажа видео и работы с 3D-графикой.',
 '• Дисплей 14.2" ProMotion\n• Чип M4 Pro\n• Память 16 ГБ, SSD 512 ГБ',
 3),
('Apple Watch Series 10', 'Watch', 45990,
 'Умные часы с расширенными функциями здоровья.',
 'Apple Watch Series 10 отслеживает активность, сон, пульс и помогает оставаться на связи.',
 '• Дисплей Always-On Retina\n• Оптический и электрический датчики сердца\n• До 18 часов работы',
 15),
('AirPods Pro (2‑е поколение)', 'AirPods', 31990,
 'Беспроводные наушники с активным шумоподавлением.',
 'AirPods Pro обеспечивают качественный звук, режим прозрачности и глубокую интеграцию с устройствами Apple.',
 '• Активное шумоподавление\n• Режим прозрачности\n• Пространственное аудио',
 20);
