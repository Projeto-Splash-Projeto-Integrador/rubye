-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/09/2025 às 21:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30
CREATE DATABASE IF NOT EXISTS `rubye_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rubye_db`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rubye_db`
--
CREATE DATABASE IF NOT EXISTS `rubye_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rubye_db`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(3, 'Acessórios'),
(2, 'Calças'),
(1, 'Camisetas'),
(4, 'Moletom');

-- --------------------------------------------------------

--
-- Estrutura para tabela `colecoes`
--

CREATE TABLE `colecoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `colecoes`
--

INSERT INTO `colecoes` (`id`, `nome`, `imagem`) VALUES
(1, 'Outono', 'col_68d68f8e0d172.jpeg'),
(2, 'Primavera', 'col_68d68fa956344.jpeg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('Pedido Recebido','Pagamento em Análise','Pagamento Confirmado','Em Separação','Enviado','Em rota de entrega','Entregue','Pedido Cancelado') NOT NULL DEFAULT 'Pedido Recebido',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `categoria_id` int(11) DEFAULT NULL,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_colecao`
--

CREATE TABLE `produto_colecao` (
  `produto_id` int(11) NOT NULL,
  `colecao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_imagens`
--

CREATE TABLE `produto_imagens` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `caminho_imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `role` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
-- (Senha para admin@rubye.com é 'admin123')
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `role`, `data_cadastro`) VALUES
(1, 'Admin', 'admin@rubye.com', '$2y$10$5iCTxAEnZqwx3P54J2hi/.VhVVYVs8IwkKTSU3s9pzcNoChHc6f7C', 'admin', '2025-09-24 17:33:42');

--
-- Índices para tabelas despejadas
--

ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_unico` (`nome`);

ALTER TABLE `colecoes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedido` (`pedido_id`),
  ADD KEY `fk_produto` (`produto_id`);

ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`categoria_id`);

ALTER TABLE `produto_colecao`
  ADD PRIMARY KEY (`produto_id`,`colecao_id`),
  ADD KEY `fk_colecao` (`colecao_id`);

ALTER TABLE `produto_imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produto_imagens_produto` (`produto_id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unico` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `colecoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `produto_imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `fk_pedido_itens_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_itens_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON UPDATE CASCADE;

ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `produto_colecao`
  ADD CONSTRAINT `fk_colecao` FOREIGN KEY (`colecao_id`) REFERENCES `colecoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

ALTER TABLE `produto_imagens`
  ADD CONSTRAINT `fk_produto_imagens_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;