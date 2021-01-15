-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Jan-2021 às 15:45
-- Versão do servidor: 10.4.14-MariaDB
-- versão do PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `teste`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ambulantes`
--

CREATE TABLE `ambulantes` (
  `licencas_idlicencas` int(11) NOT NULL,
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
(1, 'userImage.jpeg', '0', 26),
(2, 'identityImage.jpeg', '0', 26),
(3, 'userImage.jpeg', '0', 27),
(4, 'identityImage.jpeg', '0', 27),
(5, 'userImage.jpeg', '0', 28),
(6, 'identityImage.jpeg', '0', 28);

-- --------------------------------------------------------

--
-- Estrutura da tabela `boletos`
--

CREATE TABLE `boletos` (
  `idboletos` int(11) NOT NULL,
  `licencas_idlicencas` int(11) DEFAULT NULL,
  `licencas_zonas_idzonas` int(11) DEFAULT NULL,
  `status-boletos_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

CREATE TABLE `empresas` (
  `licencas_idlicencas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventos`
--

CREATE TABLE `eventos` (
  `ideventos` int(11) NOT NULL,
  `data-inicio` datetime DEFAULT NULL,
  `data-fim` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventual`
--

CREATE TABLE `eventual` (
  `licencas_idlicencas` int(11) NOT NULL,
  `eventos_ideventos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fiscais`
--

CREATE TABLE `fiscais` (
  `matricula` varchar(15) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `cpf` varchar(45) DEFAULT NULL,
  `tipo-fiscal_id` int(11) NOT NULL,
  `senha_temporaria` varchar(60) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `licencas`
--

CREATE TABLE `licencas` (
  `idlicencas` int(11) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `geopoint` varchar(45) DEFAULT NULL,
  `zonas_idzonas` int(11) DEFAULT NULL,
  `data-inicio` varchar(45) DEFAULT NULL,
  `data-fim` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status-licencas_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `idnotificacoes` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `hora` datetime NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `boletos_idboletos` int(11) DEFAULT NULL,
  `fiscais_id` int(11) NOT NULL,
  `fiscais_tipo-fiscal_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ocorrencias`
--

CREATE TABLE `ocorrencias` (
  `idocorrencias` int(11) NOT NULL,
  `data` varchar(45) DEFAULT NULL,
  `hora` varchar(45) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `regiao` varchar(45) DEFAULT NULL,
  `foto` longblob DEFAULT NULL,
  `fiscais_id` int(11) NOT NULL,
  `fiscais_tipo-fiscal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status-boletos`
--

CREATE TABLE `status-boletos` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status-licencas`
--

CREATE TABLE `status-licencas` (
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
-- Estrutura da tabela `uso de solo`
--

CREATE TABLE `uso de solo` (
  `licencas_idlicencas` int(11) NOT NULL,
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
  `senha` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `cpf`, `nome`, `endereco`, `telefone`, `email`, `rg`, `nome_mae`, `senha`) VALUES
(28, '034.325.347-01', 'Lucas Gabriel Peixoto de Oliveira', 'Rua Doutor Batista Aciole, Rio Largo, Centro, 294', '82 98718-0470', 'lucasgabrielpdoliveira@gmail.com', '3651746-1', 'Izabel Cristina Barros Peixoto', '123');

-- --------------------------------------------------------

--
-- Estrutura da tabela `zonas`
--

CREATE TABLE `zonas` (
  `idzonas` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `limite_ambulantes` int(11) NOT NULL,
  `quantidade_ambulantes` int(11) NOT NULL,
  `coordenadas` polygon NOT NULL,
  `detalhes` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `ambulantes`
--
ALTER TABLE `ambulantes`
  ADD PRIMARY KEY (`licencas_idlicencas`);

--
-- Índices para tabela `anexos`
--
ALTER TABLE `anexos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `boletos`
--
ALTER TABLE `boletos`
  ADD PRIMARY KEY (`idboletos`,`status-boletos_id`),
  ADD KEY `fk_boletos_licencas1` (`licencas_idlicencas`),
  ADD KEY `fk_boletos_status-boletos1` (`status-boletos_id`);

--
-- Índices para tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`licencas_idlicencas`);

--
-- Índices para tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`ideventos`);

--
-- Índices para tabela `eventual`
--
ALTER TABLE `eventual`
  ADD PRIMARY KEY (`licencas_idlicencas`,`eventos_ideventos`),
  ADD KEY `fk_eventual_eventos1` (`eventos_ideventos`);

--
-- Índices para tabela `fiscais`
--
ALTER TABLE `fiscais`
  ADD PRIMARY KEY (`id`,`tipo-fiscal_id`),
  ADD KEY `fk_fiscais_tipo-fiscal1` (`tipo-fiscal_id`);

--
-- Índices para tabela `licencas`
--
ALTER TABLE `licencas`
  ADD PRIMARY KEY (`idlicencas`,`status-licencas_id`,`usuarios_id`),
  ADD KEY `fk_licencas_zonas1` (`zonas_idzonas`),
  ADD KEY `fk_licencas_status-licencas1` (`status-licencas_id`),
  ADD KEY `fk_licencas_usuarios1` (`usuarios_id`);

--
-- Índices para tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`idnotificacoes`,`fiscais_id`,`fiscais_tipo-fiscal_id`,`usuarios_id`),
  ADD KEY `fk_notificacoes_boletos1` (`boletos_idboletos`),
  ADD KEY `fk_notificacoes_fiscais1` (`fiscais_id`,`fiscais_tipo-fiscal_id`),
  ADD KEY `fk_notificacoes_usuarios1` (`usuarios_id`);

--
-- Índices para tabela `ocorrencias`
--
ALTER TABLE `ocorrencias`
  ADD PRIMARY KEY (`idocorrencias`,`fiscais_id`,`fiscais_tipo-fiscal_id`),
  ADD KEY `fk_ocorrencias_fiscais1` (`fiscais_id`,`fiscais_tipo-fiscal_id`);

--
-- Índices para tabela `status-boletos`
--
ALTER TABLE `status-boletos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `status-licencas`
--
ALTER TABLE `status-licencas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo_fiscal`
--
ALTER TABLE `tipo_fiscal`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `uso de solo`
--
ALTER TABLE `uso de solo`
  ADD PRIMARY KEY (`licencas_idlicencas`,`licencas_usuarios_cpf`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`idzonas`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `anexos`
--
ALTER TABLE `anexos`
  MODIFY `id` int(45) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `fiscais`
--
ALTER TABLE `fiscais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `licencas`
--
ALTER TABLE `licencas`
  MODIFY `idlicencas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status-licencas`
--
ALTER TABLE `status-licencas`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `ambulantes`
--
ALTER TABLE `ambulantes`
  ADD CONSTRAINT `fk_Ambulantes_licencas1` FOREIGN KEY (`licencas_idlicencas`) REFERENCES `licencas` (`idlicencas`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `boletos`
--
ALTER TABLE `boletos`
  ADD CONSTRAINT `fk_boletos_licencas1` FOREIGN KEY (`licencas_idlicencas`) REFERENCES `licencas` (`idlicencas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_boletos_status-boletos1` FOREIGN KEY (`status-boletos_id`) REFERENCES `status-boletos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `fk_empresas_licencas1` FOREIGN KEY (`licencas_idlicencas`) REFERENCES `licencas` (`idlicencas`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `eventual`
--
ALTER TABLE `eventual`
  ADD CONSTRAINT `fk_eventual_eventos1` FOREIGN KEY (`eventos_ideventos`) REFERENCES `eventos` (`ideventos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_eventual_licencas1` FOREIGN KEY (`licencas_idlicencas`) REFERENCES `licencas` (`idlicencas`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `fiscais`
--
ALTER TABLE `fiscais`
  ADD CONSTRAINT `fk_fiscais_tipo-fiscal1` FOREIGN KEY (`tipo-fiscal_id`) REFERENCES `tipo_fiscal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `licencas`
--
ALTER TABLE `licencas`
  ADD CONSTRAINT `fk_licencas_status-licencas1` FOREIGN KEY (`status-licencas_id`) REFERENCES `status-licencas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_licencas_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_licencas_zonas1` FOREIGN KEY (`zonas_idzonas`) REFERENCES `zonas` (`idzonas`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `fk_notificacoes_boletos1` FOREIGN KEY (`boletos_idboletos`) REFERENCES `boletos` (`idboletos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notificacoes_fiscais1` FOREIGN KEY (`fiscais_id`,`fiscais_tipo-fiscal_id`) REFERENCES `fiscais` (`id`, `tipo-fiscal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notificacoes_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `ocorrencias`
--
ALTER TABLE `ocorrencias`
  ADD CONSTRAINT `fk_ocorrencias_fiscais1` FOREIGN KEY (`fiscais_id`,`fiscais_tipo-fiscal_id`) REFERENCES `fiscais` (`id`, `tipo-fiscal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `uso de solo`
--
ALTER TABLE `uso de solo`
  ADD CONSTRAINT `fk_uso de solo_licencas1` FOREIGN KEY (`licencas_idlicencas`) REFERENCES `licencas` (`idlicencas`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
