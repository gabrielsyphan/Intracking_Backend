-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19-Jan-2021 às 16:44
-- Versão do servidor: 10.4.13-MariaDB
-- versão do PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `teste2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ambulantes`
--

CREATE TABLE `ambulantes` (
  `id` int(11) NOT NULL,
  `id_licenca` int(11) NOT NULL,
  `local_endereco` varchar(400) NOT NULL,
  `ponto_referencia` varchar(200) DEFAULT NULL,
  `produto` int(11) NOT NULL,
  `regiao` int(11) DEFAULT NULL,
  `atendimento_dias` datetime NOT NULL,
  `atendimento_hora_inicio` datetime NOT NULL,
  `atendimento_hora_fim` datetime NOT NULL,
  `relato_atividade` varchar(150) NOT NULL,
  `local_latitude` varchar(45) DEFAULT NULL,
  `local_longitude` varchar(45) DEFAULT NULL,
  `area_equipamento` int(11) NOT NULL,
  `tipo_equipamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `anexos`
--

CREATE TABLE `anexos` (
  `id` int(45) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `tipo_usuario` varchar(45) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `anexos`
--

INSERT INTO `anexos` (`id`, `nome`, `tipo_usuario`, `id_usuario`) VALUES
(15, 'userImage.png', '3', 1),
(20, 'userImage.png', '0', 35),
(21, 'identityImage.png', '0', 35);

-- --------------------------------------------------------

--
-- Estrutura da tabela `boletos`
--

CREATE TABLE `boletos` (
  `id` int(11) NOT NULL,
  `id_licenca` int(11) DEFAULT NULL,
  `licencas_zonas_idzonas` int(11) DEFAULT NULL,
  `status-boletos_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `id_licenca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventual`
--

CREATE TABLE `eventual` (
  `id` int(11) NOT NULL,
  `id_licenca` int(11) NOT NULL,
  `id_eventos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fiscais`
--

CREATE TABLE `fiscais` (
  `id` int(11) NOT NULL,
  `matricula` varchar(15) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `cpf` varchar(45) DEFAULT NULL,
  `tipo_fiscal` int(11) NOT NULL,
  `situacao` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `fiscais`
--

INSERT INTO `fiscais` (`id`, `matricula`, `nome`, `email`, `senha`, `cpf`, `tipo_fiscal`, `situacao`) VALUES
(1, '111111-1', 'Lucas Gabriel Peixoto de Oliveira', 'lucasgabrielpdoliveira@gmail.com', '698d51a19d8a121ce581499d7b701668', '111.657.194-36', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `licencas`
--

CREATE TABLE `licencas` (
  `id` int(11) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `geopoint` varchar(45) DEFAULT NULL,
  `zonas_idzonas` int(11) DEFAULT NULL,
  `data_inicio` varchar(45) DEFAULT NULL,
  `data_fim` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_licencas_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `hora` datetime NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `boletos_idboletos` int(11) DEFAULT NULL,
  `fiscais_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status_boletos`
--

CREATE TABLE `status_boletos` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status_licencas`
--

CREATE TABLE `status_licencas` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_fiscal`
--

CREATE TABLE `tipo_fiscal` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id`, `tipo`) VALUES
(0, 'usuario'),
(1, 'ambulante'),
(2, 'empresa'),
(3, 'fiscal');

-- --------------------------------------------------------

--
-- Estrutura da tabela `uso_de_solo`
--

CREATE TABLE `uso_de_solo` (
  `id` int(11) NOT NULL,
  `id_licenca` int(11) NOT NULL,
  `licencas_usuarios_cpf` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `endereco` varchar(150) NOT NULL,
  `telefone` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rg` varchar(45) NOT NULL,
  `nome_mae` varchar(150) DEFAULT NULL,
  `senha` varchar(45) DEFAULT NULL,
  `situacao` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `cpf`, `nome`, `endereco`, `telefone`, `email`, `rg`, `nome_mae`, `senha`, `situacao`) VALUES
(35, '034.325.347-01', 'Lucas Gabriel Pexito de Oliveira', 'Rua Doutor Batista Acioly, Rio Largo, Centro, 294', '82 98718-0470', 'lucasgabrielpdoliveira@gmail.com', '3651746-1', 'Izabel Cristina Barros Peixoto', '202cb962ac59075b964b07152d234b70', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `zonas`
--

CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `limite_ambulantes` int(11) NOT NULL,
  `quantidade_ambulantes` int(11) NOT NULL,
  `coordenadas` polygon NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `foto` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `ambulantes`
--
ALTER TABLE `ambulantes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `anexos`
--
ALTER TABLE `anexos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `boletos`
--
ALTER TABLE `boletos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `eventual`
--
ALTER TABLE `eventual`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `fiscais`
--
ALTER TABLE `fiscais`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `licencas`
--
ALTER TABLE `licencas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `status_boletos`
--
ALTER TABLE `status_boletos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `status_licencas`
--
ALTER TABLE `status_licencas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo_fiscal`
--
ALTER TABLE `tipo_fiscal`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `ambulantes`
--
ALTER TABLE `ambulantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `anexos`
--
ALTER TABLE `anexos`
  MODIFY `id` int(45) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `boletos`
--
ALTER TABLE `boletos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `eventual`
--
ALTER TABLE `eventual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fiscais`
--
ALTER TABLE `fiscais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `licencas`
--
ALTER TABLE `licencas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status_boletos`
--
ALTER TABLE `status_boletos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status_licencas`
--
ALTER TABLE `status_licencas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_fiscal`
--
ALTER TABLE `tipo_fiscal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
