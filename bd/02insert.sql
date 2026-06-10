--  ORDEM DE INSERCAO (obrigatoria por causa das chaves estrangeiras):
--    1. Tabelas de base: Categorias, Servicos, Fornecedores, Utilizadores, Conteudos
--    2. Localizacoes  (depende de Servicos)
--    3. Equipamentos  (depende de Categorias e Localizacoes)
--    4. Componentes, Equipamento_Fornecedor, Documentos, Garantias, Contratos

-- 1. CATEGORIAS
INSERT INTO `Categorias` (`idCategoria`, `nome`) VALUES
(1,  'Monitorização'),
(2,  'Suporte de vida'),
(3,  'Diagnóstico'),
(4,  'Cirurgia'),
(5,  'Laboratório'),
(6,  'Neonatologia'),
(7,  'Reabilitação'),
(8,  'Imagem médica'),
(9,  'Terapia'),
(10, 'Anestesia'),
(11, 'Emergência'),
(12, 'Esterilização');

-- 2. SERVICOS
INSERT INTO `Servicos` (`idServico`, `nome`) VALUES
(1,  'Unidade de Cuidados Intensivos'),
(2,  'Urgência'),
(3,  'Bloco Operatório'),
(4,  'Medicina Interna'),
(5,  'Cardiologia'),
(6,  'Imagiologia'),
(7,  'Patologia Clínica'),
(8,  'Esterilização'),
(9,  'Fisioterapia'),
(10, 'Neonatologia');

-- 3. FORNECEDORES
INSERT INTO `Fornecedores`
(`idFornecedor`, `nome_empresa`, `nif`, `telefone`, `email`, `website`, `morada`, `pessoa_contacto`, `telefone_contacto`, `observacoes`) VALUES
(1,  'Philips Portugal, Lda',                 '500123456', '+351 210 000 001', 'geral@philips.pt',        'www.philips.pt',      'Lagoas Park, Edifício 7, Oeiras',        'Ana Marques',     '+351 912 000 001', 'Fabricante de equipamentos de monitorização.'),
(2,  'Dräger Portugal, Lda',                  '501234567', '+351 210 000 002', 'info@draeger.pt',         'www.draeger.com',     'Av. do Forte 6, Carnaxide',              'Bruno Costa',     '+351 912 000 002', 'Fabricante de ventiladores e anestesia.'),
(3,  'B. Braun Medical, Lda',                 '502345678', '+351 210 000 003', 'apoio@bbraun.pt',         'www.bbraun.pt',       'Estrada da Outurela 118, Carnaxide',     'Carla Dias',      '+351 912 000 003', 'Fabricante de bombas de infusão.'),
(4,  'ZOLL Medical Portugal',                 '503456789', '+351 210 000 004', 'geral@zoll.pt',           'www.zoll.com',        'Rua de Entrecampos 28, Lisboa',          'Diogo Pinto',     '+351 912 000 004', 'Fabricante de desfibrilhadores.'),
(5,  'GE HealthCare Portugal',                '504567890', '+351 210 000 005', 'contacto@gehealthcare.pt','www.gehealthcare.pt', 'Av. da Liberdade 110, Lisboa',           'Eva Lopes',       '+351 912 000 005', 'Fabricante de imagem e monitorização.'),
(6,  'Siemens Healthineers Portugal',         '505678901', '+351 210 000 006', 'saude@siemens.pt',        'www.siemens-healthineers.pt', 'Rua Irmãos Siemens 1, Amadora',  'Filipe Nunes',    '+351 912 000 006', 'Fabricante de imagem e laboratório.'),
(7,  'Mindray Medical Portugal',              '506789012', '+351 210 000 007', 'info@mindray.pt',         'www.mindray.com',     'Av. 24 de Julho 56, Lisboa',             'Gabriela Sousa',  '+351 912 000 007', 'Fabricante de monitores.'),
(8,  'Medtronic Portugal, Lda',               '507890123', '+351 210 000 008', 'geral@medtronic.pt',      'www.medtronic.pt',    'Av. de Cáceres 1, Alfragide',            'Hugo Ferreira',   '+351 912 000 008', 'Fabricante de equipamento cirúrgico.'),
(9,  'Fresenius Medical Care Portugal',       '508901234', '+351 210 000 009', 'info@fresenius.pt',       'www.freseniusmedicalcare.pt', 'Rua Quinta da Fonte 1, Oeiras',  'Inês Ramos',      '+351 912 000 009', 'Fornecedor de terapia e consumíveis.'),
(10, 'Medisa - Equipamentos Médicos, Lda',    '509012345', '+351 220 000 010', 'vendas@medisa.pt',        'www.medisa.pt',       'Rua de Santa Catarina 200, Porto',       'João Teixeira',   '+351 912 000 010', 'Distribuidor nacional de equipamento médico.'),
(11, 'TecnoSaúde - Assistência Técnica, Lda', '510123456', '+351 220 000 011', 'apoio@tecnosaude.pt',     'www.tecnosaude.pt',   'Rua do Campo Alegre 50, Porto',          'Luísa Martins',   '+351 912 000 011', 'Empresa de assistência técnica multimarca.'),
(12, 'Hospitalar Consumíveis, Lda',           '511234567', '+351 220 000 012', 'encomendas@hospconsum.pt','www.hospconsum.pt',   'Rua de Cedofeita 300, Porto',            'Miguel Carvalho', '+351 912 000 012', 'Fornecedor de consumíveis e acessórios.');

