-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/08/2025 às 23:07
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `amenidades`
--

CREATE TABLE `amenidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `amenidades_acomodacoes`
--

CREATE TABLE `amenidades_acomodacoes` (
  `id` int(11) NOT NULL,
  `id_amenidades` int(11) NOT NULL,
  `id_acomodacoes` int(11) NOT NULL
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `preferencias_hospedes`
--

CREATE TABLE `preferencias_hospedes` (
  `id` int(11) NOT NULL,
  `id_hospede` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `amenidades`
--
ALTER TABLE `amenidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `amenidades_acomodacoes`
--
ALTER TABLE `amenidades_acomodacoes`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imagens_acomodacoes`
--
ALTER TABLE `imagens_acomodacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `preferencias_hospedes`
--
ALTER TABLE `preferencias_hospedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
