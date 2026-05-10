-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 10 May 2026, 15:40:59
-- Sunucu sürümü: 8.4.7
-- PHP Sürümü: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `edu_pool`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bookmarks`
--

DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `project_id`, `comment`, `created_at`) VALUES
(1, 4, 1, '', '2026-05-02 13:54:02'),
(2, 4, 1, 'ff', '2026-05-02 13:54:03'),
(3, 4, 1, '', '2026-05-02 13:54:13'),
(4, 5, 2, 'on numara', '2026-05-04 20:58:37'),
(5, 14, 8, 'çok iyi olmuş', '2026-05-05 10:52:11'),
(6, 14, 6, 'çok geliştirici bir sunum', '2026-05-05 10:52:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `contents`
--

DROP TABLE IF EXISTS `contents`;
CREATE TABLE IF NOT EXISTS `contents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `contents`
--

INSERT INTO `contents` (`id`, `title`, `description`, `type`, `lesson_id`, `user_id`, `file_path`) VALUES
(26, NULL, 'Fonksiyonlar', 'note', 7, 9, 'uploads/1777976875_sunum1.pptx'),
(22, NULL, 'HTML', 'question', 2, 9, 'uploads/1777976480_Örnekler.rar'),
(28, NULL, 'Sınıf', 'video', 7, 9, 'https://youtu.be/2EUIvEEVbcM?si=A04XRMWVkassS8Gr'),
(21, '', 'HTML', 'video', 2, 9, 'https://youtu.be/YaDrFMt7fdU?si=yA5ilCeNikwO634U'),
(20, '', 'HTML', 'note', 2, 9, 'uploads/1777976125_chp01.pdf'),
(29, NULL, 'Fonksiyonlar', 'question', 8, 9, 'uploads/1777977164_b3cbe00bded6d903ce22576f701df12b.webp'),
(30, NULL, '25-26 Çıkmışlar', 'question', 9, 9, 'uploads/1777977283_WhatsApp Görsel 2025-06-21 saat 23.38.12_4f7b3096.jpg'),
(31, NULL, 'Elektrik Alan', 'note', 4, 9, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `lessons`
--

DROP TABLE IF EXISTS `lessons`;
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lesson_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `lessons`
--

INSERT INTO `lessons` (`id`, `lesson_name`) VALUES
(1, 'Genel'),
(2, 'web programlama'),
(3, 'mat'),
(4, 'fizik'),
(5, 'kimya'),
(6, 'Bilgisayar Organizasyonu'),
(7, 'Görsel Programlama'),
(8, 'Matematik'),
(9, 'İnkılap');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `project_id`) VALUES
(17, 14, 5),
(15, 14, 7),
(7, 4, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `how_made` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `description`, `how_made`, `file_path`, `created_at`) VALUES
(6, 14, 'Fransız Guyanası Tanıtım Sunumu', 'Fransa\'nın bir ili', 'Fransa\'dan bir il seçip araştırdım', '', '2026-05-05 10:48:00'),
(5, 14, 'Merdiven Veri Yapısı', 'Yeni bir veri yapısı oluşturmamız istendi', 'Merdivenin katlı yapısını var olan veri yapılarına uyarladım.', 'uploads/1777977741_230709028_ZeynepYilmaz.pdf', '2026-05-05 10:42:21'),
(7, 14, 'Trafik Veri Yapısı', 'Özgün veri yapısı', 'Trafiği veri yapısına uyarladım', 'uploads/1777978200_230709066_DilrubaTosun.txt', '2026-05-05 10:50:00'),
(8, 14, 'Akıllı Buzdolabı', 'Akıllı buzdolabı kodu', 'c programlama dilimi geliştirmek için tasarladım', 'uploads/1777978280_230709028_ZEYNEP_YİLMAZ.c', '2026-05-05 10:51:20');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(11, 'Ayşe', 'aysecetin@gmail.com', 'ayse', 'user'),
(12, 'Mehmet', 'mehmetatik@gmail.com', 'memo', 'user'),
(5, 'zeypymz', 'zeymaz05@gmail.com', 'zeynep', 'user'),
(13, 'Melisa', 'melisakaya@gmail.com', 'melis', 'user'),
(9, 'dilruba', 'dilrubatosun2525@gmail.com', 'dilru', 'user'),
(10, 'berfinn', 'berfincelik@gmail.com', 'berfin', 'user'),
(14, 'Zehra', 'zehraturgut@gmail.com', 'zehraa', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
