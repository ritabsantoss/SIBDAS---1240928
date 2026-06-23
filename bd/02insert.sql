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

-- A despejar dados para tabela db1240928.Categorias: ~12 rows (aproximadamente)
INSERT INTO `Categorias` (`idCategoria`, `nome`) VALUES
	(1, 'Monitorização'),
	(2, 'Suporte de vida'),
	(3, 'Diagnóstico'),
	(4, 'Cirurgia'),
	(5, 'Laboratório'),
	(6, 'Neonatologia'),
	(7, 'Reabilitação'),
	(8, 'Imagem médica'),
	(9, 'Terapia'),
	(10, 'Anestesia'),
	(11, 'Emergência'),
	(12, 'Esterilização');

-- A despejar dados para tabela db1240928.Componentes: ~18 rows (aproximadamente)
INSERT INTO `Componentes` (`idComponente`, `idEquipamento`, `codigo_componente`, `nome_componente`, `estado_componente`) VALUES
	(17, 26, 'COMP-0017', 'Elétrodos', 'Ativo'),
	(18, 43, 'COMP-0018', 'Conector em Y', 'Inativo'),
	(29, 3, 'COMP-0006', 'Circuito respiratório', 'Ativo'),
	(30, 3, 'COMP-0007', 'Sensor de fluxo', 'Ativo'),
	(31, 3, 'COMP-0008', 'Bateria', 'Em manutenção'),
	(56, 1, 'COMP-0001', 'Sensor de oximetria (SpO2)', 'Ativo'),
	(57, 1, 'COMP-0002', 'Cabo ECG', 'Ativo'),
	(58, 1, 'COMP-0003', 'Manguito de pressão arterial (NIBP)', 'Ativo'),
	(59, 1, 'COMP-0004', 'Sensor de temperatura', 'Ativo'),
	(60, 1, 'COMP-0005', 'Bateria', 'Ativo'),
	(61, 2, 'COMP-0013', 'Sensor de oximetria (SpO2)', 'Ativo'),
	(62, 2, 'COMP-0014', 'Bateria', 'Ativo'),
	(63, 7, 'COMP-0009', 'Pás de desfibrilhação', 'Ativo'),
	(64, 7, 'COMP-0010', 'Cabo ECG', 'Ativo'),
	(65, 7, 'COMP-0011', 'Bateria', 'Ativo'),
	(66, 7, 'COMP-0012', 'Impressora térmica', 'Ativo'),
	(67, 14, 'COMP-0015', 'Vaporizador', 'Ativo'),
	(68, 17, 'COMP-0016', 'Sensor de temperatura cutânea', 'Ativo');

