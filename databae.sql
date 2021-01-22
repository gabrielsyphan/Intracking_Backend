-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Jan-2021 às 16:55
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
  `id_zona` int(11) DEFAULT NULL,
  `local_endereco` varchar(400) NOT NULL,
  `produto` int(11) NOT NULL,
  `atendimento_dias` datetime NOT NULL,
  `atendimento_hora_inicio` datetime NOT NULL,
  `atendimento_hora_fim` datetime NOT NULL,
  `relato_atividade` varchar(150) DEFAULT NULL,
  `area_equipamento` varchar(20) NOT NULL,
  `tipo_equipamento` varchar(25) NOT NULL,
  `latitude` varchar(25) NOT NULL,
  `longitude` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `ambulantes`
--

INSERT INTO `ambulantes` (`id`, `id_licenca`, `id_zona`, `local_endereco`, `produto`, `atendimento_dias`, `atendimento_hora_inicio`, `atendimento_hora_fim`, `relato_atividade`, `area_equipamento`, `tipo_equipamento`, `latitude`, `longitude`) VALUES
(12, 16, NULL, 'Avenida Celeste Bezerra, Levada, Maceió, Região Geográfica Imediata de Maceió, Região Geográfica Intermediária de Maceió, Alagoas, Região Nordeste, 57017-040, Brasil', 37, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'aaaaaaa', '10 x 5', 'Barraca', '-9.65819407125508', '-35.746684154570005');

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
(31, 'userImage.png', '0', 36),
(32, 'identityImage.png', '0', 36),
(33, 'proofAddress.png', '0', 36),
(34, 'userImage.png', '1', 10),
(35, 'cnpjRegistration.png', '1', 10),
(36, 'proofAddress.png', '1', 10),
(37, 'socialContract.png', '1', 10),
(38, 'businessLicense.png', '1', 10),
(39, 'otherDocument.png', '1', 10),
(40, 'equipmentImage.jpg', '1', 11),
(41, 'equipmentImage.jpg', '1', 12),
(42, 'equipmentImage.jpg', '1', 13),
(43, 'equipmentImage.jpg', '1', 14),
(44, 'equipmentImage.jpg', '1', 15),
(45, 'equipmentImage.jpg', '1', 16);

-- --------------------------------------------------------

--
-- Estrutura da tabela `boletos`
--

