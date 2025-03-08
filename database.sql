-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2025 at 05:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS battlezone;
USE battlezone;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `battlezone`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `ff_team_registration`
--

CREATE TABLE `ff_team_registration` (
  `id` int(11) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `member1_name` varchar(255) NOT NULL,
  `member1_uid` varchar(255) NOT NULL,
  `member2_name` varchar(255) NOT NULL,
  `member2_uid` varchar(255) NOT NULL,
  `member3_name` varchar(255) NOT NULL,
  `member3_uid` varchar(255) NOT NULL,
  `member4_name` varchar(255) NOT NULL,
  `member4_uid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `kills` int(11) DEFAULT 0,
  `points` int(11) DEFAULT 0,
  `total_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ff_team_registration`
--

INSERT INTO `ff_team_registration` (`id`, `team_name`, `tournament_id`, `member1_name`, `member1_uid`, `member2_name`, `member2_uid`, `member3_name`, `member3_uid`, `member4_name`, `member4_uid`, `user_id`, `user_email`, `registration_date`, `kills`, `points`, `total_points`) VALUES
(14, 'Creators', 3, 'sachinnnnn', '123456', 'sumittttttt', '654321', 'manishhhhh', '098765', 'bupennn', '111111', 2, 'aashish@gmail.com', '2025-03-07 12:30:32', 24, 41, 65),
(15, 'XAPA GANG', 3, 'sachinnnnn', '123456', 'sumittttttt', '654321', 'manish(00)', '098765', 'bupennn', '111111', 6, 'sachins.newa1@gmail.com', '2025-03-07 17:16:22', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_uuid` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('PENDING','COMPLETED','FAILED') NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `transaction_uuid`, `total_amount`, `status`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 3, '911829', 1.00, '', 'eSewa', '2025-03-06 18:26:08', '2025-03-06 18:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `pubg_team_registration`
--

CREATE TABLE `pubg_team_registration` (
  `id` int(11) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `member1_name` varchar(255) NOT NULL,
  `member1_uid` varchar(12) NOT NULL,
  `member2_name` varchar(255) NOT NULL,
  `member2_uid` varchar(12) NOT NULL,
  `member3_name` varchar(255) NOT NULL,
  `member3_uid` varchar(12) NOT NULL,
  `member4_name` varchar(255) NOT NULL,
  `member4_uid` varchar(12) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kills` int(11) DEFAULT 0,
  `points` int(11) DEFAULT 0,
  `total_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pubg_team_registration`
--

INSERT INTO `pubg_team_registration` (`id`, `team_name`, `tournament_id`, `member1_name`, `member1_uid`, `member2_name`, `member2_uid`, `member3_name`, `member3_uid`, `member4_name`, `member4_uid`, `user_id`, `email`, `kills`, `points`, `total_points`) VALUES
(1, 'Demon', 2, 'sumit', '123456', '0', '654321', 'manish', '098765', 'bupennn', '111111', 2, 'aashish@gmail.com', 11, 16, 51),
(4, 'heroes', 2, 'sumit', '123456', '0', '654321', 'manish(00)', '098765', 'sachin 999', '111111', 7, 'saruto@gmail.com', 0, 0, 0),
(5, 'Creators', 2, 'sumit', '123456', '0', '654321', 'manish(00)', '098765', 'krishna', '111111', 7, 'saruto@gmail.com', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_details`
--

CREATE TABLE `room_details` (
  `id` int(11) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `room_password` int(11) NOT NULL,
  `description` text NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('PUBG','FreeFire') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `registration_deadline` date NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `prize_1st` decimal(10,2) NOT NULL,
  `prize_2nd` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `category`, `date`, `time`, `registration_deadline`, `thumbnail`, `price`, `prize_1st`, `prize_2nd`) VALUES
(2, 'padmanshree league', 'PUBG', '2025-03-19', '23:43:00', '2025-03-29', '../uploads/Screenshot 2025-01-21 122732.png', 11.00, 20000.00, 10000.00),
(3, 'FF', 'FreeFire', '2025-03-08', '20:00:00', '2025-03-07', '../uploads/ff.jpg', 200.00, 5000.00, 2000.00),
(5, 'PUBG', 'PUBG', '2025-03-08', '21:00:00', '2025-03-07', '../uploads/Pubg.png', 200.00, 5000.00, 2000.00),
(6, 'PUBG', 'PUBG', '2025-03-08', '20:30:00', '2025-03-07', '../uploads/pubggg.jpeg', 200.00, 5000.00, 2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Fullname`, `email`, `password`, `created_at`) VALUES
(2, 'Aashsih Karki', 'aashish@gmail.com', '$2y$10$22vHAQBRApNtUl9coMnKaOkCoqe7Im951t9TXPEzqNkipayGG7lbi', '2025-03-06 17:35:43'),
(3, 'Sumit Tiruwa', 'sumit@gmail.com', '$2y$10$qORfkxQ.0iPZ2fKGfamgNOihd1iDlQUVFVz73G3IElHeXz2N9L/ay', '2025-03-06 18:05:19'),
(4, 'Sajina Shrestha', 'sajina123@gmail.com', '$2y$10$f6PSfvPli5b.SZtSVSoTYeTiCUHzhPbRjX563mWU8Zz55XKXXqYey', '2025-03-06 21:07:30'),
(6, 'Sachin Shrestha', 'sachins.newa1@gmail.com', '$2y$10$ks83ig8iBx7Ysviv1E4xROGidxAL6C4hJmM1d89Gav/BAowpiAsS6', '2025-03-07 01:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `youtube_lives`
--

CREATE TABLE `youtube_lives` (
  `id` int(11) NOT NULL,
  `video_id` varchar(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `category` enum('pubg','freefire') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `youtube_lives`
--

INSERT INTO `youtube_lives` (`id`, `video_id`, `title`, `description`, `thumbnail_url`, `category`, `created_at`) VALUES
(3, 'VXQ19jO6iWQ', 'Free Fire', '4 round all map 4v4 tournament', 'uploads/67cab1e8e0e1a_ff.jpg', 'freefire', '2025-03-07 08:44:24'),
(4, '063zhkMK7-k', 'PUBG', '5000 price pool only winner gets', 'uploads/67cab29a09e01_pubg.jpg', 'pubg', '2025-03-07 08:47:22'),
(5, '9tQZHfu4hd0', 'FF', 'Win up to 5000', 'uploads/67cab5281b5c4_ff.jpeg', 'freefire', '2025-03-07 08:58:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `ff_team_registration`
--
ALTER TABLE `ff_team_registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pubg_team_registration`
--
ALTER TABLE `pubg_team_registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_name` (`team_name`,`tournament_id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `room_details`
--
ALTER TABLE `room_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `youtube_lives`
--
ALTER TABLE `youtube_lives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `video_id` (`video_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ff_team_registration`
--
ALTER TABLE `ff_team_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pubg_team_registration`
--
ALTER TABLE `pubg_team_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_details`
--
ALTER TABLE `room_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `youtube_lives`
--
ALTER TABLE `youtube_lives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ff_team_registration`
--
ALTER TABLE `ff_team_registration`
  ADD CONSTRAINT `ff_team_registration_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ff_team_registration_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pubg_team_registration`
--
ALTER TABLE `pubg_team_registration`
  ADD CONSTRAINT `pubg_team_registration_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_details`
--
ALTER TABLE `room_details`
  ADD CONSTRAINT `room_details_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
