CREATE TABLE `Utilizadores` (
  `idUtilizador` int AUTO_INCREMENT,
  `nome` varchar(80) NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  CONSTRAINT `pkUtilizadores` PRIMARY KEY (`idUtilizador`)
);

CREATE TABLE `Categorias` (
  `idCategoria` int  AUTO_INCREMENT,
  `nome` varchar(80) UNIQUE NOT NULL,
  CONSTRAINT `pkCategorias` PRIMARY KEY (`idCategoria`)
);

CREATE TABLE `Servicos` (
  `idServico` int  AUTO_INCREMENT,
  `nome` varchar(80) UNIQUE NOT NULL,
  CONSTRAINT `pkServicos` PRIMARY KEY (`idServico`)
);

CREATE TABLE `Localizacoes` (
  `idLocalizacao` int AUTO_INCREMENT,
  `edificio` varchar(80),
  `piso` varchar(10),
  `idServico` int NOT NULL,
  `sala` varchar(80),
  `observacoes` text,
  CONSTRAINT `pkLocalizacoes` PRIMARY KEY (`idLocalizacao`)
);

CREATE TABLE `Fornecedores` (
  `idFornecedor` int AUTO_INCREMENT,
  `nome_empresa` varchar(80) NOT NULL,
  `nif` varchar(20) UNIQUE,
  `telefone` varchar(20),
  `email` varchar(100),
  `website` varchar(100),
  `morada` varchar(100),
  `pessoa_contacto` varchar(80),
  `telefone_contacto` varchar(20),
  `observacoes` text,
  CONSTRAINT `pkFornecedores` PRIMARY KEY (`idFornecedor`)
);

CREATE TABLE `Equipamentos` (
  `idEquipamento` int AUTO_INCREMENT,
  `codigo_interno` varchar(20) UNIQUE NOT NULL,
  `designacao` varchar(80) NOT NULL,
  `idCategoria` int NOT NULL,
  `idLocalizacao` int NOT NULL,
  `marca` varchar(80),
  `modelo` varchar(80),
  `numero_serie` varchar(50),
  `fabricante` varchar(80),
  `data_aquisicao` date,
  `ano_fabrico` int,
  `custo` decimal(10,2),
  `tipo_entrada` ENUM ('Compra', 'Doação', 'Aluguer', 'Empréstimo'),
  `estado_atual` ENUM ('Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido') NOT NULL,
  `criticidade` ENUM ('Baixa', 'Média', 'Alta', 'Suporte de vida') NOT NULL,
  `observacoes` text,
  CONSTRAINT `pkEquipamentos` PRIMARY KEY (`idEquipamento`)
);

CREATE TABLE `Componentes` (
  `idComponente` int AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `codigo_componente` varchar(20) UNIQUE NOT NULL,
  `nome_componente` varchar(80) NOT NULL,
  `estado_componente` ENUM ('Ativo', 'Inativo', 'Em manutenção'),
  CONSTRAINT `pkComponentes` PRIMARY KEY (`idComponente`)
);

CREATE TABLE `Equipamento_Fornecedor` (
  `id` int AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `tipo_relacao` ENUM ('Fabricante', 'Distribuidor ou fornecedor comercial', 'Empresa de assistência técnica', 'Fornecedor de consumíveis ou acessórios') NOT NULL,
  CONSTRAINT `pkEquipamento_Fornecedor` PRIMARY KEY (`id`)
);

CREATE TABLE `Documentos` (
  `idDocumento` int AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idFornecedor` int COMMENT 'Opcional',
  `codigo_documento` varchar(20) UNIQUE NOT NULL,
  `tipo_documento` ENUM ('Manual de Utilizador', 'Manual de Serviço', 'Certificado de Calibração', 'Fatura ou Guia de Aquisição', 'Declaração de Conformidade', 'Relatório Técnico') NOT NULL,
  `nome_documento` varchar(80),
  `data_documento` date,
  `validade` date COMMENT 'Quando aplicável',
  `estado_documento` ENUM ('Ativo', 'Prestes a Expirar', 'Expirado', 'Pendente', 'Anulado', 'Estendido', 'Não disponível'),
  `ficheiro` varchar(255) COMMENT 'Nome/caminho do ficheiro',
  `observacoes` text,
  CONSTRAINT `pkDocumentos` PRIMARY KEY (`idDocumento`)
);

CREATE TABLE `Garantias` (
  `idGarantia` int AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idEntidade` int COMMENT 'Entidade responsável',
  `codigo_garantia` varchar(20) UNIQUE NOT NULL,
  `data_inicio` date,
  `data_fim` date,
  `estado_garantia` ENUM ('Ativa', 'Prestes a Expirar', 'Expirada', 'Pendente', 'Anulada', 'Estendida', 'Não disponível'),
  `ficheiro_garantia` varchar(255),
  `observacoes` text,
  CONSTRAINT `pkGarantias` PRIMARY KEY (`idGarantia`)
);

