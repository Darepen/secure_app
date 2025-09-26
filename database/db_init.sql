-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 27, 2025 at 01:24 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secure_app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `privacy_consent` tinyint(1) NOT NULL DEFAULT 0,
  `failed_login_attempts` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Tracks consecutive failed login attempts.',
  `account_locked_until` timestamp NULL DEFAULT NULL COMMENT 'Timestamp until the account is locked.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `privacy_consent`, `failed_login_attempts`, `account_locked_until`, `created_at`) VALUES
(1, 'Lhizelle R. Rosel', 'lhizelle.rosel00@gmail.com', '$2y$10$5cy5A8Tsp2sSq3ELpzMjIu3vfFfSBnG4We.iw1jLQ4yIqnXPQcP8q', 1, 0, NULL, '2025-09-26 23:18:05'),
(2, 'Julio Jose Pedrena', 'juliojosepedrena@gmail.com', '$2y$10$PPwtKEEED0ILPzPJWEOKhO12BsUGO9/CwzMcGZ6XWt44uPlmz7zue', 1, 0, NULL, '2025-09-26 23:17:31'),
(3, 'Vladietta Cahayon', 'aughryt_m@vango.com', '$2y$10$ci92VrFmm4RbBMBIDB/iOuvGZxrsp56ifSrwibMx5rpHAklpL2zLO', 1, 0, NULL, '2025-09-26 23:21:18'),
(4, '<script>alert(\'WOW\')</script>', 'gawa@gawa.kolang', '$2y$10$2eUOiQWKFGGDzlojR8igNORQzn.CpZSkp9TuM5pNNQH6Z8xKwZ7gG', 1, 0, NULL, '2025-09-26 23:23:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