-- A despejar dados para tabela db1240928.Conteudos: ~47 rows (aproximadamente)
INSERT INTO `Conteudos` (`idConteudo`, `seccao`, `chave`, `valor`, `ordem`) VALUES
	(1, 'hero', 'hero_titulo', 'Apoio ao Inventário Hospitalar de Equipamentos Médicos', 1),
	(2, 'hero', 'hero_subtitulo', 'A SIHEM permite organizar, monitorizar e gerir o inventário hospitalar de equipamentos médicos de forma centralizada, segura e eficiente.', 2),
	(3, 'hero', 'hero_botao', 'Explore a nossa Plataforma', 3),
	(4, 'estatisticas', 'estat1_valor', '1500+', 1),
	(5, 'estatisticas', 'estat1_label', 'Equipamentos Registados', 2),
	(6, 'estatisticas', 'estat2_valor', '45', 3),
	(7, 'estatisticas', 'estat2_label', 'Hospitais Associados', 4),
	(8, 'estatisticas', 'estat3_valor', '120', 5),
	(9, 'estatisticas', 'estat3_label', 'Técnicos Especializados', 6),
	(10, 'estatisticas', 'estat4_valor', '24/7', 7),
	(11, 'estatisticas', 'estat4_label', 'Monitorização Contínua', 8),
	(12, 'servicos', 'servicos_titulo', 'Serviços da Plataforma', 1),
	(13, 'servicos', 'servicos_subtitulo', 'Funcionalidades desenvolvidas para apoiar a gestão hospitalar de equipamentos médicos.', 2),
	(14, 'servicos', 'serv1_titulo', 'Gestão de Equipamentos', 3),
	(15, 'servicos', 'serv1_texto', 'Registo e consulta de equipamentos médicos com informação técnica detalhada.', 4),
	(16, 'servicos', 'serv2_titulo', 'Documentação Técnica', 5),
	(17, 'servicos', 'serv2_texto', 'Armazenamento de manuais, garantias, relatórios e documentação associada.', 6),
	(18, 'servicos', 'serv3_titulo', 'Localização Hospitalar', 7),
	(19, 'servicos', 'serv3_texto', 'Monitorização da localização física dos equipamentos hospitalares.', 8),
	(20, 'servicos', 'serv4_titulo', 'Dashboard Estatístico', 9),
	(21, 'servicos', 'serv4_texto', 'Visualização rápida de indicadores e estatísticas do inventário hospitalar.', 10),
	(22, 'faq', 'faq_titulo', 'Perguntas Frequentes', 1),
	(23, 'faq', 'faq_subtitulo', 'Esclareça as principais dúvidas sobre a plataforma SIHEM.', 2),
	(24, 'faq', 'faq1_pergunta', 'O que é a plataforma?', 3),
	(25, 'faq', 'faq1_resposta', 'A SIHEM é uma plataforma de gestão de inventário hospitalar desenvolvida para organizar equipamentos médicos e documentação técnica.', 4),
	(26, 'faq', 'faq2_pergunta', 'É possível localizar equipamentos hospitalares?', 5),
	(27, 'faq', 'faq2_resposta', 'Sim. O sistema permite acompanhar a localização dos equipamentos dentro das diferentes áreas hospitalares.', 6),
	(28, 'faq', 'faq3_pergunta', 'A plataforma armazena documentação técnica?', 7),
	(29, 'faq', 'faq3_resposta', 'A SIHEM permite guardar manuais, relatórios, garantias e histórico técnico dos equipamentos.', 8),
	(30, 'faq', 'faq4_pergunta', 'O sistema apresenta estatísticas e dashboards?', 9),
	(31, 'faq', 'faq4_resposta', 'Sim. A plataforma inclui dashboards com indicadores estatísticos relacionados com os equipamentos registados.', 10),
	(32, 'faq', 'faq5_pergunta', 'Quem pode utilizar a plataforma?', 11),
	(33, 'faq', 'faq5_resposta', 'A plataforma destina-se a hospitais, clínicas e técnicos responsáveis pela gestão de equipamentos médicos.', 12),
	(34, 'contactos', 'contacto_email', 'geral@sihem.pt', 1),
	(35, 'contactos', 'contacto_telefone', '+351 912 222 222', 2),
	(36, 'contactos', 'contacto_local', 'Porto, Portugal', 3),
	(37, 'plataforma', 'plataforma_versao', 'v1.0', 1),
	(38, 'plataforma', 'plataforma_atualizacao', 'Junho 2026', 2),
	(39, 'plataforma', 'plataforma_estado', 'Sistema Online', 3),
	(40, 'hero', 'hero_link', '#servicos', 4),
	(41, 'estatisticas', 'estat1_icone', 'fa-solid fa-laptop-medical', 1),
	(42, 'estatisticas', 'estat2_icone', 'fa-solid fa-hospital', 2),
	(43, 'estatisticas', 'estat3_icone', 'fa-solid fa-user-doctor', 3),
	(44, 'estatisticas', 'estat4_icone', 'fa-solid fa-clock', 4),
	(46, 'contactos', 'footer_titulo1', 'APOIO TÉCNICO', 1),
	(47, 'contactos', 'footer_titulo2', 'PLATAFORMA', 2),
	(48, 'hero', 'hero_imagem', 'hero_4eea0df603ac5f1d.jpg', 5);

-- A despejar dados para tabela db1240928.Contratos: ~14 rows (aproximadamente)
INSERT INTO `Contratos` (`idContrato`, `idEquipamento`, `idEntidade`, `codigo_contrato`, `tipo_contrato`, `periodicidade`, `ficheiro_contrato`, `observacoes`) VALUES
	(7, 15, 5, 'CON-0007', 'Contrato de Assistência Técnica', 'Anual', NULL, NULL),
	(12, 26, 7, 'CON-0012', 'Manutenção Preventiva', 'Semestral', NULL, NULL),
	(20, 49, 3, 'CON-0014', 'Contrato de Manutenção', 'Semestral', 'contrato_cb2e5fd205887de0.pdf', NULL),
	(22, 3, 2, 'CON-0002', 'Manutenção Preventiva', 'Semestral', NULL, NULL),
	(31, 1, 11, 'CON-0001', 'Contrato de Manutenção', 'Anual', 'contrato_439c4181c510eaa9.pdf', NULL),
	(32, 4, 2, 'CON-0011', 'Contrato de Manutenção', 'Semestral', 'contrato_0fa16f144d04dd01.pdf', NULL),
	(33, 7, 11, 'CON-0003', 'Contrato de Assistência Técnica', 'Anual', NULL, NULL),
	(35, 10, 5, 'CON-0004', 'Contrato de Manutenção', 'Anual', NULL, NULL),
	(36, 11, 6, 'CON-0005', 'Contrato de Manutenção', 'Trimestral', NULL, 'Equipamento crítico de imagem.'),
	(37, 14, 2, 'CON-0006', 'Manutenção Preventiva', 'Semestral', NULL, NULL),
	(39, 16, 10, 'CON-0010', 'Manutenção Preventiva', 'Anual', NULL, NULL),
	(40, 17, 2, 'CON-0008', 'Contrato de Manutenção', 'Anual', NULL, NULL),
	(41, 19, 6, 'CON-0009', 'Contrato de Manutenção', 'Trimestral', NULL, NULL),
	(43, 47, 16, 'CON-0013', 'Contrato de Manutenção', 'Anual', NULL, NULL);

