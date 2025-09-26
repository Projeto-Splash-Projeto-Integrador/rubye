-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/09/2025 às 07:22
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
-- Banco de dados: `rubye_db`
--

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
(1, 'Outono', 'col_68d60d96b0a4d.png'),
(2, 'Primavera', 'col_68d61946e0e08.jpg');

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

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `status`, `data_pedido`) VALUES
(1, 1, 1000.00, 'Entregue', '2025-09-25 02:59:25'),
(2, 1, 100.00, 'Entregue', '2025-09-25 03:57:33'),
(3, 1, 200.00, 'Entregue', '2025-09-26 03:02:43'),
(4, 4, 2000.00, 'Entregue', '2025-09-26 04:28:00');

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

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 2, 1, 1000.00),
(2, 2, 4, 1, 100.00),
(3, 3, 8, 2, 100.00),
(4, 4, 9, 2, 1000.00);

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

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `estoque`, `categoria_id`, `status`) VALUES
(2, 'Theylor Antunes', 'sabao', 1000.00, '68d6201190631.png', 11, 1, 'ativo'),
(3, 'sabao', 'teste', 100.00, '68d4bb55797c9.png', 12, 3, 'ativo'),
(4, 'Theylor Antunes', 'sabao', 100.00, '68d4bcf17d989.png', 99, 3, 'ativo'),
(5, 'sabao', 'ASDASDASD', 15.50, '68d4bdd960e29.png', 24, 1, 'ativo'),
(6, 'sabao', 'asdasdas', 12.00, '68d4bf2dc102c.png', 12, 3, 'ativo'),
(7, 'Camiseta ARGUSº', 'A melhor oversized que  você vai encontrar.', 259.99, '68d4c1340fa0b.webp', 50, 1, 'ativo'),
(8, 'Theylor Antunes', 'bapoijsdpoias', 100.00, '68d6001ec03b0.png', 8, 2, 'ativo'),
(9, 'asdasdas', 'asdadasd', 1000.00, '68d6013feb339.png', 121, 2, 'ativo'),
(10, 'Camiseta ARGUSº', 'Camiseta daora pra carai fi, compra', 259.99, '68d60db517138.png', 50, 1, 'ativo'),
(11, 'Moletom sabnonete', 'asdasdasd', 100.00, '68d61936a4e7b.jpg', 12, 4, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_colecao`
--

CREATE TABLE `produto_colecao` (
  `produto_id` int(11) NOT NULL,
  `colecao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto_colecao`
--

INSERT INTO `produto_colecao` (`produto_id`, `colecao_id`) VALUES
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1);

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
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `role`, `data_cadastro`) VALUES
(1, 'Admin', 'admin@rubye.com', '$2y$10$5iCTxAEnZqwx3P54J2hi/.VhVVYVs8IwkKTSU3s9pzcNoChHc6f7C', 'admin', '2025-09-24 17:33:42'),
(2, 'Theylor Antunes', 'theylorantunes@gmail.com', '$2y$10$p/RDCM9ETGx3bo9vfq2gpOyx3s0HUaHOwqNguGCQfLuOtmYAzKpme', 'admin', '2025-09-24 19:58:30'),
(3, 'Theylor', 'theylorantuntescruz@gmail.com', '$2y$10$3h.U8B2jYJd/R0yEwLJGVeEplqjD1/uNIX/BEPIhzuFkF6Jb85f/.', 'admin', '2025-09-24 20:09:10'),
(4, 'Regiane Dias', 'theylor@gmail.com', '$2y$10$kqnbxh1nm2zYsXPi/KfMfuaQ3SnRTYpPUGDk2IZslVA5luiv3gxHK', 'cliente', '2025-09-26 04:27:14');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_unico` (`nome`);

--
-- Índices de tabela `colecoes`
--
ALTER TABLE `colecoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedido` (`pedido_id`),
  ADD KEY `fk_produto` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`categoria_id`);

--
-- Índices de tabela `produto_colecao`
--
ALTER TABLE `produto_colecao`
  ADD PRIMARY KEY (`produto_id`,`colecao_id`),
  ADD KEY `fk_colecao` (`colecao_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unico` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `colecoes`
--
ALTER TABLE `colecoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `fk_pedido_itens_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_itens_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `produto_colecao`
--
ALTER TABLE `produto_colecao`
  ADD CONSTRAINT `fk_colecao` FOREIGN KEY (`colecao_id`) REFERENCES `colecoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
