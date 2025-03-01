-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 08:33 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` enum('0','1') DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(11, 'Technology', '1', '2025-03-01 06:11:12', NULL),
(12, 'Health and Fitness', '1', '2025-03-01 06:11:25', NULL),
(13, 'Culture and Arts', '1', '2025-03-01 06:11:35', NULL),
(15, 'Education and Self-Improvement', '1', '2025-03-01 06:12:05', NULL),
(16, 'sports', '1', '2025-03-01 06:14:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `files` varchar(255) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `description`, `files`, `status`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES
(103, 'Tech Updates', 'Explore the latest advancements in technology, from artificial intelligence to cybersecurity. Stay up-to-date on the latest trends, innovations, and breakthroughs that are shaping the future. Learn how technology is transforming industries, revolutionizing the way we live and work', 'OIP.jpg', '1', 3, 11, '2025-03-01 06:17:32', NULL),
(108, 'Health and Fitness', '\r\nDiscover the latest insights and advice on health and wellness. Learn how to maintain a healthy lifestyle, from nutrition and exercise to mental wellbeing. Get expert tips and guidance on how to achieve your fitness goals, and stay informed about the latest medical research and breakthroughs.\r\n', 'OIP (1).jpg', '1', 3, 12, '2025-03-01 06:46:39', NULL),
(109, 'Arts & Culture', 'Immerse yourself in the world of culture and arts. Explore the latest developments in music, film, literature, and visual arts. Discover new artists, writers, and creatives, and learn about the cultural trends that are shaping our world.\r\n', 'OIP (2).jpg', '1', 3, 13, '2025-03-01 06:46:58', NULL),
(110, ' Learn & Grow', '\r\nUnlock your full potential with expert advice and guidance on education and self-improvement. Learn new skills, expand your knowledge, and develop your personal and professional growth. Discover the latest trends, tools, and strategies for lifelong learning and self-improvement.', 'Education-Wallpapers-HD-For-Desktop.jpg', '1', 5, 15, '2025-03-01 06:48:00', NULL),
(111, 'Sports News', '\r\nGet the latest news, analysis, and insights from the world of sports. From football and basketball to tennis and golf, stay up-to-date on the latest developments, scores, and player profiles. Learn about the latest trends, technologies, and innovations that are changing the face of sports.\r\n', '.jpg', '1', 5, 16, '2025-03-01 06:48:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `report` varchar(255) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report`, `post_id`, `created_at`) VALUES
(42, '', 103, '2025-03-01 06:41:15'),
(43, '', 103, '2025-03-01 06:41:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `role` enum('user','admin','author') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `status`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$3rBrk3OGg4yVznE8kw2s9urhyucxwf9y684LTH7BFcPNHmLMlcbHK', '1', 'admin', '2025-02-04 21:12:19', '2025-03-01 06:44:45'),
(2, 'admin', 'admin2@gmail.com', '$2y$10$XZpYelGTQDXaThRToPGC/e3fuTBKoYg4NqCkoEWlNE9UtlOl4IhJS', '0', 'admin', '2025-02-04 21:47:29', '2025-02-04 21:48:51'),
(3, 'user', 'author@gmail.com', '$2y$10$VVG2BkOVCv/pHUsvUbq3zum3/kCT71nqytwimTJkQdmdi/D4Zmmhy', '1', 'author', '2025-02-04 21:47:42', '2025-02-25 00:45:13'),
(4, 'user3', 'user@gmail.com', '$2y$10$MMLmwroHt4PYEIumjs9vJ.VzoG0TCe5ULojgUO4oNXb89v1T/5djS', '1', 'user', '2025-02-04 21:47:57', '2025-02-25 01:10:45'),
(5, 'user2', 'author2@gmail.com', '$2y$10$xWG9j392iHz0kNAqMS1DPeiZTi1pKJxIDKWXECjfkrzuXwht69JT2', '1', 'author', '2025-02-07 08:32:58', NULL),
(2798, 'admin', 'admin33@gmail.com', '$2y$10$d3eRc/ZwIUICTzDsmHsTdO.b7qPQGTz7.ZibGNRTLnY2SNcd5xOne', '1', 'user', '2025-03-01 06:50:32', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `relation1` (`user_id`),
  ADD KEY `relation2` (`category_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name2` (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2799;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `relation1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relation2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `name2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