CREATE TABLE `Contratos` (
  `idContrato` int AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `idEntidade` int COMMENT 'Entidade responsável',
  `codigo_contrato` varchar(20) UNIQUE NOT NULL,
  `tipo_contrato` ENUM ('Contrato de Manutenção', 'Manutenção Preventiva', 'Contrato de Assistência Técnica'),
  `periodicidade` ENUM ('Mensal', 'Trimestral', 'Semestral', 'Anual', 'Bianual'),
  `ficheiro_contrato` varchar(255),
  `observacoes` text,
  CONSTRAINT `pkContratos` PRIMARY KEY (`idContrato`)
);

CREATE TABLE `Conteudos` (
  `idConteudo` int AUTO_INCREMENT,
  `seccao` varchar(100) NOT NULL COMMENT 'hero, estatisticas, servicos, faq, contactos',
  `chave` varchar(100) NOT NULL COMMENT 'ex.: hero_titulo, estat_equipamentos, faq1_pergunta',
  `valor` text,
  `ordem` int,
  CONSTRAINT `pkConteudos` PRIMARY KEY (`idConteudo`)
);

CREATE UNIQUE INDEX `Equipamentos_index_0` ON `Equipamentos` (`marca`, `modelo`, `numero_serie`);

CREATE UNIQUE INDEX `Equipamento_Fornecedor_index_1` ON `Equipamento_Fornecedor` (`idEquipamento`, `idFornecedor`, `tipo_relacao`);

CREATE UNIQUE INDEX `Conteudos_index_2` ON `Conteudos` (`seccao`, `chave`);

ALTER TABLE `Equipamentos` ADD CONSTRAINT `fkEquipamentosCategoria` FOREIGN KEY (`idCategoria`) REFERENCES `Categorias` (`idCategoria`) ON DELETE RESTRICT;

ALTER TABLE `Equipamentos` ADD CONSTRAINT `fkEquipamentosLocalizacao` FOREIGN KEY (`idLocalizacao`) REFERENCES `Localizacoes` (`idLocalizacao`) ON DELETE RESTRICT;

ALTER TABLE `Componentes` ADD CONSTRAINT `fkComponentesEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE;

ALTER TABLE `Documentos` ADD CONSTRAINT `fkDocumentosEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE;

ALTER TABLE `Documentos` ADD CONSTRAINT `fkDocumentosFornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE SET NULL;

ALTER TABLE `Garantias` ADD CONSTRAINT `fkGarantiasEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE;

ALTER TABLE `Garantias` ADD CONSTRAINT `fkGarantiasEntidade` FOREIGN KEY (`idEntidade`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE SET NULL;

ALTER TABLE `Contratos` ADD CONSTRAINT `fkContratosEquipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE;

ALTER TABLE `Contratos` ADD CONSTRAINT `fkContratosEntidade` FOREIGN KEY (`idEntidade`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE SET NULL;

ALTER TABLE `Equipamento_Fornecedor` ADD CONSTRAINT `fkEquipamento_Fornecedor_Equipamento` FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamentos` (`idEquipamento`) ON DELETE CASCADE;

ALTER TABLE `Equipamento_Fornecedor` ADD CONSTRAINT `fkEquipamento_Fornecedor_Fornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedores` (`idFornecedor`) ON DELETE CASCADE;

ALTER TABLE `Localizacoes` ADD CONSTRAINT `fkLocalizacoesServico` FOREIGN KEY (`idServico`) REFERENCES `Servicos` (`idServico`) ON DELETE RESTRICT;
