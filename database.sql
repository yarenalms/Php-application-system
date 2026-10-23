-- Pasaport Başvuru Sistemi — veritabanı şeması ve ÖRNEK veriler
-- Tüm kişi bilgileri (ad, e-posta, TC, telefon) uydurmadır; gerçek kişilerle ilgisi yoktur.
--
-- Demo hesaplar:
--   Yetkili  : yetkili@ornek.com  / admin1234
--   Kullanıcı: kullanici@ornek.com / demo1234

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `pasaport_sistemi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pasaport_sistemi`;

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pasaport_turu` enum('Hususi','Hizmet') NOT NULL,
  `kadro` varchar(50) NOT NULL,
  `unvan` varchar(50) NOT NULL,
  `kurum_sicil_no` varchar(20) NOT NULL,
  `tel_no` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nufus_mudurlugu` varchar(50) NOT NULL,
  `kimlik_fotokopisi` varchar(255) NOT NULL,
  `basvuru_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `surec` varchar(50) NOT NULL,
  `notlar` text DEFAULT NULL,
  `esi_var_mi` enum('Var','Yok') NOT NULL DEFAULT 'Yok',
  `es_kimlik_fotokopisi` varchar(255) DEFAULT NULL,
  `cocuk_sayisi` int(11) DEFAULT 0,
  `faculty` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cocuklar` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `kimlik_fotokopisi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `tc` varchar(11) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `faculty` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Örnek veriler
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `ad_soyad`, `email`, `sifre`, `tc`, `telefon`, `role`, `created_at`, `faculty`, `position`) VALUES
(1, 'Örnek Yetkili', 'yetkili@ornek.com', '$2y$10$f50nq5sF42CGAZJflg4oF.mY7oxkbjWmshR7HD0CaHd/sMY8Qrd/C', '10000000001', '5550000001', 1, '2025-03-01 09:00:00', 'Mühendislik Fakültesi', 'Personel Daire Başkanlığı'),
(2, 'Örnek Kullanıcı', 'kullanici@ornek.com', '$2y$10$Ek9BPZiYQPqg/g.sKBx5U.m8HoLbEVmAKTs0Wno3kXi7Q4UcepeyS', '10000000002', '5550000002', 0, '2025-03-01 09:05:00', 'Mühendislik Fakültesi', 'Memur'),
(3, 'Örnek Akademisyen', 'akademisyen@ornek.com', '$2y$10$Ek9BPZiYQPqg/g.sKBx5U.m8HoLbEVmAKTs0Wno3kXi7Q4UcepeyS', '10000000003', '5550000003', 0, '2025-03-02 10:00:00', 'Tıp Fakültesi', 'Akademik Personel');

INSERT INTO `applications` (`id`, `user_id`, `pasaport_turu`, `kadro`, `unvan`, `kurum_sicil_no`, `tel_no`, `email`, `nufus_mudurlugu`, `kimlik_fotokopisi`, `basvuru_tarihi`, `surec`, `notlar`, `esi_var_mi`, `es_kimlik_fotokopisi`, `cocuk_sayisi`, `faculty`) VALUES
(1, 2, 'Hizmet', '3', 'Memur', '1001', '5550000002', 'kullanici@ornek.com', 'Ankara', 'uploads/ornek_kimlik.png', '2025-03-03 10:00:00', 'Bekliyor', NULL, 'Yok', NULL, 0, 'Mühendislik Fakültesi'),
(2, 3, 'Hususi', '1', 'Öğretim Üyesi', '1002', '5550000003', 'akademisyen@ornek.com', 'İstanbul', 'uploads/ornek_kimlik.png', '2025-03-04 11:30:00', 'Onaylandı', 'Belgeler eksiksiz.', 'Var', 'uploads/ornek_es_kimlik.png', 1, 'Tıp Fakültesi');

INSERT INTO `cocuklar` (`id`, `application_id`, `kimlik_fotokopisi`) VALUES
(1, 2, 'uploads/ornek_cocuk_kimlik.png');

-- --------------------------------------------------------
-- İndeksler, AUTO_INCREMENT ve ilişkiler
-- --------------------------------------------------------

ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `cocuklar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `tc` (`tc`);

ALTER TABLE `admins`       MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `applications` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `cocuklar`     MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `users`        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `cocuklar`
  ADD CONSTRAINT `cocuklar_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

COMMIT;
