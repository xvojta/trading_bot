-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 29, 2025 at 07:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+01:00";  -- Central European Time (CET, Standard Time)

DROP DATABASE IF EXISTS trading_bot;

-- Create the database
CREATE DATABASE IF NOT EXISTS `trading_bot` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `trading_bot`;

-- Table structure for table `trade_history`
CREATE TABLE IF NOT EXISTS `trade_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(4) DEFAULT NULL,
  `price` decimal(10,4) DEFAULT NULL,
  `amount` decimal(10,4) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `trade_settings`
CREATE TABLE IF NOT EXISTS `trade_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `dip` decimal(5,2) DEFAULT NULL,
  `sell` decimal(5,2) DEFAULT NULL,
  `amount` decimal(10,4) DEFAULT NULL,
  `running` bit(1) NOT NULL DEFAULT b'0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`owner`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `users`
-- INSERT INTO `users` (`id`, `username`, `password`) VALUES
-- (1, 'admin', 'admin'),
-- (2, 'honza', 'honza123'),
-- (3, 'newUser', '123');

-- Dumping data for table `trade_settings`
-- INSERT INTO `trade_settings` (`id`, `owner`, `name`, `dip`, `sell`, `amount`, `running`, `created_at`) VALUES
-- (37, 1, 'Test1', 100.00, 100.00, 10.0000, b'1', '2024-10-08 17:32:05'),
-- (38, 1, 'Test2', 12.00, 23.00, 3.0000, b'0', '2024-10-09 07:15:17'),
-- (39, 1, 'Apartment', 34.00, 34.00, 10.0000, b'1', '2024-10-10 20:08:52'),
-- (40, 1, 'Apartment', 34.00, 34.00, 10.0000, b'0', '2024-10-10 20:09:11'),
-- (41, 1, 'Best', 10.00, 10.00, 500.0000, b'0', '2024-12-29 07:31:03'),
-- (42, 1, 'Funcni', 10.00, 30.00, 700.0000, b'0', '2025-01-08 08:16:23'),
-- (47, 2, 'Test bot', 10.00, 5.00, 100.0000, b'0', '2025-01-25 15:01:12');

COMMIT;