-- A despejar dados para tabela db1240928.Documentos: ~27 rows (aproximadamente)
INSERT INTO `Documentos` (`idDocumento`, `idEquipamento`, `idFornecedor`, `codigo_documento`, `tipo_documento`, `nome_documento`, `data_documento`, `validade`, `estado_documento`, `ficheiro`, `observacoes`, `ativo`) VALUES
	(25, 26, NULL, 'DOC-0025', 'Relatório Técnico', 'Relatório Técnico BeneHeart R12', '2018-03-27', NULL, 'Ativo', NULL, NULL, 1),
	(39, 49, NULL, 'DOC-0027', 'Manual de Utilizador', 'Manual Utilizador Dialog+', '2026-06-01', '2029-06-01', 'Ativo', 'documento_617cb676ab934f13.pdf', NULL, 1),
	(40, 49, NULL, 'DOC-0028', 'Fatura ou Guia de Aquisição', 'Guia Aquisição', '2026-06-01', NULL, 'Ativo', 'documento_dfa8c71fe5cde068.pdf', NULL, 1),
	(44, 18, NULL, 'DOC-0022', 'Manual de Utilizador', 'Manual Panda iRes', '2021-09-14', NULL, 'Ativo', NULL, NULL, 1),
	(47, 3, NULL, 'DOC-0004', 'Declaração de Conformidade', 'Declaração CE Evita V500', '2021-01-20', NULL, 'Ativo', NULL, NULL, 1),
	(48, 3, NULL, 'DOC-0005', 'Relatório Técnico', 'Relatório de manutenção 2025', '2025-11-10', NULL, 'Ativo', NULL, NULL, 1),
	(63, 1, NULL, 'DOC-0001', 'Manual de Utilizador', 'Manual do utilizador IntelliVue MP5', '2022-03-15', NULL, 'Ativo', 'documento_ccab8a6597d7490c.pdf', NULL, 1),
	(64, 1, NULL, 'DOC-0002', 'Certificado de Calibração', 'Certificado de calibração 2025', '2025-03-20', '2026-03-20', 'Expirado', 'documento_ee1017e635f0579a.pdf', 'Calibração anual.', 1),
	(65, 2, NULL, 'DOC-0021', 'Manual de Utilizador', 'Manual IntelliVue MX450', '2021-06-10', NULL, 'Ativo', NULL, NULL, 1),
	(67, 5, NULL, 'DOC-0017', 'Manual de Utilizador', 'Manual Infusomat Space', '2020-04-12', NULL, 'Ativo', 'documento_b94624b28448588f.pdf', NULL, 1),
	(68, 7, NULL, 'DOC-0006', 'Manual de Utilizador', 'Manual ZOLL R Series', '2021-02-28', NULL, 'Ativo', NULL, NULL, 1),
	(69, 7, NULL, 'DOC-0007', 'Certificado de Calibração', 'Calibração desfibrilhador 2026', '2026-02-15', '2027-02-15', 'Ativo', NULL, NULL, 1),
	(70, 9, NULL, 'DOC-0018', 'Manual de Utilizador', 'Manual MAC 2000', '2022-05-01', NULL, 'Ativo', NULL, NULL, 1),
	(73, 10, NULL, 'DOC-0008', 'Fatura ou Guia de Aquisição', 'Fatura de aquisição Vivid E95', '2023-01-18', NULL, 'Ativo', NULL, NULL, 1),
	(74, 10, NULL, 'DOC-0009', 'Manual de Utilizador', 'Manual Vivid E95', '2023-01-18', NULL, 'Ativo', NULL, NULL, 1),
	(75, 11, NULL, 'DOC-0010', 'Declaração de Conformidade', 'Declaração CE SOMATOM go.Top', '2022-07-22', NULL, 'Ativo', NULL, NULL, 1),
	(76, 11, NULL, 'DOC-0011', 'Certificado de Calibração', 'Calibração TAC 2025', '2025-07-01', '2026-07-01', 'Prestes a Expirar', NULL, 'A renovar brevemente.', 1),
	(77, 12, NULL, 'DOC-0024', 'Manual de Utilizador', 'Manual uMEC12', '2021-10-03', NULL, 'Ativo', NULL, NULL, 1),
	(78, 13, NULL, 'DOC-0019', 'Declaração de Conformidade', 'Declaração CE Valleylab FT10', '2020-08-19', NULL, 'Ativo', NULL, NULL, 1),
	(79, 14, NULL, 'DOC-0012', 'Manual de Serviço', 'Manual de serviço Perseus A500', '2021-03-30', NULL, 'Ativo', NULL, NULL, 1),
	(80, 14, NULL, 'DOC-0013', 'Certificado de Calibração', 'Calibração anestesia 2025', '2025-05-10', '2026-05-10', 'Expirado', NULL, NULL, 1),
	(82, 16, NULL, 'DOC-0020', 'Certificado de Calibração', 'Validação autoclave 2025', '2025-09-15', '2026-09-15', 'Ativo', NULL, 'Validação anual.', 1),
	(83, 17, NULL, 'DOC-0014', 'Manual de Utilizador', 'Manual incubadora Caleo', '2020-06-08', NULL, 'Ativo', NULL, NULL, 1),
	(84, 19, NULL, 'DOC-0015', 'Manual de Serviço', 'Manual Atellica CH', '2022-11-02', NULL, 'Ativo', NULL, NULL, 1),
	(85, 19, NULL, 'DOC-0016', 'Certificado de Calibração', 'Calibração analisador 2026', '2026-04-01', '2026-10-01', 'Ativo', NULL, NULL, 1),
	(86, 23, NULL, 'DOC-0023', 'Fatura ou Guia de Aquisição', 'Fatura IntelliVue X3', '2023-04-19', NULL, 'Ativo', NULL, NULL, 1),
	(88, 47, NULL, 'DOC-0026', 'Manual de Utilizador', 'User Manual da Vinci XI', '2023-06-14', NULL, 'Ativo', 'documento_af07ff25dd9346f9.pdf', NULL, 1);

