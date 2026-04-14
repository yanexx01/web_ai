-- Миграция для создания таблицы guestbooks
-- Выполните этот SQL запрос в вашей MySQL базе данных

CREATE TABLE IF NOT EXISTS `guestbooks` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `lastname` varchar(255) NOT NULL,
    `firstname` varchar(255) NOT NULL,
    `middlename` varchar(255) DEFAULT NULL,
    `email` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