-- 4. UTILIZADORES
--    Esta password e um MARCADOR. Ao construir o login real,
--    gerar a verdadeira com PHP (num ficheiro .php no Laragon):
--        <?php echo password_hash('admin1234', PASSWORD_DEFAULT); ?>
--    Copiar o resultado e fazer:
--        UPDATE Utilizadores SET password = 'HASH_GERADO' WHERE email = 'admin@sihem.pt';
INSERT INTO `Utilizadores` (`idUtilizador`, `nome`, `email`, `password_hash`) VALUES
(1, 'Administrador',            'admin@sihem.pt',   'SUBSTITUIR_POR_HASH_password_hash'),
(2, 'Técnico de Equipamentos',  'tecnico@sihem.pt', 'SUBSTITUIR_POR_HASH_password_hash');

-- 5. LOCALIZACOES  (idServico -> Servicos)
INSERT INTO `Localizacoes`
(`idLocalizacao`, `edificio`, `piso`, `idServico`, `sala`, `observacoes`) VALUES
(1,  'Edifício A', '2',  1,  'Sala UCI-1',                NULL),
(2,  'Edifício A', '2',  1,  'Sala UCI-2',                NULL),
(3,  'Edifício A', '0',  2,  'Sala de Reanimação',        'Acesso direto à entrada de urgência.'),
(4,  'Edifício A', '0',  2,  'Balcão de Triagem',         NULL),
(5,  'Edifício B', '1',  3,  'Sala de Cirurgia 1',        NULL),
(6,  'Edifício B', '1',  3,  'Sala de Cirurgia 2',        NULL),
(7,  'Edifício C', '3',  4,  'Enfermaria 3A',             NULL),
(8,  'Edifício C', '4',  5,  'Gabinete de Cardiologia 4B',NULL),
(9,  'Edifício D', '0',  6,  'Sala de TAC',               NULL),
(10, 'Edifício D', '0',  6,  'Sala de Ecografia',         NULL),
(11, 'Edifício E', '-1', 7,  'Laboratório Central',       NULL),
(12, 'Edifício E', '-1', 8,  'Central de Esterilização',  NULL),
(13, 'Edifício F', '1',  9,  'Ginásio de Reabilitação',   NULL),
(14, 'Edifício A', '3',  10, 'Unidade Neonatal',          NULL);