-- A despejar dados para tabela db1240928.Equipamento_Fornecedor: ~37 rows (aproximadamente)
INSERT INTO `Equipamento_Fornecedor` (`id`, `idEquipamento`, `idFornecedor`, `tipo_relacao`) VALUES
	(24, 15, 5, 'Fabricante'),
	(29, 20, 10, 'Distribuidor ou fornecedor comercial'),
	(30, 22, 3, 'Fabricante'),
	(32, 24, 10, 'Distribuidor ou fornecedor comercial'),
	(33, 26, 3, 'Fabricante'),
	(37, 43, 4, 'Fabricante'),
	(57, 49, 3, 'Fabricante'),
	(58, 49, 3, 'Fornecedor de consumíveis ou acessórios'),
	(62, 18, 5, 'Fabricante'),
	(65, 3, 2, 'Fabricante'),
	(66, 3, 11, 'Empresa de assistência técnica'),
	(88, 1, 1, 'Fabricante'),
	(89, 1, 11, 'Empresa de assistência técnica'),
	(90, 1, 12, 'Fornecedor de consumíveis ou acessórios'),
	(91, 2, 1, 'Fabricante'),
	(92, 2, 10, 'Distribuidor ou fornecedor comercial'),
	(93, 4, 2, 'Fabricante'),
	(96, 5, 3, 'Fabricante'),
	(97, 5, 12, 'Fornecedor de consumíveis ou acessórios'),
	(98, 6, 3, 'Fabricante'),
	(99, 7, 4, 'Fabricante'),
	(100, 7, 11, 'Empresa de assistência técnica'),
	(101, 8, 4, 'Fabricante'),
	(102, 9, 5, 'Fabricante'),
	(105, 10, 5, 'Fabricante'),
	(106, 10, 10, 'Distribuidor ou fornecedor comercial'),
	(107, 11, 6, 'Fabricante'),
	(108, 11, 11, 'Empresa de assistência técnica'),
	(109, 12, 7, 'Fabricante'),
	(110, 12, 10, 'Distribuidor ou fornecedor comercial'),
	(111, 13, 8, 'Fabricante'),
	(112, 14, 2, 'Fabricante'),
	(114, 16, 10, 'Distribuidor ou fornecedor comercial'),
	(115, 17, 2, 'Fabricante'),
	(116, 19, 6, 'Fabricante'),
	(117, 23, 1, 'Fabricante'),
	(119, 47, 16, 'Distribuidor ou fornecedor comercial');

