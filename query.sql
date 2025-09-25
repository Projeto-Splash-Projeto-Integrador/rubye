-- Criando o banco de dados (opcional, se ele ainda não existir)
CREATE DATABASE IF NOT EXISTS rubye_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Selecionando o banco de dados para usar
USE rubye_db;

-- Tabela: categorias
-- Armazena as categorias dos produtos.
CREATE TABLE `categorias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_unico` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: usuarios
-- Armazena informações dos clientes e administradores.
CREATE TABLE `usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(255) NOT NULL, -- Essencial para armazenar o hash da senha
  `role` ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
  `data_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unico` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: produtos (VERSÃO ATUALIZADA)
-- Armazena todos os produtos da loja.
CREATE TABLE `produtos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `descricao` TEXT,
  `preco` DECIMAL(10, 2) NOT NULL,
  `imagem` VARCHAR(255) NOT NULL, -- Caminho para o arquivo da imagem
  `estoque` INT NOT NULL DEFAULT 0,
  `categoria_id` INT,
  `status` ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo', -- Coluna para "apagar de forma lógica"
  PRIMARY KEY (`id`),
  KEY `fk_categoria` (`categoria_id`),
  CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: pedidos
-- Armazena o cabeçalho de cada pedido realizado.
CREATE TABLE `pedidos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario_id` INT NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Pendente',
  `data_pedido` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_usuario` (`usuario_id`),
  CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: pedido_itens
-- Armazena os itens específicos de cada pedido.
CREATE TABLE `pedido_itens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pedido_id` INT NOT NULL,
  `produto_id` INT NOT NULL,
  `quantidade` INT NOT NULL,
  `preco_unitario` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pedido` (`pedido_id`),
  KEY `fk_produto` (`produto_id`),
  CONSTRAINT `fk_pedido_itens_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pedido_itens_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE -- RESTRICT para não deixar apagar um produto que está em um pedido
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserindo um usuário administrador padrão para testes
-- A senha é "admin123"
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `role`) VALUES
('Admin', 'admin@rubye.com', '$2y$10$3h.U8B2jYJd/R0yEwLJGVeEplqjD1/uNIX/BEPIhzuFkF6Jb85f/.', 'admin');

-- Inserindo algumas categorias para teste
INSERT INTO `categorias` (`nome`) VALUES ('Camisetas'), ('Calças'), ('Acessórios');