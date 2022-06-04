-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-server
-- Tempo de geração: 04/06/2022 às 22:36
-- Versão do servidor: 8.0.19
-- Versão do PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `intracking`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `TAB_CATEGORIES`
--

CREATE TABLE `TAB_CATEGORIES` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `TAB_STATUS`
--

CREATE TABLE `TAB_STATUS` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `TAB_STATUS`
--

INSERT INTO `TAB_STATUS` (`id`, `name`) VALUES
(1, 'To do'),
(2, 'In progress'),
(3, 'Done');

-- --------------------------------------------------------

--
-- Estrutura para tabela `TAB_TASKS`
--

CREATE TABLE `TAB_TASKS` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(100) NOT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `cod_status` int NOT NULL,
  `opening_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `finishing_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `TAB_TASKS_CATEGORIES`
--

CREATE TABLE `TAB_TASKS_CATEGORIES` (
  `task_id` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `TAB_USERS`
--

CREATE TABLE `TAB_USERS` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `session_token` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `TAB_CATEGORIES`
--
ALTER TABLE `TAB_CATEGORIES`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_USER` (`user_id`);

--
-- Índices de tabela `TAB_STATUS`
--
ALTER TABLE `TAB_STATUS`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `TAB_TASKS`
--
ALTER TABLE `TAB_TASKS`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_STATUS` (`cod_status`),
  ADD KEY `FK_USERS` (`user_id`);

--
-- Índices de tabela `TAB_TASKS_CATEGORIES`
--
ALTER TABLE `TAB_TASKS_CATEGORIES`
  ADD PRIMARY KEY (`task_id`,`category_id`),
  ADD KEY `FK_CATEGORY` (`category_id`);

--
-- Índices de tabela `TAB_USERS`
--
ALTER TABLE `TAB_USERS`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `constraint_email` (`email`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `TAB_CATEGORIES`
--
ALTER TABLE `TAB_CATEGORIES`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `TAB_STATUS`
--
ALTER TABLE `TAB_STATUS`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `TAB_TASKS`
--
ALTER TABLE `TAB_TASKS`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `TAB_USERS`
--
ALTER TABLE `TAB_USERS`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `TAB_CATEGORIES`
--
ALTER TABLE `TAB_CATEGORIES`
  ADD CONSTRAINT `FK_USER` FOREIGN KEY (`user_id`) REFERENCES `TAB_USERS` (`id`);

--
-- Restrições para tabelas `TAB_TASKS`
--
ALTER TABLE `TAB_TASKS`
  ADD CONSTRAINT `FK_STATUS` FOREIGN KEY (`cod_status`) REFERENCES `TAB_STATUS` (`id`),
  ADD CONSTRAINT `FK_USERS` FOREIGN KEY (`user_id`) REFERENCES `TAB_USERS` (`id`);

--
-- Restrições para tabelas `TAB_TASKS_CATEGORIES`
--
ALTER TABLE `TAB_TASKS_CATEGORIES`
  ADD CONSTRAINT `FK_CATEGORY` FOREIGN KEY (`category_id`) REFERENCES `TAB_CATEGORIES` (`id`),
  ADD CONSTRAINT `FK_TASK` FOREIGN KEY (`task_id`) REFERENCES `TAB_TASKS` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