-- A despejar dados para tabela db1240928.Equipamentos: ~29 rows (aproximadamente)
INSERT INTO `Equipamentos` (`idEquipamento`, `codigo_interno`, `designacao`, `idCategoria`, `idLocalizacao`, `marca`, `modelo`, `numero_serie`, `fabricante`, `data_aquisicao`, `ano_fabrico`, `custo`, `tipo_entrada`, `estado_atual`, `criticidade`, `observacoes`, `ativo`) VALUES
	(1, 'EQ-0001', 'Monitor multiparamétrico', 1, 1, 'Philips', 'IntelliVue MP5', 'MP5-2022-45873', 'Philips', '2022-03-15', 2022, 8500.00, 'Compra', 'Ativo', 'Suporte de vida', NULL, 1),
	(2, 'EQ-0002', 'Monitor multiparamétrico', 1, 2, 'Philips', 'IntelliVue MX450', 'MX450-2021-11234', 'Philips', '2021-06-10', 2021, 9200.00, 'Compra', 'Ativo', 'Suporte de vida', NULL, 1),
	(3, 'EQ-0003', 'Ventilador pulmonar', 2, 1, 'Dräger', 'Evita V500', 'EV500-2021-9934', 'Dräger', '2021-01-20', 2021, 28000.00, 'Compra', 'Ativo', 'Suporte de vida', NULL, 1),
	(4, 'EQ-0004', 'Ventilador pulmonar', 2, 2, 'Dräger', 'Evita V300', 'EV300-2020-7781', 'Dräger', '2020-09-05', 2020, 24000.00, 'Compra', 'Em manutenção', 'Suporte de vida', 'Em manutenção corretiva.', 1),
	(5, 'EQ-0005', 'Bomba de infusão', 9, 7, 'B. Braun', 'Infusomat Space', 'INF-2020-88321', 'B. Braun', '2020-04-12', 2020, 1800.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(6, 'EQ-0006', 'Bomba de infusão', 9, 7, 'B. Braun', 'Infusomat Space', 'INF-2020-88322', 'B. Braun', '2020-04-12', 2020, 1800.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(7, 'EQ-0007', 'Desfibrilhador', 11, 3, 'ZOLL', 'R Series', 'ZR-2021-7712', 'ZOLL', '2021-02-28', 2021, 12000.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(8, 'EQ-0008', 'Desfibrilhador automático externo', 11, 4, 'ZOLL', 'AED Plus', 'AED-2019-3320', 'ZOLL', '2019-11-15', 2019, 1500.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(9, 'EQ-0009', 'Eletrocardiógrafo', 3, 8, 'GE HealthCare', 'MAC 2000', 'MAC-2022-5567', 'GE HealthCare', '2022-05-01', 2022, 4200.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(10, 'EQ-0010', 'Ecógrafo', 8, 10, 'GE HealthCare', 'Vivid E95', 'VIV-2023-1102', 'GE HealthCare', '2023-01-18', 2023, 65000.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(11, 'EQ-0011', 'Tomógrafo Computorizado (TAC)', 8, 9, 'Siemens Healthineers', 'SOMATOM go.Top', 'SOM-2022-0099', 'Siemens Healthineers', '2022-07-22', 2022, 480000.00, 'Compra', 'Ativo', 'Alta', 'Equipamento crítico de imagiologia.', 1),
	(12, 'EQ-0012', 'Monitor de sinais vitais', 1, 7, 'Mindray', 'uMEC12', 'UMEC-2021-4456', 'Mindray', '2021-10-03', 2021, 3500.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(13, 'EQ-0013', 'Bisturi eletrocirúrgico', 4, 5, 'Medtronic', 'Valleylab FT10', 'FT10-2020-6634', 'Medtronic', '2020-08-19', 2020, 9800.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(14, 'EQ-0014', 'Máquina de anestesia', 10, 5, 'Dräger', 'Perseus A500', 'PER-2021-2245', 'Dräger', '2021-03-30', 2021, 52000.00, 'Compra', 'Ativo', 'Suporte de vida', NULL, 1),
	(15, 'EQ-0015', 'Máquina de anestesia', 10, 6, 'GE HealthCare', 'Aisys CS2', 'AIS-2019-8890', 'GE HealthCare', '2019-05-25', 2019, 49000.00, 'Compra', 'Em manutenção', 'Suporte de vida', NULL, 1),
	(16, 'EQ-0016', 'Autoclave de esterilização', 12, 12, 'Matachana', 'S1000', 'MAT-2018-1199', 'Matachana', '2018-02-14', 2018, 35000.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(17, 'EQ-0017', 'Incubadora neonatal', 6, 14, 'Dräger', 'Caleo', 'CAL-2020-3344', 'Dräger', '2020-06-08', 2020, 22000.00, 'Compra', 'Ativo', 'Suporte de vida', NULL, 1),
	(18, 'EQ-0018', 'Berço de reanimação neonatal', 6, 14, 'GE HealthCare', 'Panda iRes', 'PAN-2021-5521', 'GE HealthCare', '2021-09-14', 2021, 18000.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(19, 'EQ-0019', 'Analisador bioquímico', 5, 11, 'Siemens Healthineers', 'Atellica CH', 'ATE-2022-7788', 'Siemens Healthineers', '2022-11-02', 2022, 120000.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(20, 'EQ-0020', 'Centrífuga laboratorial', 5, 11, 'Eppendorf', '5810 R', 'EPP-2019-9912', 'Eppendorf', '2019-03-11', 2019, 6500.00, 'Compra', 'Ativo', 'Baixa', NULL, 1),
	(21, 'EQ-0021', 'Equipamento de ultrassom', 7, 13, 'Chattanooga', 'Intelect Mobile 2', 'CHA-2018-2210', 'Chattanooga', '2018-10-20', 2018, 4800.00, 'Compra', 'Ativo', 'Baixa', 'Uso em fisioterapia.', 1),
	(22, 'EQ-0022', 'Bomba de seringa', 9, 1, 'B. Braun', 'Perfusor Space', 'PER-2020-1133', 'B. Braun', '2020-07-07', 2020, 2100.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(23, 'EQ-0023', 'Monitor de transporte', 1, 3, 'Philips', 'IntelliVue X3', 'X3-2023-4410', 'Philips', '2023-04-19', 2023, 7800.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(24, 'EQ-0024', 'Ventilador de transporte', 2, 3, 'Hamilton Medical', 'HAMILTON-T1', 'HAM-2021-6655', 'Hamilton Medical', '2021-12-01', 2021, 17000.00, 'Compra', 'Inativo', 'Suporte de vida', 'Fora de serviço, a aguardar peça.', 1),
	(25, 'EQ-0025', 'Monitor de diagnóstico por imagem', 3, 9, 'Eizo', 'RadiForce RX360', 'EIZO-2017-3300', 'Eizo', '2017-01-30', 2017, 3200.00, 'Doação', 'Abatido', 'Baixa', 'Abatido por fim de vida útil.', 1),
	(26, 'EQ-0026', 'Eletrocardiógrafo', 3, 8, 'Mindray', 'BeneHeart R12', 'BEN-2018-0928', 'Mindray', '2018-03-27', 2017, 1200.00, 'Aluguer', 'Ativo', 'Média', NULL, 1),
	(43, 'EQ-0027', 'Ventilador de Emergência', 2, 15, 'ZOLL', 'EMV+', 'EMV-2024-1212', NULL, '2024-07-17', 2024, 12000.00, 'Compra', 'Ativo', 'Alta', NULL, 1),
	(47, 'EQ-0028', 'Robô Cirúrgico', 4, 15, 'Intuitive Surgical', 'da Vinci XI', 'XI-2022-222', NULL, '2023-06-14', 2022, 2000000.00, 'Compra', 'Ativo', 'Média', NULL, 1),
	(49, 'EQ-0029', 'Máquina de Hemodiálise', 2, 16, 'B. Braun', 'Dialog+', 'BBR-2026-0012', NULL, '2026-06-01', 2026, 27000.00, 'Compra', 'Ativo', 'Alta', NULL, 1);

-- A despejar dados para tabela db1240928.Fornecedores: ~15 rows (aproximadamente)
INSERT INTO `Fornecedores` (`idFornecedor`, `nome_empresa`, `nif`, `telefone`, `email`, `website`, `morada`, `pessoa_contacto`, `telefone_contacto`, `observacoes`, `ativo`) VALUES
	(1, 'Philips Portugal, Lda', '500123456', '+351 210 000 001', 'geral@philips.pt', 'https://www.philips.pt/', 'Lagoas Park, Edifício 7, Oeiras', 'Ana Marques', '+351 912 000 001', 'Fabricante de equipamentos de monitorização.', 1),
	(2, 'Dräger Portugal, Lda', '501234567', '+351 210 000 002', 'info@draeger.pt', 'https://www.draeger.com/pt-br_br/Home', 'Av. do Forte 6, Carnaxide', 'Bruno Costa', '+351 912 000 002', 'Fabricante de ventiladores e anestesia.', 1),
	(3, 'B. Braun Medical, Lda', '502345678', '+351 210 000 003', 'apoio@bbraun.pt', 'https://www.bbraun.pt/pt.html', 'Estrada da Outurela 118, Carnaxide', 'Carla Dias', '+351 912 000 003', 'Fabricante de bombas de infusão.', 1),
	(4, 'ZOLL Medical Portugal', '503456789', '+351 210 000 004', 'geral@zoll.pt', 'https://www.zoll.com/en-us', 'Rua de Entrecampos 28, Lisboa', 'Diogo Pinto', '+351 912 000 004', 'Fabricante de desfibrilhadores.', 1),
	(5, 'GE HealthCare Portugal', '504567890', '+351 210 000 005', 'contacto@gehealthcare.pt', 'https://www.gehealthcare.com/en-us', 'Av. da Liberdade 110, Lisboa', 'Eva Lopes', '+351 912 000 005', 'Fabricante de imagem e monitorização.', 1),
	(6, 'Siemens Healthineers Portugal', '505678901', '+351 210 000 006', 'saude@siemens.pt', 'https://www.siemens-healthineers.com/', 'Rua Irmãos Siemens 1, Amadora', 'Filipe Nunes', '+351 912 000 006', 'Fabricante de imagem e laboratório.', 1),
	(7, 'Mindray Medical Portugal', '506789012', '+351 210 000 007', 'info@mindray.pt', 'https://www.mindray.com/en', 'Av. 24 de Julho 56, Lisboa', 'Gabriela Sousa', '+351 912 000 007', 'Fabricante de monitores.', 1),
	(8, 'Medtronic Portugal, Lda', '507890123', '+351 210 000 008', 'geral@medtronic.pt', 'https://www.medtronic.com/', 'Av. de Cáceres 1, Alfragide', 'Hugo Ferreira', '+351 912 000 008', 'Fabricante de equipamento cirúrgico.', 1),
	(9, 'Fresenius Medical Care Portugal', '508901234', '+351 210 000 009', 'info@fresenius.pt', 'https://freseniusmedicalcare.com/pt-pt/', 'Rua Quinta da Fonte 1, Oeiras', 'Inês Ramos', '+351 912 000 009', 'Fornecedor de terapia e consumíveis.', 1),
	(10, 'Medisa - Equipamentos Médicos, Lda', '509012345', '+351 220 000 010', 'vendas@medisa.pt', 'https://medisa.tech/en/home/', 'Rua de Santa Catarina 200, Porto', 'João Teixeira', '+351 912 000 010', 'Distribuidor nacional de equipamento médico.', 1),
	(11, 'TecnoSaúde - Assistência Técnica, Lda', '510123456', '+351 220 000 011', 'apoio@tecnosaude.pt', 'https://issuu.com/tecnosaudeangola', 'Rua do Campo Alegre 50, Porto', 'Luísa Martins', '+351 912 000 011', 'Empresa de assistência técnica multimarca.', 1),
	(12, 'Hospcom, Lda', '511234567', '+351 220 000 012', 'encomendas@hospcom.pt', 'https://www.hospcom.net/', 'Rua de Cedofeita 300, Porto', 'Miguel Carvalho', '+351 912 000 012', 'Fornecedor de consumíveis e acessórios.', 1),
	(15, 'Bacelar+', '500500500', '+351 225 898 900', 'geral@bacelar.pt', 'https://www.bacelar.pt/', 'Rua Duque de Saldanha, 168/174, Porto', 'Alexandra Rosas', '+351 912 344 321', NULL, 1),
	(16, 'Intuitive Surgical', '500501501', '+1 408 523 2100', 'support.indirect@intusurg.com', 'https://www.intuitive.com/en-us', '1020 Kifer Road, Sunnyvale, CA, EUA', 'Emily Johnson', '+1 212 555-0123', NULL, 1),
	(18, 'Medicalta', '515140341', '+351 918 337 665', 'hello@medicalta.pt', 'https://www.medicalta.pt/', 'Rua do Outeiro Reimão, n.º 13, Portela do Gato, Coimbra, Portugal', 'Margarida Antunes', '+351 915 675 675', NULL, 1);

-- A despejar dados para tabela db1240928.Garantias: ~22 rows (aproximadamente)
INSERT INTO `Garantias` (`idGarantia`, `idEquipamento`, `idEntidade`, `codigo_garantia`, `data_inicio`, `data_fim`, `estado_garantia`, `ficheiro_garantia`, `observacoes`) VALUES
	(16, 22, 3, 'GAR-0016', '2020-07-07', '2023-07-07', 'Expirada', NULL, NULL),
	(18, 24, 10, 'GAR-0018', '2021-12-01', '2024-12-01', 'Expirada', NULL, NULL),
	(19, 26, 7, 'GAR-0019', '2018-03-27', '2021-03-27', 'Expirada', NULL, NULL),
	(30, 49, 3, 'GAR-0021', '2026-06-01', '2029-06-01', 'Ativa', 'garantia_90c14931d47c54ea.pdf', NULL),
	(34, 18, 5, 'GAR-0010', '2021-09-14', '2026-06-30', 'Prestes a Expirar', NULL, NULL),
	(36, 3, 2, 'GAR-0003', '2021-01-20', '2026-01-20', 'Expirada', NULL, NULL),
	(45, 1, 1, 'GAR-0001', '2022-03-15', '2025-03-15', 'Expirada', 'garantia_db29bf877da9842c.pdf', NULL),
	(46, 2, 1, 'GAR-0002', '2021-06-10', '2024-06-10', 'Expirada', NULL, NULL),
	(48, 5, 3, 'GAR-0015', '2020-04-12', '2023-04-12', 'Expirada', 'garantia_66afe353385cb487.pdf', NULL),
	(49, 6, NULL, 'GAR-0022', NULL, NULL, NULL, 'garantia_ed92871c293b7d9d.pdf', NULL),
	(50, 7, 4, 'GAR-0004', '2021-02-28', '2024-02-28', 'Expirada', NULL, NULL),
	(51, 9, 5, 'GAR-0012', '2022-05-01', '2025-05-01', 'Expirada', NULL, NULL),
	(53, 10, 5, 'GAR-0005', '2023-01-18', '2028-01-18', 'Ativa', NULL, 'Garantia alargada de 5 anos.'),
	(54, 11, 6, 'GAR-0006', '2022-07-22', '2027-07-22', 'Ativa', NULL, NULL),
	(55, 12, 7, 'GAR-0013', '2021-10-03', '2024-10-03', 'Expirada', NULL, NULL),
	(56, 13, 8, 'GAR-0007', '2020-08-19', '2023-08-19', 'Expirada', NULL, NULL),
	(57, 14, 2, 'GAR-0008', '2021-03-30', '2026-06-20', 'Prestes a Expirar', NULL, 'Termina nos próximos dias.'),
	(59, 16, 10, 'GAR-0017', '2018-02-14', '2021-02-14', 'Expirada', NULL, NULL),
	(60, 17, 2, 'GAR-0009', '2020-06-08', '2025-06-08', 'Expirada', NULL, NULL),
	(61, 19, 6, 'GAR-0011', '2022-11-02', '2027-11-02', 'Ativa', NULL, NULL),
	(62, 23, 1, 'GAR-0014', '2023-04-19', '2026-07-01', 'Prestes a Expirar', NULL, NULL),
	(64, 47, 16, 'GAR-0020', '2023-06-14', '2026-06-14', 'Expirada', 'garantia_9f3b9ce4bf1af063.pdf', NULL);

-- A despejar dados para tabela db1240928.Localizacoes: ~18 rows (aproximadamente)
INSERT INTO `Localizacoes` (`idLocalizacao`, `edificio`, `piso`, `idServico`, `sala`, `observacoes`, `ativo`) VALUES
	(1, 'Edifício A', '2', 1, 'Sala UCI-1', NULL, 1),
	(2, 'Edifício A', '2', 1, 'Sala UCI-2', NULL, 0),
	(3, 'Edifício A', '0', 2, 'Sala de Reanimação', 'Acesso direto à entrada da urgência.', 1),
	(4, 'Edifício A', '0', 2, 'Balcão de Triagem', NULL, 0),
	(5, 'Edifício B', '1', 3, 'Sala de Cirurgia 1', NULL, 1),
	(6, 'Edifício B', '1', 3, 'Sala de Cirurgia 2', NULL, 1),
	(7, 'Edifício C', '3', 4, 'Enfermaria 3A', NULL, 1),
	(8, 'Edifício C', '4', 5, 'Gabinete de Cardiologia 4B', NULL, 1),
	(9, 'Edifício D', '0', 6, 'Sala de TAC', NULL, 1),
	(10, 'Edifício D', '0', 6, 'Sala de Ecografia', NULL, 1),
	(11, 'Edifício E', '-1', 7, 'Laboratório Central', NULL, 1),
	(12, 'Edifício E', '-1', 8, 'Central de Esterilização', NULL, 1),
	(13, 'Edifício F', '1', 9, 'Ginásio de Reabilitação', NULL, 1),
	(14, 'Edifício A', '3', 10, 'Unidade Neonatal', NULL, 1),
	(15, 'Edifício B', '-1', 3, 'Sala de Cirurgia 3', NULL, 1),
	(16, 'Edifício G', '1', 11, 'Unidade de Hemodiálise - Sala 01', NULL, 1),
	(17, 'Edifício H', '1', 5, 'Sala de Reanimação', NULL, 1),
	(18, 'Edifício H', '4', 3, 'Sala de Cirurgia 4', NULL, 1);

-- A despejar dados para tabela db1240928.Servicos: ~11 rows (aproximadamente)
INSERT INTO `Servicos` (`idServico`, `nome`) VALUES
	(1, 'Unidade de Cuidados Intensivos'),
	(2, 'Urgência'),
	(3, 'Bloco Operatório'),
	(4, 'Medicina Interna'),
	(5, 'Cardiologia'),
	(6, 'Imagiologia'),
	(7, 'Patologia Clínica'),
	(8, 'Esterilização'),
	(9, 'Fisioterapia'),
	(10, 'Neonatologia'),
	(11, 'Nefrologia');

-- A despejar dados para tabela db1240928.Utilizadores: ~5 rows (aproximadamente)
INSERT INTO `Utilizadores` (`idUtilizador`, `nome`, `email`, `password_hash`, `perfil`, `last_login`, `created_at`, `genero`, `ativo`) VALUES
	(1, 'Rita Santos', 'ritasantos@sihem.pt', '$2y$10$sQzhih0CheTBlfKFE5xdpOS5AbS82/H1tpyysL3MintlFJX1lJjPC', 'administrador', '2026-06-22 21:56:58', '2026-06-21 10:34:25', 'F', 1),
	(2, 'Mário Silva', 'mariosilva@sihem.pt', '$2y$10$0P7JIEX7BZ8S3Fu2CkyOzuJgWU1MLYuLXoLle8JjU8tE7W298y6di', 'tecnico', '2026-06-22 16:36:30', '2026-06-21 10:34:25', 'M', 1),
	(3, 'Alexandra Rosas', 'alexandrarosas@sihem.pt', '$2y$10$FzPFREcrewfK8AxPtHwJP.Akg//ePz4MUxT7WGAe8qWgfY1Hp5uWK', 'profissional', '2026-06-21 16:48:59', '2026-06-21 10:34:25', 'F', 1),
	(4, 'Diogo Bastos', 'diogobastos@sihem.pt', '$2y$10$x8CyGVVSWa2atuKs5I994uy6BokPSLlO3wYNXghtYApLbGXOffVGq', 'profissional', '2026-06-21 16:46:06', '2026-06-21 16:45:14', 'M', 1),
	(5, 'Beatriz Mendes', 'beatrizmendes@sihem.pt', '$2y$10$mRTOHud1RrdmSPd3azD.L.xelKZFwkcH.e.rytdwhNlb9QNZTXPli', 'tecnico', NULL, '2026-06-22 14:15:49', 'F', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
