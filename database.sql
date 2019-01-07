-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Pon 07. led 2019, 10:41
-- Verze serveru: 10.1.37-MariaDB
-- Verze PHP: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `kiv-web`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `perex` text COLLATE utf8mb4_czech_ci NOT NULL,
  `image` varchar(200) COLLATE utf8mb4_czech_ci NOT NULL,
  `content` text COLLATE utf8mb4_czech_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `returned` tinyint(1) NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_czech_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `score_topic` tinyint(2) DEFAULT NULL,
  `score_content` tinyint(2) DEFAULT NULL,
  `score_readability` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_czech_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`) VALUES
(1, 'admin', 'Administrátor'),
(2, 'author', 'Autor'),
(3, 'reviewer', 'Recenzent');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_czech_ci NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `upated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `surname`, `email`, `password`, `banned`, `upated_at`, `created_at`) VALUES
(1, 'admin', 'Administrátorský', 'Účet', 'admin@admin.cz', '$2y$10$ypnSXpu83VtER7Bs1tZn9uFpjfC28p3ZbSVUNgQnEktAzUVJcE4Q6', 0, '2019-01-07 11:34:48', '2019-01-07 11:34:48');

-- --------------------------------------------------------

--
-- Struktura tabulky `user_role`
--

CREATE TABLE `user_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_articles_user_id` (`user_id`);

--
-- Klíče pro tabulku `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documents_article_id` (`article_id`);

--
-- Klíče pro tabulku `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_article_id` (`article_id`),
  ADD KEY `fk_reviews_user_id` (`user_id`);

--
-- Klíče pro tabulku `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_role_user_id` (`user_id`),
  ADD KEY `fk_user_role_role_id` (`role_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_role_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