-- 6. EQUIPAMENTOS  (idCategoria -> Categorias, idLocalizacao -> Localizacoes)
INSERT INTO `Equipamentos`
(`idEquipamento`, `codigo_interno`, `designacao`, `idCategoria`, `idLocalizacao`, `marca`, `modelo`, `numero_serie`, `fabricante`, `data_aquisicao`, `ano_fabrico`, `custo`, `tipo_entrada`, `estado_atual`, `criticidade`, `observacoes`) VALUES
(1,  'EQ-0001', 'Monitor multiparamétrico',           1,  1,  'Philips',              'IntelliVue MP5',   'MP5-2022-45873',  'Philips',              '2022-03-15', 2022, 8500.00,   'Compra',  'Ativo',          'Suporte de vida', NULL),
(2,  'EQ-0002', 'Monitor multiparamétrico',           1,  2,  'Philips',              'IntelliVue MX450', 'MX450-2021-11234','Philips',              '2021-06-10', 2021, 9200.00,   'Compra',  'Ativo',          'Suporte de vida', NULL),
(3,  'EQ-0003', 'Ventilador pulmonar',                2,  1,  'Dräger',               'Evita V500',       'EV500-2021-9934', 'Dräger',               '2021-01-20', 2021, 28000.00,  'Compra',  'Ativo',          'Suporte de vida', NULL),
(4,  'EQ-0004', 'Ventilador pulmonar',                2,  2,  'Dräger',               'Evita V300',       'EV300-2020-7781', 'Dräger',               '2020-09-05', 2020, 24000.00,  'Compra',  'Em manutenção',  'Suporte de vida', 'Em manutenção corretiva.'),
(5,  'EQ-0005', 'Bomba de infusão',                   9,  7,  'B. Braun',             'Infusomat Space',  'INF-2020-88321',  'B. Braun',             '2020-04-12', 2020, 1800.00,   'Compra',  'Ativo',          'Média',           NULL),
(6,  'EQ-0006', 'Bomba de infusão',                   9,  7,  'B. Braun',             'Infusomat Space',  'INF-2020-88322',  'B. Braun',             '2020-04-12', 2020, 1800.00,   'Compra',  'Ativo',          'Média',           NULL),
(7,  'EQ-0007', 'Desfibrilhador',                     11, 3,  'ZOLL',                 'R Series',         'ZR-2021-7712',    'ZOLL',                 '2021-02-28', 2021, 12000.00,  'Compra',  'Ativo',          'Alta',            NULL),
(8,  'EQ-0008', 'Desfibrilhador automático externo',  11, 4,  'ZOLL',                 'AED Plus',         'AED-2019-3320',   'ZOLL',                 '2019-11-15', 2019, 1500.00,   'Compra',  'Ativo',          'Alta',            NULL),
(9,  'EQ-0009', 'Eletrocardiógrafo',                  3,  8,  'GE HealthCare',        'MAC 2000',         'MAC-2022-5567',   'GE HealthCare',        '2022-05-01', 2022, 4200.00,   'Compra',  'Ativo',          'Alta',            NULL),
(10, 'EQ-0010', 'Ecógrafo',                           8,  10, 'GE HealthCare',        'Vivid E95',        'VIV-2023-1102',   'GE HealthCare',        '2023-01-18', 2023, 65000.00,  'Compra',  'Ativo',          'Alta',            NULL),
(11, 'EQ-0011', 'Tomógrafo Computorizado (TAC)',      8,  9,  'Siemens Healthineers', 'SOMATOM go.Top',   'SOM-2022-0099',   'Siemens Healthineers', '2022-07-22', 2022, 480000.00, 'Compra',  'Ativo',          'Alta',            'Equipamento crítico de imagiologia.'),
(12, 'EQ-0012', 'Monitor de sinais vitais',           1,  7,  'Mindray',              'uMEC12',           'UMEC-2021-4456',  'Mindray',              '2021-10-03', 2021, 3500.00,   'Compra',  'Ativo',          'Média',           NULL),
(13, 'EQ-0013', 'Bisturi eletrocirúrgico',            4,  5,  'Medtronic',            'Valleylab FT10',   'FT10-2020-6634',  'Medtronic',            '2020-08-19', 2020, 9800.00,   'Compra',  'Ativo',          'Alta',            NULL),
(14, 'EQ-0014', 'Máquina de anestesia',               10, 5,  'Dräger',               'Perseus A500',     'PER-2021-2245',   'Dräger',               '2021-03-30', 2021, 52000.00,  'Compra',  'Ativo',          'Suporte de vida', NULL),
(15, 'EQ-0015', 'Máquina de anestesia',               10, 6,  'GE HealthCare',        'Aisys CS2',        'AIS-2019-8890',   'GE HealthCare',        '2019-05-25', 2019, 49000.00,  'Compra',  'Em manutenção',  'Suporte de vida', NULL),
(16, 'EQ-0016', 'Autoclave de esterilização',         12, 12, 'Matachana',            'S1000',            'MAT-2018-1199',   'Matachana',            '2018-02-14', 2018, 35000.00,  'Compra',  'Ativo',          'Média',           NULL),
(17, 'EQ-0017', 'Incubadora neonatal',                6,  14, 'Dräger',               'Caleo',            'CAL-2020-3344',   'Dräger',               '2020-06-08', 2020, 22000.00,  'Compra',  'Ativo',          'Suporte de vida', NULL),
(18, 'EQ-0018', 'Berço de reanimação neonatal',       6,  14, 'GE HealthCare',        'Panda iRes',       'PAN-2021-5521',   'GE HealthCare',        '2021-09-14', 2021, 18000.00,  'Compra',  'Ativo',          'Alta',            NULL),
(19, 'EQ-0019', 'Analisador bioquímico',              5,  11, 'Siemens Healthineers', 'Atellica CH',      'ATE-2022-7788',   'Siemens Healthineers', '2022-11-02', 2022, 120000.00, 'Compra',  'Ativo',          'Média',           NULL),
(20, 'EQ-0020', 'Centrífuga laboratorial',            5,  11, 'Eppendorf',            '5810 R',           'EPP-2019-9912',   'Eppendorf',            '2019-03-11', 2019, 6500.00,   'Compra',  'Ativo',          'Baixa',           NULL),
(21, 'EQ-0021', 'Equipamento de ultrassom',           7,  13, 'Chattanooga',          'Intelect Mobile 2','CHA-2018-2210',   'Chattanooga',          '2018-10-20', 2018, 4800.00,   'Compra',  'Ativo',          'Baixa',           'Uso em fisioterapia.'),
(22, 'EQ-0022', 'Bomba de seringa',                   9,  1,  'B. Braun',             'Perfusor Space',   'PER-2020-1133',   'B. Braun',             '2020-07-07', 2020, 2100.00,   'Compra',  'Ativo',          'Média',           NULL),
(23, 'EQ-0023', 'Monitor de transporte',              1,  3,  'Philips',              'IntelliVue X3',    'X3-2023-4410',    'Philips',              '2023-04-19', 2023, 7800.00,   'Compra',  'Ativo',          'Alta',            NULL),
(24, 'EQ-0024', 'Ventilador de transporte',           2,  3,  'Hamilton Medical',     'HAMILTON-T1',      'HAM-2021-6655',   'Hamilton Medical',     '2021-12-01', 2021, 17000.00,  'Compra',  'Inativo',        'Suporte de vida', 'Fora de serviço, a aguardar peça.'),
(25, 'EQ-0025', 'Monitor de diagnóstico por imagem',  3,  9,  'Eizo',                 'RadiForce RX360',  'EIZO-2017-3300',  'Eizo',                 '2017-01-30', 2017, 3200.00,   'Doação',  'Abatido',        'Baixa',           'Abatido por fim de vida útil.');

