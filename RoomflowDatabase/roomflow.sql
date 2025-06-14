-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/05/2025 às 13:05
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
(13, 'Ar Condicionado'),
(14, 'Televisao'),
(15, 'Ducha'),
(16, 'Cozinha'),
(17, 'Wifi'),
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
(70, 13, 17),
(71, 14, 17),
(72, 16, 17),
(73, 17, 17),
(74, 20, 17),
(75, 13, 18),
(76, 14, 18),
(77, 16, 18),
(78, 17, 18),
(79, 20, 18),
(80, 13, 19),
(81, 14, 19),
(82, 16, 19),
(83, 17, 19),
(84, 20, 19),
(85, 15, 20),
(86, 17, 20),
(274, 13, 16),
(275, 14, 16),
(276, 15, 16),
(277, 16, 16),
(278, 17, 16),
(279, 18, 16),
(280, 20, 16),
(289, 13, 15),
(290, 14, 15),
(291, 15, 15),
(292, 16, 15),
(293, 17, 15),
(294, 18, 15),
(295, 19, 15),
(296, 20, 15);

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
  `id_funcionario` int(11) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `hospedes`
--

CREATE TABLE `hospedes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
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

INSERT INTO `hospedes` (`id`, `nome`, `email`, `telefone`, `documento`, `rua`, `numero`, `cidade`, `estado`, `cep`, `data_nascimento`, `data_cadastro`, `active`) VALUES
(5, 'Davi Passos', 'davipassos213@gmail.com', '(35)98405-9573', '17172386612', 'José Cândido da Silva', '80', 'Itajubá', 'MG', '37504314', '2007-02-08', '2025-05-21 00:05:54', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens_acomodacoes`
--

CREATE TABLE `imagens_acomodacoes` (
  `id` int(11) NOT NULL,
  `acomodacao_id` int(11) DEFAULT NULL,
  `nome_arquivo` varchar(255) DEFAULT NULL,
  `caminho_arquivo` varchar(255) DEFAULT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `capa_acomodacao` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imagens_acomodacoes`
--

INSERT INTO `imagens_acomodacoes` (`id`, `acomodacao_id`, `nome_arquivo`, `caminho_arquivo`, `data_upload`, `capa_acomodacao`) VALUES
(21, 16, 'b87f83_427a0844705c415b85cf8d45e2f221b2~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f1930ba_b87f83_427a0844705c415b85cf8d45e2f221b2~mv2.jpeg', '2025-05-20 22:28:01', 0),
(22, 16, 'b87f83_928e441d10b74b3ba45cf455e8c12b0e~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f193456_b87f83_928e441d10b74b3ba45cf455e8c12b0e~mv2.jpeg', '2025-05-20 22:28:01', 0),
(23, 16, 'b87f83_2195bb2abcea4970834f97af39aee9b3~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f1937ce_b87f83_2195bb2abcea4970834f97af39aee9b3~mv2.jpeg', '2025-05-20 22:28:01', 0),
(24, 16, 'b87f83_3984ca8f5d97472ebe0f78082100ec3a~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f193b11_b87f83_3984ca8f5d97472ebe0f78082100ec3a~mv2.jpeg', '2025-05-20 22:28:01', 0),
(25, 16, 'b87f83_b585e69752e946568b5bafe0a957c720~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f193e46_b87f83_b585e69752e946568b5bafe0a957c720~mv2.jpeg', '2025-05-20 22:28:01', 0),
(26, 16, 'b87f83_d2d7bd83c35c436d8715a34567485109~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f194117_b87f83_d2d7bd83c35c436d8715a34567485109~mv2.jpeg', '2025-05-20 22:28:01', 0),
(27, 16, 'b87f83_d5005769bdab47a688c07915ffe7b4bc~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f19441b_b87f83_d5005769bdab47a688c07915ffe7b4bc~mv2.jpeg', '2025-05-20 22:28:01', 0),
(28, 16, 'b87f83_e89ecfdd2aa84fa0812f6c8789225f20~mv2.jpeg', 'Public/uploads/acomodacoes/682d01f19471d_b87f83_e89ecfdd2aa84fa0812f6c8789225f20~mv2.jpeg', '2025-05-20 22:28:01', 0),
(29, 16, 'b87f83_0db328063a8c4b4ea1bb3dff437e8e46~mv2.jpeg', 'Public/uploads/acomodacoes/682d09c707c0e_b87f83_0db328063a8c4b4ea1bb3dff437e8e46~mv2.jpeg', '2025-05-20 23:01:27', 1),
(36, 15, 'b87f83_5a54d8612da145a99bb18d7b3a22ff73~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c960389_b87f83_5a54d8612da145a99bb18d7b3a22ff73~mv2.jpeg', '2025-05-22 12:12:57', 1),
(37, 15, 'b87f83_9dbd4e58f38a4c59ad7d8dbeea534562~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c9606c4_b87f83_9dbd4e58f38a4c59ad7d8dbeea534562~mv2.jpeg', '2025-05-22 12:12:57', 0),
(38, 15, 'b87f83_61cd9f30603c4c0782d0dd8d262c0fcb~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c96092c_b87f83_61cd9f30603c4c0782d0dd8d262c0fcb~mv2.jpeg', '2025-05-22 12:12:57', 0),
(39, 15, 'b87f83_5256b82fef1a4bd1936c66a9e2acbb26~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c964155_b87f83_5256b82fef1a4bd1936c66a9e2acbb26~mv2.jpeg', '2025-05-22 12:12:57', 0),
(40, 15, 'b87f83_5580c08771c841089ccc440a82c2f298~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c96458b_b87f83_5580c08771c841089ccc440a82c2f298~mv2.jpeg', '2025-05-22 12:12:57', 0),
(41, 15, 'b87f83_af81b183079f4adfa6af570cb95eab09~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c964cef_b87f83_af81b183079f4adfa6af570cb95eab09~mv2.jpeg', '2025-05-22 12:12:57', 0),
(42, 15, 'b87f83_c8f85aa203bb4a26a1dbe3633c00114b~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c965119_b87f83_c8f85aa203bb4a26a1dbe3633c00114b~mv2.jpeg', '2025-05-22 12:12:57', 0),
(43, 15, 'b87f83_c72880f87ec948868f23310a25b1a518~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c9653f0_b87f83_c72880f87ec948868f23310a25b1a518~mv2.jpeg', '2025-05-22 12:12:57', 0),
(44, 15, 'b87f83_f07179544559435c967a19c767edd577~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c9656e0_b87f83_f07179544559435c967a19c767edd577~mv2.jpeg', '2025-05-22 12:12:57', 0),
(45, 15, 'b87f83_5a42d6c11e7143e18cbeb41cbab190c0~mv2.jpeg', 'Public/uploads/acomodacoes/682f14c965974_b87f83_5a42d6c11e7143e18cbeb41cbab190c0~mv2.jpeg', '2025-05-22 12:12:57', 0);

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
(9, 5, 'Intolerante a Lactose');

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
  ADD KEY `id_reserva` (`id_reserva`),
  ADD KEY `id_funcionario` (`id_funcionario`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `amenidades`
--
ALTER TABLE `amenidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `amenidades_acomodacoes`
--
ALTER TABLE `amenidades_acomodacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_reservas`
--
ALTER TABLE `historico_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `hospedes`
--
ALTER TABLE `hospedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `imagens_acomodacoes`
--
ALTER TABLE `imagens_acomodacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Restrições para tabelas `historico_reservas`
--
ALTER TABLE `historico_reservas`
  ADD CONSTRAINT `historico_reservas_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_reservas_ibfk_2` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

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