CREATE TABLE `boletos` (
  `id` int(11) NOT NULL,
  `id_licenca` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `cod_referencia` text DEFAULT NULL,
  `cod_pagamento` text DEFAULT NULL,
  `valor` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 3,
  `tipo` int(11) NOT NULL,
  `pagar_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `pago_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `boletos`
--

INSERT INTO `boletos` (`id`, `id_licenca`, `id_usuario`, `cod_referencia`, `cod_pagamento`, `valor`, `status`, `tipo`, `pagar_em`, `pago_em`) VALUES
(148, 16, 36, '15123', 'teste', 144, 3, 1, '2021-01-25 03:00:00', '2021-01-22 14:11:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `endereco` text NOT NULL,
  `numero` int(11) NOT NULL,
  `bairro` text NOT NULL,
  `cidade` text NOT NULL,
  `cep` text NOT NULL,
  `produto` text NOT NULL,
  `relato_atividade` text NOT NULL,
  `cnpj` text NOT NULL,
  `cmc` text NOT NULL,
  `nome_fantasia` text NOT NULL,
  `outro_produto` text DEFAULT NULL,
  `como_vende` text NOT NULL,
  `quantidade_equipamentos` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `empresas`
--

INSERT INTO `empresas` (`id`, `endereco`, `numero`, `bairro`, `cidade`, `cep`, `produto`, `relato_atividade`, `cnpj`, `cmc`, `nome_fantasia`, `outro_produto`, `como_vende`, `quantidade_equipamentos`) VALUES
(23, 'Rua Doutor Batista Acioly', 294, 'Centro', 'Rio Largo', '57100000', '137', 'aaaaaaaaaaaaaaaaaaaaaaaaa', '11.111.111/1111-11', '1111111111', 'More Media', 'Cabelo', '', 14);

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
  `cmc` varchar(50) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `data_inicio` varchar(45) DEFAULT NULL,
  `data_fim` varchar(45) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `licencas`
--

INSERT INTO `licencas` (`id`, `cmc`, `tipo`, `data_inicio`, `data_fim`, `status`, `id_usuario`) VALUES
(10, '1111111111', '1', '2021-01-21', '2021-01-24', 1, 36),
(16, '9900027911', '0', '2021-01-22', '2021-01-25', 0, 36);

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
-- Estrutura da tabela `tipo_licenca`
--

CREATE TABLE `tipo_licenca` (
  `id` int(11) NOT NULL,
  `nome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tipo_licenca`
--

INSERT INTO `tipo_licenca` (`id`, `nome`) VALUES
(0, 'Ambulante'),
(1, 'Empresa'),
(2, 'Eventual'),
(3, 'Publicidade'),
(4, 'Uso de solo');

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
  `senha` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `cpf`, `nome`, `endereco`, `telefone`, `email`, `rg`, `nome_mae`, `senha`) VALUES
(36, '034.325.347-01', 'Lucas Gabriel Peixoto de Oliveira', 'Rua Doutor Batista Acioly, Rio Largo, Centro, 294', '82 98718-0470', 'lucasgabrielpdoliveira@gmail.com', '3651746-1', 'Izabel Cristina Barros Peixoto', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Estrutura da tabela `zonas`
--

CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `coordenadas` polygon DEFAULT NULL,
  `detalhes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `foto` longblob DEFAULT NULL,
  `limite_ambulantes` int(11) DEFAULT NULL,
  `quantidade_ambulantes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `zonas`
--

INSERT INTO `zonas` (`id`, `coordenadas`, `detalhes`, `nome`, `foto`, `limite_ambulantes`, `quantidade_ambulantes`) VALUES
(62, 0x000000000103000000010000000c0000000a0020e456de41c01af6622c504e23c0efff9f0858de41c07b0dbcae534e23c0000080cd58de41c0146d9ab15c4e23c02200b01658de41c0109262b5644e23c0c1ffdf0557de41c0bee37516684e23c0e7ff2fa954de41c097c4456e694e23c0390090b452de41c0c23cd772644e23c0cdff3f1452de41c0ba72516c5f4e23c00200d00b52de41c0cb61412f594e23c0e5ffcf4653de41c0f9c78fb0514e23c01a00f07e55de41c0bf5c4db34e4e23c00a0020e456de41c01af6622c504e23c0, 'Area cadastrada', 'Praça Radialista Edval Vieira de Oliveira', '', 1, 1),
(63, 0x000000000103000000010000000b000000deff1f3b00de41c0978182b09e5223c0320070effbdd41c000c1f8b7ae5223c02300e062fadd41c0712290d5b25223c03200b0fdf8dd41c0db6fbeebb25223c0d5ffdfbff7dd41c0a46bf731af5223c01c00c03bf6dd41c0bca899bb9e5223c0f2ffbffdf7dd41c06c86dfda7c5223c0c6ff1f4ffbdd41c01845bb213c5223c0080070b1fddd41c0e1748f173b5223c0c6ff8fcf0ade41c0a5375bf8685223c0deff1f3b00de41c0978182b09e5223c0, 'Area cadastrada', 'Praça José Bagio Filho', '', 2, 1),
(64, 0x0000000001030000000100000009000000f3ff9f04a9dc41c07e2810ab5a4123c0e5ff1fa2a9dc41c0cfd07fc2654123c02200808ea8dc41c032ee21bd6b4123c0cbff5fbea7dc41c038678ac56e4123c024004086a5dc41c0def33a69724123c01e00a0ba89dc41c0f5429c89734123c01100c0c888dc41c0457e5c81194123c0c0ffbfe09ddc41c0227d0827404123c0f3ff9f04a9dc41c07e2810ab5a4123c0, 'Area cadastrada', 'Praça Nossa Senhora de Fátima', '', 3, 2),
(65, 0x0000000001030000000100000007000000ceff7f1214de41c0c6c04b6ed43d23c0bbff3feb11de41c0cc305285ec3d23c01500c0230ede41c06c8f8c44ea3d23c0d1ffff3a02de41c0840a5cbce03d23c0110080baf3dd41c0e968483bcf3d23c0f9ffbf9dfddd41c05adf5efc983d23c0ceff7f1214de41c0c6c04b6ed43d23c0, 'Area cadastrada', 'Praça José Barreto', '', 2, 2),
(66, 0x000000000103000000010000000b000000120000c7b2dd41c08ccc5228613e23c01d008056b2dd41c024cfb2406b3e23c02b0000b9b1dd41c0c4d7695e6f3e23c03b00d053b0dd41c0c0bbfd0e713e23c0290000ecabdd41c036e52c6c6c3e23c0ccffdf7ba5dd41c08390cad9613e23c02c00502aa5dd41c04acbdfb15c3e23c023005084a5dd41c066606353563e23c0e2ffefc4a6dd41c0ea75a7884c3e23c0100080ddb2dd41c0d9a23629603e23c0120000c7b2dd41c08ccc5228613e23c0, 'Area cadastrada', 'Terminal Rotary', '', 3, 3),
(67, 0x000000000103000000010000000a000000e2ffffaf52dc41c06c2d8206b74923c01600e0124cdc41c09bb21f46ae4923c0420080c148dc41c050b57b8aac4923c01c00c09d3bdc41c022b71c82a94923c0cafffffc41dc41c0532d2bbb9b4923c01400c0be47dc41c06aa494448b4923c0140080b04adc41c049a61817804923c03c00c0f64bdc41c00e38e167954923c01d00003a50dc41c0d01b4d74ac4923c0e2ffffaf52dc41c06c2d8206b74923c0, 'Area cadastrada', 'Praça da Macaxeira', '', 1, 1),
(68, 0x0000000001030000000100000005000000d7ffff399cdb41c008734e34114623c0420020299cdb41c0c3a6d8fe3f4623c03300807892db41c075f0a9e83f4623c01100a06792db41c05703f107114623c0d7ffff399cdb41c008734e34114623c0, 'Area cadastrada', 'Praça Sandoval Caju', '', 3, 3),
(69, 0x000000000103000000010000000e000000f3ff6f4024dd41c0f83432e72c5723c0e9ff1f6225dd41c06a8f10004e5723c0bbff1f5127dd41c003613222655723c0d2ff5f4b29dd41c0f15a525d6d5723c0420020782ddd41c07b85cec4755723c0caffff4822dd41c0538c9bd28a5723c0dcff3fa918dd41c0775c9103925723c00c00c0a316dd41c0385e4e68915723c00e00601415dd41c0986285968f5723c01400005813dd41c0e142c5dc8b5723c0fdffbf5d11dd41c051d19c89855723c02d0080660cdd41c035edf07b405723c0c0ff1f6315dd41c0bddd0263375723c0f3ff6f4024dd41c0f83432e72c5723c0, 'Area cadastrada', 'Praça Marcílio Dias', '', 1, 1),
(70, 0x00000000010300000001000000050000002300c08c6adc41c0e4babc5d085823c0c2ff3f7b74dc41c009dbe7c54b5823c0e8ffff106adc41c01735fcdb635823c04200006660dc41c042f3dee0225823c02300c08c6adc41c0e4babc5d085823c0, 'Area cadastrada', 'Praça Dois Leões', '', 2, 2),
(71, 0x000000000103000000010000001200000033007067dedd41c0ab8ea2d9c93123c0210060f1dcdd41c0d7ab005dcc3123c0deffbf56dbdd41c060c113eecb3123c0eeff5f46d3dd41c056c68cc9c23123c01e0000c8cfdd41c0d79f9666c13123c01600f0fdc7dd41c077ffc94dc43123c0eaffafe4c6dd41c0dd64c102c13123c00c0090f5c6dd41c0e7ac3935ba3123c00100801fcbdd41c033a1a275a33123c0c9ffcf9ad2dd41c0bcafd1028e3123c02c00904ed7dd41c0726815a5863123c00800c05be5dd41c0348439ec743123c0bbff5f23e7dd41c0348439ec743123c00d008020e8dd41c0abbbf69b773123c02c00e047e8dd41c090bd41e57c3123c019008099e7dd41c062700737853123c04300207bdedd41c0b8418654c93123c033007067dedd41c0ab8ea2d9c93123c0, 'Area cadastrada', 'Praça Central ', '', 1, 0),
(72, 0x0000000001030000000100000005000000f0ffff54ecd941c06d34e08cb34723c0f4ffff27ecd941c0ab78d178ee4723c0350080a7ddd941c0c8b37164ec4723c02c008001ded941c03a85553eb44723c0f0ffff54ecd941c06d34e08cb34723c0, 'Area cadastrada', 'Praça Nossa Senhora da Rosa Mística', '', 6, 6),
(73, 0x0000000001030000000100000007000000dcff7f06d7db41c08527487a134e23c02200c089b9db41c0699c7584144e23c0c1ffffa2badb41c0aff0071b0e4e23c02600c05cb9db41c0710fede9b14d23c0d6ffbfb3bcdb41c0e0832154ab4d23c0230040fbd6db41c069b21a70124e23c0dcff7f06d7db41c08527487a134e23c0, 'Area cadastrada', 'Praça da Bíblia', '', 4, 4),
(74, 0x000000000103000000010000000e000000e1ff7f05d9da41c048bec46c485323c0d1ff7fb9d9da41c0fa86bdce4a5323c0cafffffcd9da41c04c459c194e5323c0eeff7f7ed8da41c0da49a6195a5323c0faff1f9bd0da41c09ca4dfc3925323c0280020acceda41c0424bf23c945323c03a00d0c5c8da41c080f2628c865323c0c1ffffc8c2da41c02b9083107c5323c00e00e01dbbda41c0a1205ded715323c03c00e02eb9da41c093a5d9486f5323c0d3ff5fb0b7da41c0b30cba6e6a5323c00300f0cebdda41c03134c546295323c0deff3f9cbeda41c04053541b285323c0e1ff7f05d9da41c048bec46c485323c0, 'Area cadastrada', 'Praça Lyons ', '', 4, 4),
(75, 0x000000000103000000010000000c000000020000557be141c02a10f692ca3c23c01500006e80e141c0637bcceae43c23c00c00000175e141c0892f8c6e0c3d23c0c2ffff6966e141c08662408c293d23c02c00000b5ce141c00f6cea433c3d23c01b0000f850e141c0f313fc0f513d23c04200006949e141c024f8b464623d23c03c0000c943e141c04152ad2d6e3d23c0ccffff8d42e141c0d17cacfe573d23c0440000f760e141c0ffee0d86173d23c0230000266ee141c0634800f1f63c23c0020000557be141c02a10f692ca3c23c0, 'Area cadastrada', 'Mirante Senador Rui Palmeira', '', 3, 3),
(76, 0x0000000001030000000100000008000000eeffff74bacd41c0efe0d2d7890e23c00100008ebfcd41c0bb9c4e26890e23c0ffffff87c5cd41c0efe0d2d7890e23c0130000a1cacd41c084fbf4638f0e23c0e9ffff62cccd41c0914b9ba1950e23c006000028cbcd41c0af3af07fa30e23c0d5ffff82bbcd41c0b1bfe71ca20e23c0eeffff74bacd41c0efe0d2d7890e23c0, 'Area cadastrada', 'Mirante Floriano Peixoto ', '', 2, 0),
(77, 0x0000000001030000000100000008000000c2ffff566ad241c0637d24d1981f23c0cfffff9675d241c06cca93fad01f23c00e0000f472d241c04915130fd31f23c0d5ffff6f6fd241c08ad892c0d31f23c02d0000bf6bd241c0a7f41483cd1f23c0010000b467d241c03ff19acaba1f23c0fdffffe067d241c0a6d49f26aa1f23c0c2ffff566ad241c0637d24d1981f23c0, 'Area cadastrada', 'Mirante da Sereia', '', 1, 0),
(78, 0x0000000001030000000100000005000000d0ffff70dddb41c07ef7d974014a23c0c7ffffcadddb41c03039c83a414a23c0d2ffffeeb9db41c08fa05629484a23c03c000090afdb41c072badd14094a23c0d0ffff70dddb41c07ef7d974014a23c0, 'Area cadastrada', 'Mirante do Jacintinho', '', 3, 2),
(79, 0x0000000001030000000100000006000000110000ae34de41c0a906d6b4425323c0c9ffffaa37de41c031c16303425323c0240000c739de41c0b3f8be544a5323c03500001339de41c041d970ba545323c0c9ffffaa37de41c0d75a1ee3585323c0110000ae34de41c0a906d6b4425323c0, 'Area cadastrada', 'Mirante do Cortiço', '', 1, 0),
(80, 0x00000000010300000001000000050000003000001780de41c00a520847a95023c0feffff3282de41c0b9962410785023c0040000d387de41c0ed9d63877b5023c0320000e485de41c0981847beac5023c03000001780de41c00a520847a95023c0, 'Area cadastrada', 'Mirante Santa Terezinha', '', 2, 1),
(81, 0x00000000010300000001000000080000002000004363de41c0aa121f24f15123c0e9ffff8b65de41c02bd539c1ef5123c0c3ffff2067de41c0d2e3483bfc5123c0e9ffff8b65de41c02374ec400e5223c02d0000fb50de41c0ca60bbf69a5223c0f9ffff494dde41c0042d94df8f5223c03000008f62de41c0d2e3483bfc5123c02000004363de41c0aa121f24f15123c0, 'Area cadastrada', 'Mirante Ambrósio de Lira', '', 2, 1),
(82, 0x0000000001030000000100000006000000400000aff7dd41c0b6fb80cc0d5423c0d9ffffe001de41c0b662e54e355423c02d00005dfedd41c02fb67954475423c0d7ffff13fcdd41c02fb67954475423c02000001df3dd41c040c58ac0265423c0400000aff7dd41c0b6fb80cc0d5423c0, 'Area cadastrada', 'Mirante Dom Ranulpho', '', 1, 1),
(83, 0x00000000010300000001000000080000000d00007cd0dd41c0d6644eb1765323c01c0000c2d5dd41c038051ff1855323c0caffff4bd3dd41c025ed0ace935323c01e0000c8cfdd41c0bbc580bc9a5323c0c8ffff7ecddd41c0bbc580bc9a5323c0f2ffffbccbdd41c025ed0ace935323c0e5ffff43ccdd41c0f638cc198a5323c00d00007cd0dd41c0d6644eb1765323c0, 'Area cadastrada', 'Mirante São Gonçalo (Rosalvo Ribeiro)', '', 3, 1),
(89, 0x00000000010300000001000000090000002a00006456d941c0e2da0d39b65123c02f0000ee26d941c0bc5a8bb5055323c0d2ffff3d13d941c009facf56905323c01f00001a0ad941c0395f84f9a05323c023000026fed841c0353407918d5323c0c8ffff9119d941c0fade9707d15223c0fbffff032fd941c0e10346ac2a5223c0dbfffff947d941c09a7e28d6b45123c02a00006456d941c0e2da0d39b65123c0, 'Proximo ao banco do Brasil', 'Faixa de areia ponta verde', NULL, 10, 4),
(90, 0x0000000001030000000100000005000000c4ffffc1cad941c099a078a3794c23c0c4ffffc1cad941c0c7a17351654d23c0d8ffff0389d941c0825ba48b624d23c0450000faa1d941c0311fa3a3ff4b23c0c4ffffc1cad941c099a078a3794c23c0, 'Zona cadastrada', 'Praia da jatiuca', NULL, 10, 1);

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
-- Índices para tabela `tipo_licenca`
--
ALTER TABLE `tipo_licenca`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `anexos`
--
ALTER TABLE `anexos`
  MODIFY `id` int(45) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `boletos`
--
ALTER TABLE `boletos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
-- AUTO_INCREMENT de tabela `tipo_licenca`
--
ALTER TABLE `tipo_licenca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