-- 7. COMPONENTES  (idEquipamento -> Equipamentos)
INSERT INTO `Componentes`
(`idEquipamento`, `codigo_componente`, `nome_componente`, `estado_componente`) VALUES
(1,  'COMP-0001', 'Sensor de oximetria (SpO2)',            'Ativo'),
(1,  'COMP-0002', 'Cabo ECG',                              'Ativo'),
(1,  'COMP-0003', 'Manguito de pressão arterial (NIBP)',   'Ativo'),
(1,  'COMP-0004', 'Sensor de temperatura',                 'Ativo'),
(1,  'COMP-0005', 'Bateria',                               'Ativo'),
(3,  'COMP-0006', 'Circuito respiratório',                 'Ativo'),
(3,  'COMP-0007', 'Sensor de fluxo',                       'Ativo'),
(3,  'COMP-0008', 'Bateria',                               'Em manutenção'),
(7,  'COMP-0009', 'Pás de desfibrilhação',                 'Ativo'),
(7,  'COMP-0010', 'Cabo ECG',                              'Ativo'),
(7,  'COMP-0011', 'Bateria',                               'Ativo'),
(7,  'COMP-0012', 'Impressora térmica',                    'Ativo'),
(2,  'COMP-0013', 'Sensor de oximetria (SpO2)',            'Ativo'),
(2,  'COMP-0014', 'Bateria',                               'Ativo'),
(14, 'COMP-0015', 'Vaporizador',                           'Ativo'),
(17, 'COMP-0016', 'Sensor de temperatura cutânea',         'Ativo');

