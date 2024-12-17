-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 11:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_12_ipt10`
--

-- --------------------------------------------------------

--
-- Table structure for table `exam_attempts`
--

CREATE TABLE `exam_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_items` int(11) NOT NULL,
  `exam_score` int(11) NOT NULL,
  `attempt_date_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_attempts`
--

INSERT INTO `exam_attempts` (`id`, `user_id`, `exam_items`, `exam_score`, `attempt_date_time`) VALUES
(18, 22, 5, 1, '2024-12-17 18:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_item_number` int(11) NOT NULL,
  `question` text NOT NULL,
  `choices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`choices`)),
  `correct_answer` char(1) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_item_number`, `question`, `choices`, `correct_answer`, `created_at`) VALUES
(1, 1, 'What does the \'MP\' stand for in MP3?', '[{\"letter\": \"A\", \"choice\": \"Moving Picture\"}, {\"letter\": \"B\", \"choice\": \"Music Player\"}, {\"letter\": \"C\", \"choice\": \"Multi Pass\"}, {\"letter\": \"D\", \"choice\": \"Micro Point\"}]', 'A', '2024-12-16 19:59:14'),
(2, 2, 'What does the computer software acronym JVM stand for?', '[{\"letter\": \"A\", \"choice\": \"Java Vendor Machine\"}, {\"letter\": \"B\", \"choice\": \"Java Visual Machine\"}, {\"letter\": \"C\", \"choice\": \"Just Virtual Machine\"}, {\"letter\": \"D\", \"choice\": \"Java Virtual Machine\"}]', 'D', '2024-12-16 19:59:14'),
(3, 3, 'What does GHz stand for?', '[{\"letter\": \"A\", \"choice\": \"Gigahotz\"}, {\"letter\": \"B\", \"choice\": \"Gigahetz\"}, {\"letter\": \"C\", \"choice\": \"Gigahatz\"}, {\"letter\": \"D\", \"choice\": \"Gigahertz\"}]', 'D', '2024-12-16 19:59:14'),
(4, 4, 'What is the most preferred image format used for logos in the Wikimedia database?', '[{\"letter\": \"A\", \"choice\": \".svg\"}, {\"letter\": \"B\", \"choice\": \".png\"}, {\"letter\": \"C\", \"choice\": \".jpeg\"}, {\"letter\": \"D\", \"choice\": \".gif\"}]', 'A', '2024-12-16 19:59:14'),
(5, 5, 'In computing, what does LAN stand for?', '[{\"letter\": \"A\", \"choice\": \"Local Area Network\"}, {\"letter\": \"B\", \"choice\": \"Long Antenna Node\"}, {\"letter\": \"C\", \"choice\": \"Light Access Node\"}, {\"letter\": \"D\", \"choice\": \"Land Address Navigation\"}]', 'A', '2024-12-16 19:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email_address`, `password`, `created_at`) VALUES
(22, 'John Doe', 'John', 'Doe', 'Johndoe@gmail.com', '$2y$10$hTM29dmypap.7huyvCSQEeJULV5mfelER5E8w1OdWSe5hStTqFike', '2024-12-17 18:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `users_answers`
--

CREATE TABLE `users_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `date_answered` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_answers`
--

INSERT INTO `users_answers` (`id`, `user_id`, `attempt_id`, `answers`, `date_answered`) VALUES
(22, 22, 18, '{\"1\":\"A\",\"2\":\"A\",\"3\":\"B\",\"4\":\"C\",\"5\":\"D\"}', '2024-12-17 18:34:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_answers`
--
ALTER TABLE `users_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `attempt_id` (`attempt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users_answers`
--
ALTER TABLE `users_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  ADD CONSTRAINT `exam_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_answers`
--
ALTER TABLE `users_answers`
  ADD CONSTRAINT `users_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_answers_ibfk_2` FOREIGN KEY (`attempt_id`) REFERENCES `exam_attempts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
