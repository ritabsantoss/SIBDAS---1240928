-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- A despejar estrutura para tabela db1240928.Categorias
CREATE TABLE IF NOT EXISTS `Categorias` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idCategoria`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Componentes
CREATE TABLE IF NOT EXISTS `Componentes` (
  `idComponente` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `codigo_componente` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `nome_componente` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  `estado_componente` enum('Ativo','Inativo','Em manutenção') COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`idComponente`),
  UNIQUE KEY `codigo_componente` (`codigo_componente`),
  KEY `fkComponentesEquipamento` (`idEquipamento`),
  CONSTRAINT `fkComponentesEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Conteudos
CREATE TABLE IF NOT EXISTS `Conteudos` (
  `idConteudo` int NOT NULL AUTO_INCREMENT,
  `seccao` varchar(100) COLLATE utf8mb4_bin NOT NULL COMMENT 'hero, estatisticas, servicos, faq, contactos',
  `chave` varchar(100) COLLATE utf8mb4_bin NOT NULL COMMENT 'ex.: hero_titulo, estat_equipamentos, faq1_pergunta',
  `valor` text COLLATE utf8mb4_bin,
  `ordem` int DEFAULT NULL,
  PRIMARY KEY (`idConteudo`),
  UNIQUE KEY `Conteudos_index_2` (`seccao`,`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Contratos
CREATE TABLE IF NOT EXISTS `Contratos` (
  `idContrato` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idEntidade` int DEFAULT NULL COMMENT 'Entidade responsável',
  `codigo_contrato` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `tipo_contrato` enum('Contrato de Manutenção','Manutenção Preventiva','Contrato de Assistência Técnica') COLLATE utf8mb4_bin DEFAULT NULL,
  `periodicidade` enum('Mensal','Trimestral','Semestral','Anual','Bianual') COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro_contrato` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`idContrato`),
  UNIQUE KEY `codigo_contrato` (`codigo_contrato`),
  KEY `fkContratosEquipamento` (`idEquipamento`),
  KEY `fkContratosEntidade` (`idEntidade`),
  CONSTRAINT `fkContratosEntidade` FOREIGN KEY (`idEntidade`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE SET NULL,
  CONSTRAINT `fkContratosEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Documentos
CREATE TABLE IF NOT EXISTS `Documentos` (
  `idDocumento` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idFornecedor` int DEFAULT NULL COMMENT 'Opcional',
  `codigo_documento` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `tipo_documento` enum('Manual de Utilizador','Manual de Serviço','Certificado de Calibração','Fatura ou Guia de Aquisição','Declaração de Conformidade','Relatório Técnico') COLLATE utf8mb4_bin NOT NULL,
  `nome_documento` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `data_documento` date DEFAULT NULL,
  `validade` date DEFAULT NULL COMMENT 'Quando aplicável',
  `estado_documento` enum('Ativo','Prestes a Expirar','Expirado','Pendente','Anulado','Estendido','Não disponível') COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Nome/caminho do ficheiro',
  `observacoes` text COLLATE utf8mb4_bin,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idDocumento`),
  UNIQUE KEY `codigo_documento` (`codigo_documento`),
  KEY `fkDocumentosEquipamento` (`idEquipamento`),
  KEY `fkDocumentosFornecedor` (`idFornecedor`),
  CONSTRAINT `fkDocumentosEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE,
  CONSTRAINT `fkDocumentosFornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Equipamento_Fornecedor
CREATE TABLE IF NOT EXISTS `Equipamento_Fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `tipo_relacao` enum('Fabricante','Distribuidor ou fornecedor comercial','Empresa de assistência técnica','Fornecedor de consumíveis ou acessórios') COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Equipamento_Fornecedor_index_1` (`idEquipamento`,`idFornecedor`,`tipo_relacao`),
  KEY `fkEquipamento_Fornecedor_Fornecedor` (`idFornecedor`),
  CONSTRAINT `fkEquipamento_Fornecedor_Equipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE,
  CONSTRAINT `fkEquipamento_Fornecedor_Fornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Equipamentos
CREATE TABLE IF NOT EXISTS `Equipamentos` (
  `idEquipamento` int NOT NULL AUTO_INCREMENT,
  `codigo_interno` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `designacao` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  `idCategoria` int NOT NULL,
  `idLocalizacao` int NOT NULL,
  `marca` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `modelo` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `numero_serie` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `fabricante` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `ano_fabrico` int DEFAULT NULL,
  `custo` decimal(10,2) DEFAULT NULL,
  `tipo_entrada` enum('Compra','Doação','Aluguer','Empréstimo') COLLATE utf8mb4_bin DEFAULT NULL,
  `estado_atual` enum('Ativo','Em manutenção','Inativo','Em calibração','Em quarentena','Abatido') COLLATE utf8mb4_bin NOT NULL,
  `criticidade` enum('Baixa','Média','Alta','Suporte de vida') COLLATE utf8mb4_bin NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idEquipamento`),
  UNIQUE KEY `codigo_interno` (`codigo_interno`),
  UNIQUE KEY `Equipamentos_index_0` (`marca`,`modelo`,`numero_serie`),
  KEY `fkEquipamentosCategoria` (`idCategoria`),
  KEY `fkEquipamentosLocalizacao` (`idLocalizacao`),
  CONSTRAINT `fkEquipamentosCategoria` FOREIGN KEY (`idCategoria`) REFERENCES `Categorias` (`idCategoria`) ON DELETE RESTRICT,
  CONSTRAINT `fkEquipamentosLocalizacao` FOREIGN KEY (`idLocalizacao`) REFERENCES `Localizacoes` (`idLocalizacao`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Fornecedores
CREATE TABLE IF NOT EXISTS `Fornecedores` (
  `idFornecedor` int NOT NULL AUTO_INCREMENT,
  `nome_empresa` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `website` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `morada` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `pessoa_contacto` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `telefone_contacto` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idFornecedor`),
  UNIQUE KEY `nif` (`nif`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Garantias
CREATE TABLE IF NOT EXISTS `Garantias` (
  `idGarantia` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idEntidade` int DEFAULT NULL COMMENT 'Entidade responsável',
  `codigo_garantia` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `estado_garantia` enum('Ativa','Prestes a Expirar','Expirada','Pendente','Anulada','Estendida','Não disponível') COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro_garantia` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`idGarantia`),
  UNIQUE KEY `codigo_garantia` (`codigo_garantia`),
  KEY `fkGarantiasEquipamento` (`idEquipamento`),
  KEY `fkGarantiasEntidade` (`idEntidade`),
  CONSTRAINT `fkGarantiasEntidade` FOREIGN KEY (`idEntidade`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE SET NULL,
  CONSTRAINT `fkGarantiasEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Localizacoes
CREATE TABLE IF NOT EXISTS `Localizacoes` (
  `idLocalizacao` int NOT NULL AUTO_INCREMENT,
  `edificio` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `piso` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL,
  `idServico` int NOT NULL,
  `sala` varchar(80) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idLocalizacao`),
  KEY `fkLocalizacoesServico` (`idServico`),
  CONSTRAINT `fkLocalizacoesServico` FOREIGN KEY (`idServico`) REFERENCES `Servicos` (`idServico`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Servicos
CREATE TABLE IF NOT EXISTS `Servicos` (
  `idServico` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idServico`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1240928.Utilizadores
CREATE TABLE IF NOT EXISTS `Utilizadores` (
  `idUtilizador` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `perfil` enum('administrador','tecnico','profissional') COLLATE utf8mb4_bin NOT NULL DEFAULT 'profissional',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `genero` enum('M','F') COLLATE utf8mb4_bin DEFAULT NULL,
  `ativo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`idUtilizador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