-- 8. EQUIPAMENTO_FORNECEDOR  (N:N, com tipo de relacao)
INSERT INTO `Equipamento_Fornecedor`
(`idEquipamento`, `idFornecedor`, `tipo_relacao`) VALUES
(1,  1,  'Fabricante'),
(1,  11, 'Empresa de assistência técnica'),
(1,  12, 'Fornecedor de consumíveis ou acessórios'),
(2,  1,  'Fabricante'),
(2,  10, 'Distribuidor ou fornecedor comercial'),
(3,  2,  'Fabricante'),
(3,  11, 'Empresa de assistência técnica'),
(4,  2,  'Fabricante'),
(5,  3,  'Fabricante'),
(5,  12, 'Fornecedor de consumíveis ou acessórios'),
(6,  3,  'Fabricante'),
(7,  4,  'Fabricante'),
(7,  11, 'Empresa de assistência técnica'),
(8,  4,  'Fabricante'),
(9,  5,  'Fabricante'),
(10, 5,  'Fabricante'),
(10, 10, 'Distribuidor ou fornecedor comercial'),
(11, 6,  'Fabricante'),
(11, 11, 'Empresa de assistência técnica'),
(12, 7,  'Fabricante'),
(12, 10, 'Distribuidor ou fornecedor comercial'),
(13, 8,  'Fabricante'),
(14, 2,  'Fabricante'),
(15, 5,  'Fabricante'),
(16, 10, 'Distribuidor ou fornecedor comercial'),
(17, 2,  'Fabricante'),
(18, 5,  'Fabricante'),
(19, 6,  'Fabricante'),
(20, 10, 'Distribuidor ou fornecedor comercial'),
(22, 3,  'Fabricante'),
(23, 1,  'Fabricante'),
(24, 10, 'Distribuidor ou fornecedor comercial');


