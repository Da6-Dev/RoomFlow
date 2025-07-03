-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/07/2025 às 12:52
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `roomflow`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acomodacoes`
--

CREATE TABLE `acomodacoes` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('disponivel','ocupado','manutencao') DEFAULT 'disponivel',
  `capacidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `minimo_noites` int(11) NOT NULL,
  `camas_casal` int(11) NOT NULL,
  `camas_solteiro` int(11) NOT NULL,
  `hora_checkin` time NOT NULL,
  `hora_checkout` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `acomodacoes`
--

INSERT INTO `acomodacoes` (`id`, `tipo`, `numero`, `descricao`, `status`, `capacidade`, `preco`, `minimo_noites`, `camas_casal`, `camas_solteiro`, `hora_checkin`, `hora_checkout`) VALUES
(15, 'Charrua(Bus)', '1', 'O Charrua é uma das grandes novidades da pousada. A seguir alguns detalhes que acompanham esta acomodação especial: Roupas de cama; Roupas de banho; Banheira de hidromassagem; Cozinha básica; Taças; Churrasqueira; Televisão; Soundbar Jbl Cinema; Cafeteira com cápsula; Rede de descanso; Área com vista para o mar; Deck externo com fogueira; Acompanha uma cesta de café da manhã.', 'disponivel', 2, 490.00, 2, 1, 0, '14:00:00', '10:00:00'),
(16, 'Domo', '1', 'O Domo é a grande novidade da pousada. Uma acomodação totalmente diferenciada construída nos padrões arquitetônicos dos domos geodésicos modernos.', 'disponivel', 3, 590.00, 2, 1, 1, '14:00:00', '10:00:00'),
(17, 'Suíte com cozinha', '1', 'Com ampla vista para o mar, esta acomodação possui cama de casal, cama extra, ar-condicionado e TV, além de possuir também uma pequena cozinha com utensílios básicos e banheiro. Na sua parte externa possui deck com churrasqueira. A acomodação é ideal para duas pessoas, podendo comportar até três.', 'disponivel', 3, 390.00, 2, 1, 1, '14:00:00', '10:00:00'),
(18, 'Chalé família', '1', 'Esta acomodação possui dois quartos, um dos quartos com cama de casal e TV e o outro com cama de casal e uma de solteiro. Ambos os quartos são equipados com ar-condicionado. Possui também banheiro, cozinha com utensílios básicos e churrasqueira. Na sua parte externa possui sacada com ampla vista para o mar. A acomodação é ideal para até cinco pessoas.', 'disponivel', 5, 590.00, 2, 2, 1, '14:00:00', '10:00:00'),
(19, 'Cabana', '1', 'Esta acomodação está localizada em uma área mais reservada da pousada. Possui cama de casal, uma cama de solteiro, cama extra, ar-condicionado, TV, cozinha com utensílios básicos e banheiro. Na área externa possui varanda e deck com churrasqueira, tendo ampla vista para o mar. A acomodação é ideal para três pessoas, podendo comportar até quatro.', 'disponivel', 3, 490.00, 2, 1, 1, '14:00:00', '10:00:00'),
(20, 'Estacionamento para overlanders', '1', 'A pousada conta também com um espaço plano com vista para o mar, destinado a estacionamento de overlanders, tendo disponível para uso ponto de água e luz. Possui também banheiro e churrasqueira para uso comum destes viajantes.', 'disponivel', 4, 100.00, 2, 0, 0, '14:00:00', '10:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `amenidades`
--

CREATE TABLE `amenidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `amenidades`
--

INSERT INTO `amenidades` (`id`, `nome`) VALUES
(13, 'Ar-Condicionado'),
(14, 'Televisao'),
(15, 'Ducha'),
(16, 'Cozinha'),
(17, 'Wi-fi'),
(18, 'Frigobar'),
(19, 'Banheira'),
(20, 'Toalhas');

-- --------------------------------------------------------

--
-- Estrutura para tabela `amenidades_acomodacoes`
--

CREATE TABLE `amenidades_acomodacoes` (
  `id` int(11) NOT NULL,
  `id_amenidades` int(11) NOT NULL,
  `id_acomodacoes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `amenidades_acomodacoes`
--

INSERT INTO `amenidades_acomodacoes` (`id`, `id_amenidades`, `id_acomodacoes`) VALUES
(512, 13, 18),
(513, 16, 18),
(514, 14, 18),
(515, 20, 18),
(516, 17, 18),
(517, 13, 15),
(518, 19, 15),
(519, 16, 15),
(520, 15, 15),
(521, 18, 15),
(522, 14, 15),
(523, 20, 15),
(524, 17, 15),
(525, 13, 16),
(526, 16, 16),
(527, 15, 16),
(528, 18, 16),
(529, 14, 16),
(530, 20, 16),
(531, 17, 16),
(532, 13, 17),
(533, 16, 17),
(534, 14, 17),
(535, 20, 17),
(536, 17, 17),
(541, 15, 20),
(542, 17, 20),
(587, 13, 19),
(588, 16, 19),
(589, 14, 19),
(590, 20, 19),
(591, 17, 19);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `cargo` enum('admin','recepcao','limpeza') NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_reservas`
--

CREATE TABLE `historico_reservas` (
  `id` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_hospede` int(11) NOT NULL,
  `id_acomodacao` int(11) NOT NULL,
  `data_checkin` date NOT NULL,
  `data_checkout` date NOT NULL,
  `status` enum('cancelada','finalizada') DEFAULT 'finalizada',
  `valor_total` decimal(10,2) DEFAULT NULL,
  `metodo_pagamento` enum('cartao-debito','cartao-credito','dinheiro','pix') DEFAULT 'cartao-debito',
  `observacoes` text DEFAULT NULL,
  `data_reserva` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_reservas`
--

INSERT INTO `historico_reservas` (`id`, `id_reserva`, `detalhes`, `data_registro`, `id_hospede`, `id_acomodacao`, `data_checkin`, `data_checkout`, `status`, `valor_total`, `metodo_pagamento`, `observacoes`, `data_reserva`) VALUES
(11, 0, 'Reserva arquivada automaticamente por expiração.', '2025-06-23 19:41:20', 5, 16, '2025-06-01', '2025-06-02', 'finalizada', 590.00, 'dinheiro', 'tthrthtrhrh', '2025-06-09 01:56:33'),
(12, 16, 'Reserva arquivada automaticamente por expiração.', '2025-06-27 14:50:01', 5, 16, '2025-06-23', '2025-06-25', 'finalizada', 1180.00, 'pix', 'tthrthtrhrh', '2025-06-23 19:58:26'),
(13, 17, 'Reserva arquivada automaticamente por expiração.', '2025-06-27 14:50:01', 5, 17, '2025-06-25', '2025-06-26', 'finalizada', 390.00, 'dinheiro', 'tthrthtrhrh', '2025-06-23 19:58:40'),
(14, 18, 'Reserva arquivada automaticamente por expiração.', '2025-07-02 11:20:02', 5, 16, '2025-06-30', '2025-07-01', 'finalizada', 590.00, 'cartao-credito', 'tthrthtrhrh', '2025-06-23 19:58:57'),
(15, 19, 'Reserva arquivada automaticamente por expiração.', '2025-07-02 11:20:02', 5, 16, '2025-06-28', '2025-06-29', 'finalizada', 590.00, 'pix', 'Bosta liquida 12', '2025-06-27 15:18:21'),
(16, 20, 'Reserva arquivada automaticamente por expiração.', '2025-07-02 11:20:02', 5, 19, '2025-06-28', '2025-06-30', 'finalizada', 980.00, 'cartao-credito', 'Poste Liquido', '2025-06-27 15:49:43'),
(17, 21, 'Reserva arquivada automaticamente por expiração.', '2025-07-02 11:20:02', 5, 18, '2025-06-27', '2025-06-28', 'finalizada', 590.00, 'dinheiro', 'Bosta', '2025-06-27 16:42:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hospedes`
--

CREATE TABLE `hospedes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT 'default.png',
  `documento` varchar(50) NOT NULL,
  `rua` varchar(150) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `hospedes`
--

INSERT INTO `hospedes` (`id`, `nome`, `email`, `telefone`, `imagem`, `documento`, `rua`, `numero`, `cidade`, `estado`, `cep`, `data_nascimento`, `data_cadastro`, `active`) VALUES
(5, 'Davi Passos', 'davipassos213@gmail.com', '(35) 98405-9573', '6859c773c7b7d-my-notion-face-portrait.png', '17172386612', 'Rua José Cândido da Silva', '80', 'Itajubá', 'MG', '37504-314', '2007-02-08', '2025-05-21 00:05:54', 1),
(6, 'Daniela', 'daniela.pereira@email.com', '(35) 98405-4324', '685eb540c06e8-unnamed.jpg', '84526807087', 'Rua Barão do Rio Branco', '99', 'Itajubá', 'MG', '37505-000', '2000-05-05', '2025-06-23 20:19:35', 0),
(7, 'Reginaldo Rossi', 'gjgfgg213@gmail.com', '(34) 56789-0987', '685edf9974976-unnamed.jpg', '00032369603', 'Rua José Cândido da Silva', '80', 'Itajubá', 'MG', '37504-314', '2003-02-26', '2025-06-27 18:14:49', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens_acomodacoes`
--

CREATE TABLE `imagens_acomodacoes` (
  `id` int(11) NOT NULL,
  `acomodacao_id` int(11) DEFAULT NULL,
  `nome_arquivo` varchar(255) DEFAULT NULL,
  `caminho_arquivo` varchar(255) DEFAULT NULL,
  `capa_acomodacao` tinyint(1) NOT NULL DEFAULT 0,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `ordem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imagens_acomodacoes`
--

INSERT INTO `imagens_acomodacoes` (`id`, `acomodacao_id`, `nome_arquivo`, `caminho_arquivo`, `capa_acomodacao`, `data_upload`, `ordem`) VALUES
(21, 16, 'b87f83_427a0844705c415b85cf8d45e2f221b2~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f1930ba_b87f83_427a0844705c415b85cf8d45e2f221b2~mv2.jpeg', 0, '2025-05-20 22:28:01', 1),
(22, 16, 'b87f83_928e441d10b74b3ba45cf455e8c12b0e~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f193456_b87f83_928e441d10b74b3ba45cf455e8c12b0e~mv2.jpeg', 0, '2025-05-20 22:28:01', 2),
(23, 16, 'b87f83_2195bb2abcea4970834f97af39aee9b3~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f1937ce_b87f83_2195bb2abcea4970834f97af39aee9b3~mv2.jpeg', 0, '2025-05-20 22:28:01', 3),
(24, 16, 'b87f83_3984ca8f5d97472ebe0f78082100ec3a~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f193b11_b87f83_3984ca8f5d97472ebe0f78082100ec3a~mv2.jpeg', 0, '2025-05-20 22:28:01', 4),
(25, 16, 'b87f83_b585e69752e946568b5bafe0a957c720~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f193e46_b87f83_b585e69752e946568b5bafe0a957c720~mv2.jpeg', 0, '2025-05-20 22:28:01', 5),
(26, 16, 'b87f83_d2d7bd83c35c436d8715a34567485109~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f194117_b87f83_d2d7bd83c35c436d8715a34567485109~mv2.jpeg', 0, '2025-05-20 22:28:01', 6),
(27, 16, 'b87f83_d5005769bdab47a688c07915ffe7b4bc~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f19441b_b87f83_d5005769bdab47a688c07915ffe7b4bc~mv2.jpeg', 0, '2025-05-20 22:28:01', 7),
(28, 16, 'b87f83_e89ecfdd2aa84fa0812f6c8789225f20~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f19471d_b87f83_e89ecfdd2aa84fa0812f6c8789225f20~mv2.jpeg', 0, '2025-05-20 22:28:01', 8),
(29, 16, 'b87f83_0db328063a8c4b4ea1bb3dff437e8e46~mv2.jpeg', 'Public/uploads/acomodacoes/682d09c707c0e_b87f83_0db328063a8c4b4ea1bb3dff437e8e46~mv2.jpeg', 1, '2025-05-20 23:01:27', 0),
(36, 15, 'b87f83_5a54d8612da145a99bb18d7b3a22ff73~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c960389_b87f83_5a54d8612da145a99bb18d7b3a22ff73~mv2.jpeg', 0, '2025-05-22 12:12:57', 1),
(37, 15, 'b87f83_9dbd4e58f38a4c59ad7d8dbeea534562~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c9606c4_b87f83_9dbd4e58f38a4c59ad7d8dbeea534562~mv2.jpeg', 0, '2025-05-22 12:12:57', 2),
(38, 15, 'b87f83_61cd9f30603c4c0782d0dd8d262c0fcb~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c96092c_b87f83_61cd9f30603c4c0782d0dd8d262c0fcb~mv2.jpeg', 0, '2025-05-22 12:12:57', 3),
(39, 15, 'b87f83_5256b82fef1a4bd1936c66a9e2acbb26~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c964155_b87f83_5256b82fef1a4bd1936c66a9e2acbb26~mv2.jpeg', 1, '2025-05-22 12:12:57', 0),
(40, 15, 'b87f83_5580c08771c841089ccc440a82c2f298~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c96458b_b87f83_5580c08771c841089ccc440a82c2f298~mv2.jpeg', 0, '2025-05-22 12:12:57', 4),
(41, 15, 'b87f83_af81b183079f4adfa6af570cb95eab09~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c964cef_b87f83_af81b183079f4adfa6af570cb95eab09~mv2.jpeg', 0, '2025-05-22 12:12:57', 5),
(42, 15, 'b87f83_c8f85aa203bb4a26a1dbe3633c00114b~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c965119_b87f83_c8f85aa203bb4a26a1dbe3633c00114b~mv2.jpeg', 0, '2025-05-22 12:12:57', 6),
(43, 15, 'b87f83_c72880f87ec948868f23310a25b1a518~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c9653f0_b87f83_c72880f87ec948868f23310a25b1a518~mv2.jpeg', 0, '2025-05-22 12:12:57', 7),
(44, 15, 'b87f83_f07179544559435c967a19c767edd577~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c9656e0_b87f83_f07179544559435c967a19c767edd577~mv2.jpeg', 0, '2025-05-22 12:12:57', 8),
(45, 15, 'b87f83_5a42d6c11e7143e18cbeb41cbab190c0~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c965974_b87f83_5a42d6c11e7143e18cbeb41cbab190c0~mv2.jpeg', 0, '2025-05-22 12:12:57', 9),
(46, 17, 'b87f83_ffb843fc4c484315aa4e4ab80ce909b1~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4a12a_b87f83_ffb843fc4c484315aa4e4ab80ce909b1~mv2.jpg', 0, '2025-05-26 16:29:49', 1),
(47, 17, 'b87f83_f07179544559435c967a19c767edd577~mv2.jpeg', 'Public/uploads/acomodacoes/683496fd4a678_b87f83_f07179544559435c967a19c767edd577~mv2.jpeg', 0, '2025-05-26 16:29:49', 2),
(48, 17, 'b87f83_f7253080d75c4ca6aeb1a93f144a05bc~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4a945_b87f83_f7253080d75c4ca6aeb1a93f144a05bc~mv2.jpg', 0, '2025-05-26 16:29:49', 3),
(49, 17, 'b87f83_de7918ffcf3947b6ba9b21ff2c56c40c~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4abf1_b87f83_de7918ffcf3947b6ba9b21ff2c56c40c~mv2.jpg', 0, '2025-05-26 16:29:49', 4),
(50, 17, 'b87f83_cb768f930e7f460a88236e43bbb1258c~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4aea0_b87f83_cb768f930e7f460a88236e43bbb1258c~mv2.jpg', 0, '2025-05-26 16:29:49', 5),
(51, 17, 'b87f83_bfc66e6435f34c23bfd60e2fccb3d499~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4b137_b87f83_bfc66e6435f34c23bfd60e2fccb3d499~mv2.jpg', 1, '2025-05-26 16:29:49', 0),
(52, 17, 'b87f83_b6732012d563483b800222bd3e8b3165~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4b409_b87f83_b6732012d563483b800222bd3e8b3165~mv2.jpg', 0, '2025-05-26 16:29:49', 6),
(53, 17, 'b87f83_9874647537df4460b22481153ec53e64~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4b6bd_b87f83_9874647537df4460b22481153ec53e64~mv2.jpg', 0, '2025-05-26 16:29:49', 7),
(54, 17, 'b87f83_372231fb9efe40798c040f28d773e461~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4baa2_b87f83_372231fb9efe40798c040f28d773e461~mv2.jpg', 0, '2025-05-26 16:29:49', 8),
(55, 17, 'b87f83_3b4acd8d82e342469093e71fb29a3632~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4bd4b_b87f83_3b4acd8d82e342469093e71fb29a3632~mv2.jpg', 0, '2025-05-26 16:29:49', 9),
(56, 17, 'b87f83_0ba13b9dfa2c42058f578180254fbed8~mv2.jpg', 'Public/uploads/acomodacoes/683496fd4bfd2_b87f83_0ba13b9dfa2c42058f578180254fbed8~mv2.jpg', 0, '2025-05-26 16:29:49', 10),
(57, 18, 'b87f83_fd189730414e46d39003c5767b995e9b~mv2.jpg', 'Public/uploads/acomodacoes/683497144a63f_b87f83_fd189730414e46d39003c5767b995e9b~mv2.jpg', 0, '2025-05-26 16:30:12', 1),
(58, 18, 'b87f83_f06e8eb7ad634e22bd69badcc538be73~mv2.jpg', 'Public/uploads/acomodacoes/683497144a9ff_b87f83_f06e8eb7ad634e22bd69badcc538be73~mv2.jpg', 0, '2025-05-26 16:30:12', 2),
(59, 18, 'b87f83_d943676e56f24781b4aad20256b75eef~mv2.jpg', 'Public/uploads/acomodacoes/683497144ad08_b87f83_d943676e56f24781b4aad20256b75eef~mv2.jpg', 0, '2025-05-26 16:30:12', 3),
(60, 18, 'b87f83_d3ae7c6f22ea4579bad3396eea56224f~mv2.jpg', 'Public/uploads/acomodacoes/683497144afe5_b87f83_d3ae7c6f22ea4579bad3396eea56224f~mv2.jpg', 0, '2025-05-26 16:30:12', 4),
(61, 18, 'b87f83_aaea0665e31b4b6aaa520cf6c66e761a~mv2.jpg', 'Public/uploads/acomodacoes/683497144b2d8_b87f83_aaea0665e31b4b6aaa520cf6c66e761a~mv2.jpg', 1, '2025-05-26 16:30:12', 0),
(62, 18, 'b87f83_8761455c1c024a8d8ebc1be8b1480cb8~mv2.jpg', 'Public/uploads/acomodacoes/683497144b5bd_b87f83_8761455c1c024a8d8ebc1be8b1480cb8~mv2.jpg', 0, '2025-05-26 16:30:12', 5),
(63, 18, 'b87f83_217039df4a4049f19adf2e9d25c8d044~mv2.jpg', 'Public/uploads/acomodacoes/683497144ba35_b87f83_217039df4a4049f19adf2e9d25c8d044~mv2.jpg', 0, '2025-05-26 16:30:12', 6),
(64, 18, 'b87f83_0195aaa684b6473888723b3f0dd4ef58~mv2.jpg', 'Public/uploads/acomodacoes/683497144bd4d_b87f83_0195aaa684b6473888723b3f0dd4ef58~mv2.jpg', 0, '2025-05-26 16:30:12', 7),
(65, 18, 'b87f83_8d589652307341d888588070b458de81~mv2.jpg', 'Public/uploads/acomodacoes/683497144bffe_b87f83_8d589652307341d888588070b458de81~mv2.jpg', 0, '2025-05-26 16:30:12', 8),
(66, 18, 'b87f83_8af61408b80d4101a4979143825ff3e3~mv2.jpg', 'Public/uploads/acomodacoes/683497144c387_b87f83_8af61408b80d4101a4979143825ff3e3~mv2.jpg', 0, '2025-05-26 16:30:12', 9),
(67, 19, 'b87f83_cddbecc47620434aba0b38cdf9a47577~mv2.jpg', 'Public/uploads/acomodacoes/68349721c270e_b87f83_cddbecc47620434aba0b38cdf9a47577~mv2.jpg', 0, '2025-05-26 16:30:25', 1),
(68, 19, 'b87f83_b77cefb65c4a44c2a44b265faad48fca~mv2.jpg', 'Public/uploads/acomodacoes/68349721c2a42_b87f83_b77cefb65c4a44c2a44b265faad48fca~mv2.jpg', 0, '2025-05-26 16:30:25', 2),
(69, 19, 'b87f83_b17e2c8c314b431285a94647be0d0b17~mv2.jpg', 'Public/uploads/acomodacoes/68349721c2da4_b87f83_b17e2c8c314b431285a94647be0d0b17~mv2.jpg', 0, '2025-05-26 16:30:25', 3),
(70, 19, 'b87f83_aa9428b24cc74f5ab33e6b9ab8792361~mv2.jpg', 'Public/uploads/acomodacoes/68349721c3047_b87f83_aa9428b24cc74f5ab33e6b9ab8792361~mv2.jpg', 0, '2025-05-26 16:30:25', 4),
(71, 19, 'b87f83_5905c982218e43a482df805768f753a0~mv2.jpg', 'Public/uploads/acomodacoes/68349721c33bc_b87f83_5905c982218e43a482df805768f753a0~mv2.jpg', 0, '2025-05-26 16:30:25', 5),
(72, 19, 'b87f83_760d26da720349d383ddf9d888fc180c~mv2.jpg', 'Public/uploads/acomodacoes/68349721c3759_b87f83_760d26da720349d383ddf9d888fc180c~mv2.jpg', 0, '2025-05-26 16:30:25', 6),
(73, 19, 'b87f83_90c38d9ab2b1451f9f8500e1d3b8fc61~mv2.jpg', 'Public/uploads/acomodacoes/68349721c3c19_b87f83_90c38d9ab2b1451f9f8500e1d3b8fc61~mv2.jpg', 0, '2025-05-26 16:30:25', 7),
(74, 19, 'b87f83_23a56936773e4f7f812d0543c078138c~mv2.jpg', 'Public/uploads/acomodacoes/68349721c49d7_b87f83_23a56936773e4f7f812d0543c078138c~mv2.jpg', 1, '2025-05-26 16:30:25', 0),
(75, 19, 'b87f83_15d714ef677d4aeeb5ae94de940bd96e~mv2.jpg', 'Public/uploads/acomodacoes/68349721c4d39_b87f83_15d714ef677d4aeeb5ae94de940bd96e~mv2.jpg', 0, '2025-05-26 16:30:25', 8),
(76, 19, 'b87f83_1f34bed210534eb2a8b788773ee8cbdf~mv2.jpg', 'Public/uploads/acomodacoes/68349721c4f96_b87f83_1f34bed210534eb2a8b788773ee8cbdf~mv2.jpg', 0, '2025-05-26 16:30:25', 9),
(77, 20, 'b87f83_f78b450dd08c4b0388b57674d817bc41~mv2.png', 'Public/uploads/acomodacoes/6834972f724b3_b87f83_f78b450dd08c4b0388b57674d817bc41~mv2.png', 1, '2025-05-26 16:30:39', 0),
(78, 20, 'b87f83_f4b318355c704575a4a6917c1a2f7401~mv2.jpg', 'Public/uploads/acomodacoes/6834972f727d2_b87f83_f4b318355c704575a4a6917c1a2f7401~mv2.jpg', 0, '2025-05-26 16:30:39', 1),
(79, 20, 'b87f83_a5851df51b1c4a338516426d8cb0c0fd~mv2.png', 'Public/uploads/acomodacoes/6834972f72abf_b87f83_a5851df51b1c4a338516426d8cb0c0fd~mv2.png', 0, '2025-05-26 16:30:39', 2),
(80, 20, 'b87f83_476126075cd8451d80d41fd83781be0d~mv2.jpg', 'Public/uploads/acomodacoes/6834972f72cde_b87f83_476126075cd8451d80d41fd83781be0d~mv2.jpg', 0, '2025-05-26 16:30:39', 3),
(81, 20, 'b87f83_543f73cd4409455a83b0751e78794057~mv2.jpg', 'Public/uploads/acomodacoes/6834972f72ec1_b87f83_543f73cd4409455a83b0751e78794057~mv2.jpg', 0, '2025-05-26 16:30:39', 4),
(82, 20, 'b87f83_89da331062774e919f434b54a7272a8f~mv2.png', 'Public/uploads/acomodacoes/6834972f7319a_b87f83_89da331062774e919f434b54a7272a8f~mv2.png', 0, '2025-05-26 16:30:39', 5),
(83, 20, 'b87f83_8c981a4259314d1e806103098ea0cd98~mv2 (1).png', 'Public/uploads/acomodacoes/6834972f733df_b87f83_8c981a4259314d1e806103098ea0cd98~mv2 (1).png', 0, '2025-05-26 16:30:39', 6),
(84, 20, 'b87f83_1af509ade7ad46cc86b69b10fe2cd6c5~mv2 (1).jpg', 'Public/uploads/acomodacoes/6834972f735f1_b87f83_1af509ade7ad46cc86b69b10fe2cd6c5~mv2 (1).jpg', 0, '2025-05-26 16:30:39', 7),
(85, 20, 'b87f83_8c981a4259314d1e806103098ea0cd98~mv2.png', 'Public/uploads/acomodacoes/6834972f737f6_b87f83_8c981a4259314d1e806103098ea0cd98~mv2.png', 0, '2025-05-26 16:30:39', 8),
(86, 20, 'b87f83_6d5124b7605f4c16aa9abac10dd14425~mv2.png', 'Public/uploads/acomodacoes/6834972f739e1_b87f83_6d5124b7605f4c16aa9abac10dd14425~mv2.png', 0, '2025-05-26 16:30:39', 9),
(87, 20, 'b87f83_2e6022b3296a410886bc4641e30d6a7e~mv2.png', 'Public/uploads/acomodacoes/6834972f73be8_b87f83_2e6022b3296a410886bc4641e30d6a7e~mv2.png', 0, '2025-05-26 16:30:39', 10),
(88, 20, 'b87f83_1af509ade7ad46cc86b69b10fe2cd6c5~mv2.jpg', 'Public/uploads/acomodacoes/6834972f73dbd_b87f83_1af509ade7ad46cc86b69b10fe2cd6c5~mv2.jpg', 0, '2025-05-26 16:30:39', 11);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_acesso`
--

CREATE TABLE `logs_acesso` (
  `id` int(11) NOT NULL,
  `id_funcionario` int(11) NOT NULL,
  `data_acesso` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_acesso` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `manutencao`
--

CREATE TABLE `manutencao` (
  `id` int(11) NOT NULL,
  `id_acomodacao` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `status` enum('pendente','em andamento','conclu?da') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `id_funcionario` int(11) DEFAULT NULL,
  `mensagem` text NOT NULL,
  `tipo` enum('reserva','pagamento','manuten??o','outro') DEFAULT 'reserva',
  `status` enum('pendente','enviado') DEFAULT 'pendente',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `preferencias_hospedes`
--

CREATE TABLE `preferencias_hospedes` (
  `id` int(11) NOT NULL,
  `id_hospede` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `preferencias_hospedes`
--

INSERT INTO `preferencias_hospedes` (`id`, `id_hospede`, `descricao`) VALUES
(11, 6, 'Intolerante a Lactose'),
(12, 7, 'Bosta'),
(22, 5, 'Intolerante a Lactose');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_hospede` int(11) NOT NULL,
  `id_acomodacao` int(11) NOT NULL,
  `data_checkin` date NOT NULL,
  `data_checkout` date NOT NULL,
  `status` enum('pendente','confirmada','cancelada','check-in realizado','check-out realizado') DEFAULT 'pendente',
  `valor_total` decimal(10,2) DEFAULT NULL,
  `metodo_pagamento` enum('cartao-debito','cartao-credito','dinheiro','pix') DEFAULT 'cartao-debito',
  `observacoes` text DEFAULT NULL,
  `data_reserva` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `id_hospede`, `id_acomodacao`, `data_checkin`, `data_checkout`, `status`, `valor_total`, `metodo_pagamento`, `observacoes`, `data_reserva`) VALUES
(22, 5, 19, '2025-06-30', '2025-07-03', 'pendente', 1470.00, 'dinheiro', 'oioioi', '2025-06-27 18:50:29');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acomodacoes`
--
ALTER TABLE `acomodacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `amenidades`
--
ALTER TABLE `amenidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `amenidades_acomodacoes`
--
ALTER TABLE `amenidades_acomodacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_amenidades` (`id_amenidades`),
  ADD KEY `id_acomodacoes` (`id_acomodacoes`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `historico_reservas`
--
ALTER TABLE `historico_reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_data_registro` (`data_registro`);

--
-- Índices de tabela `hospedes`
--
ALTER TABLE `hospedes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `imagens_acomodacoes`
--
ALTER TABLE `imagens_acomodacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acomodacao_id` (`acomodacao_id`);

--
-- Índices de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_funcionario` (`id_funcionario`);

--
-- Índices de tabela `manutencao`
--
ALTER TABLE `manutencao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_acomodacao` (`id_acomodacao`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_reserva` (`id_reserva`),
  ADD KEY `id_funcionario` (`id_funcionario`);

--
-- Índices de tabela `preferencias_hospedes`
--
ALTER TABLE `preferencias_hospedes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hospede` (`id_hospede`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hospede` (`id_hospede`),
  ADD KEY `id_acomodacao` (`id_acomodacao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acomodacoes`
--
ALTER TABLE `acomodacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `amenidades`
--
ALTER TABLE `amenidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `amenidades_acomodacoes`
--
ALTER TABLE `amenidades_acomodacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=592;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_reservas`
--
ALTER TABLE `historico_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `hospedes`
--
ALTER TABLE `hospedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `imagens_acomodacoes`
--
ALTER TABLE `imagens_acomodacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `manutencao`
--
ALTER TABLE `manutencao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `preferencias_hospedes`
--
ALTER TABLE `preferencias_hospedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `amenidades_acomodacoes`
--
ALTER TABLE `amenidades_acomodacoes`
  ADD CONSTRAINT `amenidades_acomodacoes_ibfk_1` FOREIGN KEY (`id_amenidades`) REFERENCES `amenidades` (`id`),
  ADD CONSTRAINT `amenidades_acomodacoes_ibfk_2` FOREIGN KEY (`id_acomodacoes`) REFERENCES `acomodacoes` (`id`);

--
-- Restrições para tabelas `imagens_acomodacoes`
--
ALTER TABLE `imagens_acomodacoes`
  ADD CONSTRAINT `imagens_acomodacoes_ibfk_1` FOREIGN KEY (`acomodacao_id`) REFERENCES `acomodacoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD CONSTRAINT `logs_acesso_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `manutencao`
--
ALTER TABLE `manutencao`
  ADD CONSTRAINT `manutencao_ibfk_1` FOREIGN KEY (`id_acomodacao`) REFERENCES `acomodacoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_2` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `preferencias_hospedes`
--
ALTER TABLE `preferencias_hospedes`
  ADD CONSTRAINT `preferencias_hospedes_ibfk_1` FOREIGN KEY (`id_hospede`) REFERENCES `hospedes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_hospede`) REFERENCES `hospedes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_acomodacao`) REFERENCES `acomodacoes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
