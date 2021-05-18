-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-server
-- Generation Time: May 17, 2021 at 03:03 PM
-- Server version: 8.0.19
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orditi`
--

-- --------------------------------------------------------

--
-- Table structure for table `fiscais`
--

CREATE TABLE `fiscais` (
  `id` int NOT NULL,
  `id_orgao` int NOT NULL,
  `matricula` varchar(15) NOT NULL,
  `token` text,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(60) DEFAULT NULL,
  `cpf` varchar(45) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `tipo_fiscal` int NOT NULL,
  `situacao` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fiscais`
--

INSERT INTO `fiscais` (`id`, `id_orgao`, `matricula`, `token`, `nome`, `email`, `senha`, `cpf`, `telefone`, `tipo_fiscal`, `situacao`) VALUES
(1, 1, '952714-1', NULL, 'Lucas Gabriel Peixoto de Oliveira', 'lucasgabrielpdoliveira@gmail.com', '698d51a19d8a121ce581499d7b701668', '111.657.194-36', '82 9 8718-0470', 4, 1),
(6, 2, '312132-1', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZGVudGl0eSI6IjA3MS4yOTQuMDYxLTg2IiwidHlwZVVzZXIiOjMsImRhdGVUaW1lIjp7ImRhdGUiOiIyMDIxLTA1LTE3IDExOjU5OjQ4LjAwMDAwMCIsInRpbWV6b25lX3R5cGUiOjMsInRpbWV6b25lIjoiQW1lcmljYVwvU2FvX1BhdWxvIn19.1uEKdMWGu4Rg6Q2tpwqiWgeRIMWtcBZEfQDSvCAYFpQ', 'Ian Jairo Torrez Gonzales', 'ijtg.ian@gmail.com', '202cb962ac59075b964b07152d234b70', '071.294.061-86', '82 9 8832-9180', 4, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fiscais`
--
ALTER TABLE `fiscais`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fiscais`
--
ALTER TABLE `fiscais`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
