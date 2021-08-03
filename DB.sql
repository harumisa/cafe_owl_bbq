SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, '田中一郎', 'tanaka@test.ne.jp', 'e4c8a602d1fe3e133aea4c8355b3eed5', 0);

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `option_orders` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(6) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `adult` int(11) NOT NULL,
  `schoolchildren` int(11) NOT NULL,
  `preschooler` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `plan` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `total_price` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name_kana` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `address_prefectures` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `address_city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address_subsequent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `group_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phase` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(6) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `products` (`id`, `name`, `price`, `category`) VALUES
(1, 'リーズナブル', 2750, 1),
(2, 'スタンダード', 3850, 1),
(3, 'デラックス', 5500, 1),
(4, 'キッズ(小学生まで)', 1650, 1),
(5, '小学生未満のお子さま入場料', 200, 1),
(6, 'フリードリンク(アルコール付)', 1650, 2),
(7, 'フリードリンク(ノンアルコール)', 1100, 2),
(8, 'バーベキュースペース 食材持ち込み可(4〜8名様用)', 3500, 0),
(9, 'バーベキュースペース 食材持ち込み可(9名様以上用)', 4000, 0),
(10, 'レンタルスペース 食材持ち込み不可(4〜8名様用)', 1500, 0),
(11, 'レンタルスペース 食材持ち込み不可(9名様以上用)', 2000, 0);

CREATE TABLE `product_orders` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(6) NOT NULL,
  `category` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `option_orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `product_orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `option_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `product_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
