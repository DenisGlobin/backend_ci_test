-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 20 2020 г., 12:28
-- Версия сервера: 5.7.23-0ubuntu0.18.04.1
-- Версия PHP: 7.2.9-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `futureisrise`
--

-- --------------------------------------------------------

--
-- Структура таблицы `boosterpack`
--

CREATE TABLE `boosterpack` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `boosterpack`
--

INSERT INTO `boosterpack` (`id`, `price`, `bank`, `time_created`, `time_updated`) VALUES
(1, '5.00', '0.00', '2020-03-30 00:17:28', '2020-04-19 09:59:31'),
(2, '20.00', '0.00', '2020-03-30 00:17:28', '2020-04-19 09:59:31'),
(3, '50.00', '0.00', '2020-03-30 00:17:28', '2020-04-19 09:59:31');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assign_id` int(10) UNSIGNED NOT NULL,
  `assign_comment_id` int(10) UNSIGNED DEFAULT NULL,
  `text` text NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `assign_id`, `assign_comment_id`, `text`, `likes`, `time_created`, `time_updated`) VALUES
(1, 1, 1, NULL, 'Ну чо ассигн проверим', 0, '2020-03-27 21:39:44', '2020-04-19 09:59:31'),
(2, 1, 1, NULL, 'Второй коммент', 0, '2020-03-27 21:39:55', '2020-04-19 09:59:31'),
(3, 2, 1, 2, 'Второй коммент от второго человека', 0, '2020-03-27 21:40:22', '2020-04-19 09:59:31'),
(4, 2, 1, 2, 'А где тогда первый? :)', 0, '2020-04-20 12:19:05', '2020-04-20 12:19:05');

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `img` varchar(1024) DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `user_id`, `text`, `img`, `likes`, `time_created`, `time_updated`) VALUES
(1, 1, 'Тестовый постик 1', '/images/posts/1.png', 0, '2018-08-30 13:31:14', '2020-04-19 09:59:31'),
(2, 1, 'Печальный пост', '/images/posts/2.png', 0, '2018-10-11 01:33:27', '2020-04-19 09:59:31');

-- --------------------------------------------------------

--
-- Структура таблицы `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `cash_flow` decimal(10,2) NOT NULL DEFAULT '0.00',
  `likes_flow` int(11) NOT NULL DEFAULT '0',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `cash_flow`, `likes_flow`, `time_created`, `time_updated`) VALUES
(5, 2, '0.00', 0, '2020-04-19 02:39:50', '2020-04-19 14:39:50'),
(6, 2, '10.00', 0, '2020-04-19 02:44:04', '2020-04-19 14:44:04'),
(7, 2, '0.00', 0, '2020-04-20 11:46:40', '2020-04-20 11:46:40'),
(8, 2, '0.00', 0, '2020-04-20 11:53:23', '2020-04-20 11:53:23'),
(9, 2, '-3.00', 3, '2020-04-20 11:54:46', '2020-04-20 11:54:46'),
(10, 2, '-1.00', 1, '2020-04-20 11:55:02', '2020-04-20 11:55:02'),
(11, 2, '5.00', 0, '2020-04-20 11:56:17', '2020-04-20 11:56:17');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `personaname` varchar(50) NOT NULL DEFAULT '',
  `avatarfull` varchar(150) NOT NULL DEFAULT '',
  `rights` tinyint(4) NOT NULL DEFAULT '0',
  `likes` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_refilled` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` datetime NOT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `personaname`, `avatarfull`, `rights`, `likes`, `wallet_balance`, `wallet_total_refilled`, `wallet_total_withdrawn`, `time_created`, `time_updated`) VALUES
(1, 'admin@niceadminmail.pl', 'password', 'AdminProGod', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/96/967871835afdb29f131325125d4395d55386c07a_full.jpg', 0, 0, '0.00', '0.00', '0.00', '2019-07-26 01:53:54', '2020-04-19 09:59:31'),
(2, 'simpleuser@niceadminmail.pl', 'password', 'simpleuser', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/86/86a0c845038332896455a566a1f805660a13609b_full.jpg', 0, 4, '11.00', '15.00', '4.00', '2019-07-26 01:53:54', '2020-04-20 12:28:33');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `boosterpack`
--
ALTER TABLE `boosterpack`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `time_updated` (`time_updated`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `boosterpack`
--
ALTER TABLE `boosterpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