-- 9. DOCUMENTOS  (idEquipamento -> Equipamentos; idFornecedor opcional)
INSERT INTO `Documentos`
(`idEquipamento`, `idFornecedor`, `codigo_documento`, `tipo_documento`, `nome_documento`, `data_documento`, `validade`, `estado_documento`, `ficheiro`, `loc_ficheiro`, `observacoes`) VALUES
(1,  1,    'DOC-0001', 'Manual de Utilizador',        'Manual do utilizador IntelliVue MP5', '2022-03-15', NULL,         'Ativo',             'docs/mp5_manual_user.pdf',  NULL, NULL),
(1,  1,    'DOC-0002', 'Certificado de Calibração',   'Certificado de calibração 2025',      '2025-03-20', '2026-03-20', 'Expirado',          'docs/mp5_cal_2025.pdf',     NULL, 'Calibração anual.'),
(3,  2,    'DOC-0003', 'Manual de Serviço',           'Manual de serviço Evita V500',        '2021-01-20', NULL,         'Ativo',             'docs/evita_service.pdf',    NULL, NULL),
(3,  2,    'DOC-0004', 'Declaração de Conformidade',  'Declaração CE Evita V500',            '2021-01-20', NULL,         'Ativo',             'docs/evita_ce.pdf',         NULL, NULL),
(3,  NULL, 'DOC-0005', 'Relatório Técnico',           'Relatório de manutenção 2025',        '2025-11-10', NULL,         'Ativo',             'docs/evita_rt_2025.pdf',    NULL, NULL),
(7,  4,    'DOC-0006', 'Manual de Utilizador',        'Manual ZOLL R Series',                '2021-02-28', NULL,         'Ativo',             'docs/zoll_user.pdf',        NULL, NULL),
(7,  4,    'DOC-0007', 'Certificado de Calibração',   'Calibração desfibrilhador 2026',      '2026-02-15', '2027-02-15', 'Ativo',             'docs/zoll_cal.pdf',         NULL, NULL),
(10, 5,    'DOC-0008', 'Fatura ou Guia de Aquisição', 'Fatura de aquisição Vivid E95',       '2023-01-18', NULL,         'Ativo',             'docs/vivid_fatura.pdf',     NULL, NULL),
(10, 5,    'DOC-0009', 'Manual de Utilizador',        'Manual Vivid E95',                    '2023-01-18', NULL,         'Ativo',             'docs/vivid_user.pdf',       NULL, NULL),
(11, 6,    'DOC-0010', 'Declaração de Conformidade',  'Declaração CE SOMATOM go.Top',        '2022-07-22', NULL,         'Ativo',             'docs/somatom_ce.pdf',       NULL, NULL),
(11, 6,    'DOC-0011', 'Certificado de Calibração',   'Calibração TAC 2025',                 '2025-07-01', '2026-07-01', 'Prestes a Expirar', 'docs/somatom_cal.pdf',      NULL, 'A renovar brevemente.'),
(14, 2,    'DOC-0012', 'Manual de Serviço',           'Manual de serviço Perseus A500',      '2021-03-30', NULL,         'Ativo',             'docs/perseus_service.pdf',  NULL, NULL),
(14, 2,    'DOC-0013', 'Certificado de Calibração',   'Calibração anestesia 2025',           '2025-05-10', '2026-05-10', 'Expirado',          'docs/perseus_cal.pdf',      NULL, NULL),
(17, 2,    'DOC-0014', 'Manual de Utilizador',        'Manual incubadora Caleo',             '2020-06-08', NULL,         'Ativo',             'docs/caleo_user.pdf',       NULL, NULL),
(19, 6,    'DOC-0015', 'Manual de Serviço',           'Manual Atellica CH',                  '2022-11-02', NULL,         'Ativo',             'docs/atellica_service.pdf', NULL, NULL),
(19, 6,    'DOC-0016', 'Certificado de Calibração',   'Calibração analisador 2026',          '2026-04-01', '2026-10-01', 'Ativo',             'docs/atellica_cal.pdf',     NULL, NULL),
(5,  3,    'DOC-0017', 'Manual de Utilizador',        'Manual Infusomat Space',              '2020-04-12', NULL,         'Ativo',             'docs/infusomat_user.pdf',   NULL, NULL),
(9,  5,    'DOC-0018', 'Manual de Utilizador',        'Manual MAC 2000',                     '2022-05-01', NULL,         'Ativo',             'docs/mac2000_user.pdf',     NULL, NULL),
(13, 8,    'DOC-0019', 'Declaração de Conformidade',  'Declaração CE Valleylab FT10',        '2020-08-19', NULL,         'Ativo',             'docs/ft10_ce.pdf',          NULL, NULL),
(16, 10,   'DOC-0020', 'Certificado de Calibração',   'Validação autoclave 2025',            '2025-09-15', '2026-09-15', 'Ativo',             'docs/autoclave_val.pdf',    NULL, 'Validação anual.'),
(2,  1,    'DOC-0021', 'Manual de Utilizador',        'Manual IntelliVue MX450',             '2021-06-10', NULL,         'Ativo',             'docs/mx450_user.pdf',       NULL, NULL),
(18, 5,    'DOC-0022', 'Manual de Utilizador',        'Manual Panda iRes',                   '2021-09-14', NULL,         'Ativo',             'docs/panda_user.pdf',       NULL, NULL),
(23, 1,    'DOC-0023', 'Fatura ou Guia de Aquisição', 'Fatura IntelliVue X3',                '2023-04-19', NULL,         'Ativo',             'docs/x3_fatura.pdf',        NULL, NULL),
(12, 7,    'DOC-0024', 'Manual de Utilizador',        'Manual uMEC12',                       '2021-10-03', NULL,         'Ativo',             'docs/umec_user.pdf',        NULL, NULL);


-- 10. GARANTIAS  (idEquipamento -> Equipamentos; idEntidade -> Fornecedores)
INSERT INTO `Garantias`
(`idEquipamento`, `idEntidade`, `codigo_garantia`, `data_inicio`, `data_fim`, `estado_garantia`, `ficheiro_garantia`, `observacoes`) VALUES
(1,  1,  'GAR-0001', '2022-03-15', '2025-03-15', 'Expirada',          NULL, NULL),
(2,  1,  'GAR-0002', '2021-06-10', '2024-06-10', 'Expirada',          NULL, NULL),
(3,  2,  'GAR-0003', '2021-01-20', '2026-01-20', 'Expirada',          NULL, NULL),
(7,  4,  'GAR-0004', '2021-02-28', '2024-02-28', 'Expirada',          NULL, NULL),
(10, 5,  'GAR-0005', '2023-01-18', '2028-01-18', 'Ativa',             NULL, 'Garantia alargada de 5 anos.'),
(11, 6,  'GAR-0006', '2022-07-22', '2027-07-22', 'Ativa',             NULL, NULL),
(13, 8,  'GAR-0007', '2020-08-19', '2023-08-19', 'Expirada',          NULL, NULL),
(14, 2,  'GAR-0008', '2021-03-30', '2026-06-20', 'Prestes a Expirar', NULL, 'Termina nos próximos dias.'),
(17, 2,  'GAR-0009', '2020-06-08', '2025-06-08', 'Expirada',          NULL, NULL),
(18, 5,  'GAR-0010', '2021-09-14', '2026-06-30', 'Prestes a Expirar', NULL, NULL),
(19, 6,  'GAR-0011', '2022-11-02', '2027-11-02', 'Ativa',             NULL, NULL),
(9,  5,  'GAR-0012', '2022-05-01', '2025-05-01', 'Expirada',          NULL, NULL),
(12, 7,  'GAR-0013', '2021-10-03', '2024-10-03', 'Expirada',          NULL, NULL),
(23, 1,  'GAR-0014', '2023-04-19', '2026-07-01', 'Prestes a Expirar', NULL, NULL),
(5,  3,  'GAR-0015', '2020-04-12', '2023-04-12', 'Expirada',          NULL, NULL),
(22, 3,  'GAR-0016', '2020-07-07', '2023-07-07', 'Expirada',          NULL, NULL),
(16, 10, 'GAR-0017', '2018-02-14', '2021-02-14', 'Expirada',          NULL, NULL),
(24, 10, 'GAR-0018', '2021-12-01', '2024-12-01', 'Expirada',          NULL, NULL);


-- 11. CONTRATOS  (idEquipamento -> Equipamentos; idEntidade -> Fornecedores)
INSERT INTO `Contratos`
(`idEquipamento`, `idEntidade`, `codigo_contrato`, `tipo_contrato`, `periodicidade`, `ficheiro_contrato`, `observacoes`) VALUES
(1,  11, 'CON-0001', 'Contrato de Manutenção',           'Anual',      NULL, NULL),
(3,  2,  'CON-0002', 'Manutenção Preventiva',            'Semestral',  NULL, NULL),
(7,  11, 'CON-0003', 'Contrato de Assistência Técnica',  'Anual',      NULL, NULL),
(10, 5,  'CON-0004', 'Contrato de Manutenção',           'Anual',      NULL, NULL),
(11, 6,  'CON-0005', 'Contrato de Manutenção',           'Trimestral', NULL, 'Equipamento crítico de imagem.'),
(14, 2,  'CON-0006', 'Manutenção Preventiva',            'Semestral',  NULL, NULL),
(15, 5,  'CON-0007', 'Contrato de Assistência Técnica',  'Anual',      NULL, NULL),
(17, 2,  'CON-0008', 'Contrato de Manutenção',           'Anual',      NULL, NULL),
(19, 6,  'CON-0009', 'Contrato de Manutenção',           'Trimestral', NULL, NULL),
(16, 10, 'CON-0010', 'Manutenção Preventiva',            'Anual',      NULL, NULL),
(4,  2,  'CON-0011', 'Contrato de Manutenção',           'Semestral',  NULL, NULL);


-- 12. CONTEUDOS  (backoffice da area publica)
INSERT INTO `Conteudos` (`seccao`, `chave`, `valor`, `ordem`) VALUES
('hero',         'hero_titulo',            'Apoio ao Inventário Hospitalar de Equipamentos Médicos', 1),
('hero',         'hero_subtitulo',         'A SIHEM permite organizar, monitorizar e gerir o inventário hospitalar de equipamentos médicos de forma centralizada, segura e eficiente.', 2),
('hero',         'hero_botao',             'Explore a nossa Plataforma', 3),
('estatisticas', 'estat1_valor',           '1500+', 1),
('estatisticas', 'estat1_label',           'Equipamentos Registados', 2),
('estatisticas', 'estat2_valor',           '45', 3),
('estatisticas', 'estat2_label',           'Hospitais Associados', 4),
('estatisticas', 'estat3_valor',           '120', 5),
('estatisticas', 'estat3_label',           'Técnicos Especializados', 6),
('estatisticas', 'estat4_valor',           '24/7', 7),
('estatisticas', 'estat4_label',           'Monitorização Contínua', 8),
('servicos',     'servicos_titulo',        'Serviços da Plataforma', 1),
('servicos',     'servicos_subtitulo',     'Funcionalidades desenvolvidas para apoiar a gestão hospitalar de equipamentos médicos.', 2),
('servicos',     'serv1_titulo',           'Gestão de Equipamentos', 3),
('servicos',     'serv1_texto',            'Registo e consulta de equipamentos médicos com informação técnica detalhada.', 4),
('servicos',     'serv2_titulo',           'Documentação Técnica', 5),
('servicos',     'serv2_texto',            'Armazenamento de manuais, garantias, relatórios e documentação associada.', 6),
('servicos',     'serv3_titulo',           'Localização Hospitalar', 7),
('servicos',     'serv3_texto',            'Monitorização da localização física dos equipamentos hospitalares.', 8),
('servicos',     'serv4_titulo',           'Dashboard Estatístico', 9),
('servicos',     'serv4_texto',            'Visualização rápida de indicadores e estatísticas do inventário hospitalar.', 10),
('faq',          'faq_titulo',             'Perguntas Frequentes', 1),
('faq',          'faq_subtitulo',          'Esclareça as principais dúvidas sobre a plataforma SIHEM.', 2),
('faq',          'faq1_pergunta',          'O que é a plataforma SIHEM?', 3),
('faq',          'faq1_resposta',          'A SIHEM é uma plataforma de gestão de inventário hospitalar desenvolvida para organizar equipamentos médicos e documentação técnica.', 4),
('faq',          'faq2_pergunta',          'É possível localizar equipamentos hospitalares?', 5),
('faq',          'faq2_resposta',          'Sim. O sistema permite acompanhar a localização dos equipamentos dentro das diferentes áreas hospitalares.', 6),
('faq',          'faq3_pergunta',          'A plataforma armazena documentação técnica?', 7),
('faq',          'faq3_resposta',          'A SIHEM permite guardar manuais, relatórios, garantias e histórico técnico dos equipamentos.', 8),
('faq',          'faq4_pergunta',          'O sistema apresenta estatísticas e dashboards?', 9),
('faq',          'faq4_resposta',          'Sim. A plataforma inclui dashboards com indicadores estatísticos relacionados com os equipamentos registados.', 10),
('faq',          'faq5_pergunta',          'Quem pode utilizar a plataforma?', 11),
('faq',          'faq5_resposta',          'A plataforma destina-se a hospitais, clínicas e técnicos responsáveis pela gestão de equipamentos médicos.', 12),
('contactos',    'contacto_email',         'geral@sihem.pt', 1),
('contactos',    'contacto_telefone',      '+351 912 222 222', 2),
('contactos',    'contacto_local',         'Porto, Portugal', 3),
('plataforma',   'plataforma_versao',      'v1.0', 1),
('plataforma',   'plataforma_atualizacao', 'Junho 2026', 2),
('plataforma',   'plataforma_estado',      'Sistema Online', 3);
