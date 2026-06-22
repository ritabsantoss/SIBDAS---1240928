<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] === 'profissional') {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}
require_once __DIR__ . '/../../includes/validacoes.php';

$pagina_ativa = 'equipamentos';

$erros = [];
$erro_sistema = '';
$lista_categorias = [];
$lista_localizacoes = [];
$lista_fornecedores = [];
$ficheiros_guardados = [];
$proximo_eq_num   = 1;
$proximo_gar_num  = 1;
$proximo_con_num  = 1;
$proximo_doc_num  = 1;
$proximo_comp_num = 1;

// dropdowns das chaves estrangeiras (da BD)
try {
    $ligacao = liga_bd();
    $lista_categorias   = $ligacao->query("SELECT idCategoria, nome FROM Categorias ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $lista_localizacoes = $ligacao->query(
        "SELECT l.idLocalizacao, l.edificio, l.piso, l.sala, s.nome AS servico
         FROM Localizacoes l JOIN Servicos s ON l.idServico = s.idServico
         ORDER BY s.nome"
    )->fetchAll(PDO::FETCH_OBJ);
    $lista_fornecedores = $ligacao->query("SELECT idFornecedor, nome_empresa, nif, telefone, email FROM Fornecedores ORDER BY nome_empresa")->fetchAll(PDO::FETCH_OBJ);
    $proximo_eq_num   = proximo_numero_codigo($ligacao, 'Equipamentos', 'codigo_interno', 'EQ');
    $proximo_gar_num  = proximo_numero_codigo($ligacao, 'Garantias', 'codigo_garantia', 'GAR');
    $proximo_con_num  = proximo_numero_codigo($ligacao, 'Contratos', 'codigo_contrato', 'CON');
    $proximo_doc_num  = proximo_numero_codigo($ligacao, 'Documentos', 'codigo_documento', 'DOC');
    $proximo_comp_num = proximo_numero_codigo($ligacao, 'Componentes', 'codigo_componente', 'COMP');
    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os dados do formulário.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Recolher dados do equipamento
    $codigo_interno = trim($_POST['codigo_interno'] ?? '');
    $designacao     = trim($_POST['designacao'] ?? '');
    $idCategoria    = $_POST['categoria'] ?? '';
    $idLocalizacao  = $_POST['localizacao_associada'] ?? '';
    $marca          = trim($_POST['marca'] ?? '');
    $modelo         = trim($_POST['modelo'] ?? '');
    $numero_serie   = trim($_POST['numero_serie'] ?? '');
    $data_aquisicao = trim($_POST['data_aquisicao'] ?? '');
    $ano_fabrico    = trim($_POST['ano_fabrico'] ?? '');
    $custo          = trim($_POST['custo'] ?? '');
    $tipo_entrada   = $_POST['tipo_entrada'] ?? '';
    $estado_atual   = $_POST['estado_atual'] ?? '';
    $criticidade    = $_POST['criticidade'] ?? '';
    $observacoes    = trim($_POST['observacoes_equipamento'] ?? '');

    // Garantia
    $codigo_garantia      = trim($_POST['codigo_garantia'] ?? '');
    $entidade_garantia    = $_POST['entidade_garantia'] ?? '';
    $data_inicio_garantia = trim($_POST['data_inicio_garantia'] ?? '');
    $data_fim_garantia    = trim($_POST['data_fim_garantia'] ?? '');
    $estado_garantia      = $_POST['estado_garantia'] ?? '';
    $obs_garantia         = trim($_POST['observacoes_garantia'] ?? '');
    $tem_garantia = (
        ctype_digit((string)$entidade_garantia) ||
        $data_inicio_garantia !== '' ||
        $data_fim_garantia !== '' ||
        ($estado_garantia !== '' && $estado_garantia !== 'Escolha...') ||
        $obs_garantia !== ''
    );

    // Contrato
    $existe_contrato   = $_POST['existe_contrato'] ?? '';
    $codigo_contrato   = trim($_POST['codigo_contrato'] ?? '');
    $tipo_contrato     = $_POST['tipo_contrato'] ?? '';
    $entidade_contrato = $_POST['entidade_contrato'] ?? '';
    $periodicidade     = $_POST['periodicidade'] ?? '';
    $obs_contrato      = trim($_POST['observacoes_contrato'] ?? '');


    /*
    // 2. Validar (obrigatórios + restrições de integridade)
    if ($codigo_interno === '') $erros[] = "O código interno é obrigatório.";
    if ($designacao === '')     $erros[] = "A designação é obrigatória.";
    if (!ctype_digit((string)$idCategoria))   $erros[] = "A categoria é obrigatória.";
    if (!ctype_digit((string)$idLocalizacao)) $erros[] = "A localização é obrigatória.";
    if ($estado_atual === '' || $estado_atual === 'Escolha...') $erros[] = "O estado é obrigatório.";
    if ($criticidade === '' || $criticidade === 'Escolha...')   $erros[] = "A criticidade é obrigatória.";
    // Fornecedor obrigatório + tipo de relação obrigatório
    if (empty($_POST['fornecedores']) || !is_array($_POST['fornecedores'])) {
        $erros[] = "Adicione pelo menos um fornecedor.";
    } else {
        $temFornecedorValido = false;
        $n = 0;

        foreach ($_POST['fornecedores'] as $f) {
            $n++;

            $idForn = $f['id_fornecedor'] ?? '';
            $tipoRel = $f['tipo_relacao'] ?? '';

            if (!ctype_digit((string)$idForn)) {
                $erros[] = "Fornecedor $n: selecione um fornecedor.";
            }

            if ($tipoRel === '' || $tipoRel === 'Escolha...') {
                $erros[] = "Fornecedor $n: selecione o tipo de relação.";
            }

            if (ctype_digit((string)$idForn) && $tipoRel !== '' && $tipoRel !== 'Escolha...') {
                $temFornecedorValido = true;
            }
        }

        if (!$temFornecedorValido) {
            $erros[] = "É obrigatório associar pelo menos um fornecedor válido.";
        }
    }

    // RI3: custo >= 0
    if ($custo !== '' && (!is_numeric($custo) || $custo < 0)) $erros[] = "O custo não pode ser negativo.";
    // RI4: ano de fabrico entre 1950 e o ano atual
    if ($ano_fabrico !== '' && (!ctype_digit($ano_fabrico) || $ano_fabrico < 1950 || $ano_fabrico > (int)date('Y')))
        $erros[] = "O ano de fabrico deve estar entre 1950 e " . date('Y') . ".";
    // RI5: data de aquisição não no futuro
    if ($data_aquisicao !== '' && $data_aquisicao > date('Y-m-d')) $erros[] = "A data de aquisição não pode ser no futuro.";
    // Datas reais (formato AAAA-MM-DD + data existente)
    if (!data_real($data_aquisicao))       $erros[] = "Data de aquisição inválida.";
    if (!data_real($data_inicio_garantia)) $erros[] = "Data de início da garantia inválida.";
    if (!data_real($data_fim_garantia))    $erros[] = "Data de fim da garantia inválida.";

    // RI1: se há garantia com as duas datas, fim >= início
    if ($tem_garantia && $data_inicio_garantia !== '' && $data_fim_garantia !== '' && $data_fim_garantia < $data_inicio_garantia) {
        $erros[] = "A data de fim da garantia não pode ser anterior à data de início.";
    }

    // Documentos: validar cada documento preenchido
    if (!empty($_POST['documentos']) && is_array($_POST['documentos'])) {
        $n = 0;
        foreach ($_POST['documentos'] as $doc) {
            $n++;
            $cod     = trim($doc['codigo_documento'] ?? '');
            $tipo    = $doc['tipo_documento'] ?? '';
            $nomeDoc = trim($doc['nome_documento'] ?? '');
            $dataDoc = trim($doc['data_documento'] ?? '');
            $val     = trim($doc['validade'] ?? '');
            $estadoD = $doc['estado_documento'] ?? '';
            $ficheiroD = trim($doc['ficheiro'] ?? '');
            $obsD = trim($doc['observacoes_documentacao'] ?? '');

            $temDocumento = (
                $tipo !== '' ||
                $nomeDoc !== '' ||
                $dataDoc !== '' ||
                $val !== '' ||
                $estadoD !== '' ||
                $ficheiroD !== '' ||
                $obsD !== ''
            );

            if (!$temDocumento) continue;

            if ($cod === '') {
                $erros[] = "Documento $n: o código é obrigatório.";
            }
            if ($tipo === '' || $tipo === 'Escolha...') {
                $erros[] = "Documento $n: o tipo é obrigatório.";
            }
            // RI2: validade não pode ser anterior à data do documento
            if ($dataDoc !== '' && $val !== '' && $val < $dataDoc) {
                $erros[] = "Documento $n: a validade não pode ser anterior à data do documento.";
            }
            if (!data_real($dataDoc)) $erros[] = "Documento $n: data do documento inválida.";
            if (!data_real($val))     $erros[] = "Documento $n: data de validade inválida.";
        }
    }

    // Componentes: validar cada componente preenchido
    if (!empty($_POST['componentes']) && is_array($_POST['componentes'])) {
        $n = 0;
        foreach ($_POST['componentes'] as $comp) {
            $n++;
            $cod  = trim($comp['codigo_componente'] ?? '');
            $nome = trim($comp['nome_componente'] ?? '');
            if ($cod === '' && $nome === '') continue; // linha vazia
            if ($cod === '')  $erros[] = "Componente $n: o código é obrigatório.";
            if ($nome === '') $erros[] = "Componente $n: o nome é obrigatório.";
        }
    }
*/
    // 2. Validar (centralizado em validacoes.php)
    $erros = validar_equipamento([
        'codigo_interno'       => $codigo_interno,
        'designacao'           => $designacao,
        'idCategoria'          => $idCategoria,
        'idLocalizacao'        => $idLocalizacao,
        'estado_atual'         => $estado_atual,
        'criticidade'          => $criticidade,
        'custo'                => $custo,
        'ano_fabrico'          => $ano_fabrico,
        'data_aquisicao'       => $data_aquisicao,
        'tem_garantia'         => $tem_garantia,
        'data_inicio_garantia' => $data_inicio_garantia,
        'data_fim_garantia'    => $data_fim_garantia,
        'fornecedores'         => $_POST['fornecedores'] ?? [],
        'documentos'           => $_POST['documentos'] ?? [],
        'componentes'          => $_POST['componentes'] ?? [],
    ]);

    // 3. Inserir se não houver erros (transação: equipamento + fornecedores)
    if (empty($erros)) {
        try {
            // uploads opcionais (garantia / contrato); devolvem o nome ou null
            $ficheiro_garantia = guarda_ficheiro_upload('ficheiro_garantia', 'garantia');
            if ($ficheiro_garantia) $ficheiros_guardados[] = $ficheiro_garantia;
            $ficheiro_contrato = guarda_ficheiro_upload('ficheiro_contrato', 'contrato');
            if ($ficheiro_contrato) $ficheiros_guardados[] = $ficheiro_contrato;

            $ligacao = liga_bd();
            $ligacao->beginTransaction();

            $sql = "INSERT INTO Equipamentos
                    (codigo_interno, designacao, idCategoria, idLocalizacao, marca, modelo,
                     numero_serie, data_aquisicao, ano_fabrico, custo, tipo_entrada,
                     estado_atual, criticidade, observacoes)
                    VALUES
                    (:codigo_interno, :designacao, :idCategoria, :idLocalizacao, :marca, :modelo,
                     :numero_serie, :data_aquisicao, :ano_fabrico, :custo, :tipo_entrada,
                     :estado_atual, :criticidade, :observacoes)";
            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':codigo_interno' => $codigo_interno,
                ':designacao'     => $designacao,
                ':idCategoria'    => $idCategoria,
                ':idLocalizacao'  => $idLocalizacao,
                ':marca'          => $marca ?: null,
                ':modelo'         => $modelo ?: null,
                ':numero_serie'   => $numero_serie ?: null,
                ':data_aquisicao' => $data_aquisicao ?: null,
                ':ano_fabrico'    => $ano_fabrico !== '' ? $ano_fabrico : null,
                ':custo'          => $custo !== '' ? round((float)$custo, 2) : null,
                ':tipo_entrada'   => ($tipo_entrada && $tipo_entrada !== 'Escolha...') ? $tipo_entrada : null,
                ':estado_atual'   => $estado_atual,
                ':criticidade'    => $criticidade,
                ':observacoes'    => $observacoes ?: null
            ]);

            // id do equipamento criado, para usar como chave estrangeira
            $idEquipamento = $ligacao->lastInsertId();

            // INSERT dos fornecedores associados (pode haver vários)
            if (!empty($_POST['fornecedores']) && is_array($_POST['fornecedores'])) {
                $sqlF = "INSERT INTO Equipamento_Fornecedor (idEquipamento, idFornecedor, tipo_relacao)
                         VALUES (:idEquipamento, :idFornecedor, :tipo_relacao)";
                $stmtF = $ligacao->prepare($sqlF);
                foreach ($_POST['fornecedores'] as $f) {
                    $idForn  = $f['id_fornecedor'] ?? '';
                    $tipoRel = $f['tipo_relacao'] ?? '';
                    // só insere as linhas realmente preenchidas
                    if (ctype_digit((string)$idForn) && $tipoRel !== '') {
                        $stmtF->execute([
                            ':idEquipamento' => $idEquipamento,
                            ':idFornecedor'  => $idForn,
                            ':tipo_relacao'  => $tipoRel
                        ]);
                    }
                }
            }

            $tem_garantia = (
                ctype_digit((string)$entidade_garantia) ||
                $data_inicio_garantia !== '' ||
                $data_fim_garantia !== '' ||
                ($estado_garantia !== '' && $estado_garantia !== 'Escolha...') ||
                $obs_garantia !== '' ||
                !empty($ficheiro_garantia)
            );

            // INSERT da garantia só se houver dados reais
            if ($tem_garantia && $codigo_garantia !== '') {
                $sqlG = "INSERT INTO Garantias
                         (idEquipamento, idEntidade, codigo_garantia, data_inicio, data_fim, estado_garantia, ficheiro_garantia, observacoes)
                         VALUES
                         (:idEquipamento, :idEntidade, :codigo_garantia, :data_inicio, :data_fim, :estado_garantia, :ficheiro_garantia, :observacoes)";
                $stmtG = $ligacao->prepare($sqlG);
                $stmtG->execute([
                    ':idEquipamento'     => $idEquipamento,
                    ':idEntidade'        => ctype_digit((string)$entidade_garantia) ? $entidade_garantia : null,
                    ':codigo_garantia'   => $codigo_garantia,
                    ':data_inicio'       => $data_inicio_garantia ?: null,
                    ':data_fim'          => $data_fim_garantia ?: null,
                    ':estado_garantia'   => ($estado_garantia && $estado_garantia !== 'Escolha...') ? $estado_garantia : null,
                    ':ficheiro_garantia' => $ficheiro_garantia ?: null,
                    ':observacoes'       => $obs_garantia ?: null
                ]);
            }

            // INSERT do contrato (só se existir contrato e tiver código)
            if ($existe_contrato === 'Sim' && $codigo_contrato !== '') {
                $tipoC = ($tipo_contrato && $tipo_contrato !== 'Escolha...' && $tipo_contrato !== 'Sem Contrato') ? $tipo_contrato : null;
                $sqlC = "INSERT INTO Contratos
                         (idEquipamento, idEntidade, codigo_contrato, tipo_contrato, periodicidade, ficheiro_contrato, observacoes)
                         VALUES
                         (:idEquipamento, :idEntidade, :codigo_contrato, :tipo_contrato, :periodicidade, :ficheiro_contrato, :observacoes)";
                $stmtC = $ligacao->prepare($sqlC);
                $stmtC->execute([
                    ':idEquipamento'     => $idEquipamento,
                    ':idEntidade'        => ctype_digit((string)$entidade_contrato) ? $entidade_contrato : null,
                    ':codigo_contrato'   => $codigo_contrato,
                    ':tipo_contrato'     => $tipoC,
                    ':periodicidade'     => ($periodicidade && $periodicidade !== 'Escolha...') ? $periodicidade : null,
                    ':ficheiro_contrato' => $ficheiro_contrato ?: null,
                    ':observacoes'       => $obs_contrato ?: null
                ]);
            }

            // INSERT dos documentos (os que têm código)
            if (!empty($_POST['documentos']) && is_array($_POST['documentos'])) {
                $sqlD = "INSERT INTO Documentos
                         (idEquipamento, codigo_documento, tipo_documento, nome_documento,
                          data_documento, validade, estado_documento, ficheiro, observacoes)
                         VALUES
                         (:idEquipamento, :codigo_documento, :tipo_documento, :nome_documento,
                          :data_documento, :validade, :estado_documento, :ficheiro, :observacoes)";
                $stmtD = $ligacao->prepare($sqlD);
                foreach ($_POST['documentos'] as $i => $doc) {
                    $cod     = trim($doc['codigo_documento'] ?? '');
                    $tipo    = $doc['tipo_documento'] ?? '';
                    $nomeDoc = trim($doc['nome_documento'] ?? '');
                    $dataDoc = trim($doc['data_documento'] ?? '');
                    $val     = trim($doc['validade'] ?? '');
                    $estadoD = $doc['estado_documento'] ?? '';
                    $obsD    = trim($doc['observacoes_documentacao'] ?? '');

                    $temDocumento = (
                        $tipo !== '' ||
                        $nomeDoc !== '' ||
                        $dataDoc !== '' ||
                        $val !== '' ||
                        $estadoD !== '' ||
                        $obsD !== ''
                    );

                    if (!$temDocumento || $cod === '') continue;

                    // só faz o upload se o documento vai mesmo ser inserido
                    $ficheiroD = guarda_ficheiro_array(ficheiro_de_secao('documentos', $i, 'ficheiro'), 'documento');
                    if ($ficheiroD) $ficheiros_guardados[] = $ficheiroD;

                    $stmtD->execute([
                        ':idEquipamento'    => $idEquipamento,
                        ':codigo_documento' => $cod,
                        ':tipo_documento'   => ($tipo && $tipo !== 'Escolha...') ? $tipo : null,
                        ':nome_documento'   => $nomeDoc ?: null,
                        ':data_documento'   => $dataDoc ?: null,
                        ':validade'         => $val ?: null,
                        ':estado_documento' => ($estadoD && $estadoD !== 'Escolha...') ? $estadoD : null,
                        ':ficheiro'         => $ficheiroD ?: null,
                        ':observacoes'      => $obsD ?: null
                    ]);
                }
            }

            // INSERT dos componentes (os que têm código e nome)
            if (!empty($_POST['componentes']) && is_array($_POST['componentes'])) {
                $sqlComp = "INSERT INTO Componentes
                            (idEquipamento, codigo_componente, nome_componente, estado_componente)
                            VALUES (:idEquipamento, :codigo_componente, :nome_componente, :estado_componente)";
                $stmtComp = $ligacao->prepare($sqlComp);
                foreach ($_POST['componentes'] as $comp) {
                    $cod  = trim($comp['codigo_componente'] ?? '');
                    $nome = trim($comp['nome_componente'] ?? '');
                    if ($cod === '' || $nome === '') continue;
                    $est = $comp['estado_componente'] ?? '';
                    $stmtComp->execute([
                        ':idEquipamento'     => $idEquipamento,
                        ':codigo_componente' => $cod,
                        ':nome_componente'   => $nome,
                        ':estado_componente' => ($est && $est !== 'Escolha...') ? $est : null
                    ]);
                }
            }

            $ligacao->commit();
            $_SESSION['mensagem'] = 'Equipamento criado com sucesso.';
            $_SESSION['mensagem_tipo'] = 'success';
            header("Location: lista.php");
            exit;
        } catch (Exception $err) {
            if (isset($ligacao) && $ligacao->inTransaction()) {
                $ligacao->rollBack();
            }
            foreach ($ficheiros_guardados as $fnome) {
                if (is_file(PASTA_UPLOADS . $fnome)) {
                    unlink(PASTA_UPLOADS . $fnome);
                }
            }
            $erro_sistema = erro_bd_equipamento($err, 'guardar');

            /*
            $msg = $err->getMessage();

            if ($err instanceof PDOException && strpos($msg, '23000') !== false) {
                if (strpos($msg, 'codigo_interno') !== false) {
                    $erro_sistema = "Já existe um equipamento com esse código interno.";
                } elseif (strpos($msg, 'codigo_documento') !== false) {
                    $erro_sistema = "Já existe um documento com esse código.";
                } elseif (strpos($msg, 'codigo_garantia') !== false) {
                    $erro_sistema = "Já existe uma garantia com esse código.";
                } elseif (strpos($msg, 'codigo_contrato') !== false) {
                    $erro_sistema = "Já existe um contrato com esse código.";
                } elseif (strpos($msg, 'codigo_componente') !== false) {
                    $erro_sistema = "Já existe um componente com esse código.";
                } elseif (strpos($msg, 'Equipamentos_index_0') !== false) {
                    $erro_sistema = "Já existe um equipamento com a mesma marca/modelo/número de série.";
                } elseif (strpos($msg, 'Equipamento_Fornecedor_index_1') !== false) {
                    $erro_sistema = "Esse fornecedor já foi associado a este equipamento com o mesmo tipo de relação.";
                } elseif (stripos($msg, 'foreign key') !== false) {
                    $erro_sistema = "Foi selecionada uma categoria, localização ou fornecedor inválido.";
                } else {
                    $erro_sistema = "Já existe um registo duplicado.";
                }
            } elseif (
                strpos($msg, 'ficheiro excede') !== false ||
                strpos($msg, 'Tipo de ficheiro') !== false ||
                strpos($msg, 'carregar o ficheiro') !== false ||
                strpos($msg, 'guardar o ficheiro') !== false
            ) {

                $erro_sistema = $msg;
            } elseif (strpos($msg, 'Data too long') !== false || strpos($msg, 'too long') !== false) {
                $erro_sistema = "Um dos campos tem texto demasiado comprido.";
            } elseif (strpos($msg, 'Incorrect') !== false || strpos($msg, 'Data truncated') !== false) {
                $erro_sistema = "Um dos valores selecionados não é válido.";
            } else {
                $erro_sistema = "Não foi possível guardar o equipamento. Verifique os dados e tente novamente.";
            }
*/
        }
        $ligacao = null;
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1"><i class="fa-solid fa-plus me-2"></i>Inserir Equipamento</h2>
            <p class="text-muted mb-0">Preencha as informações do equipamento médico por etapas.</p>
        </div>

        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-body p-4">

                <?php if (!empty($erros)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Foram encontrados os seguintes erros:</strong>
                        <ul class="mb-0">
                            <?php foreach ($erros as $e) : ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erro_sistema)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Erro:</strong> <?= htmlspecialchars($erro_sistema) ?>
                    </div>
                <?php endif; ?>

                <div id="aviso-passos" class="alert alert-warning d-none" role="alert"></div>

                <form id="form-equipamento" action="#" method="post" enctype="multipart/form-data">

                    <!-- Separadores / Passos -->
                    <ul class="nav nav-tabs mb-4" id="equipamentoTabs">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#passo-identificacao" type="button">1. Identificação</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-aquisicao" type="button">2. Aquisição</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-fornecedor" type="button">3. Fornecedor</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-localizacao" type="button">4. Localização</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-documentacao" type="button">5. Documentação</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-garantia" type="button">6. Garantia</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-contrato" type="button">7. Contrato</button></li>
                    </ul>

                    <div class="tab-content">

                        <!-- PASSO 1 -->
                        <div class="tab-pane fade show active" id="passo-identificacao">
                            <h5 class="mb-3">Identificação</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="codigo_interno" class="form-label">Código interno</label>
                                    <input type="text" class="form-control" id="codigo_interno"
                                        name="codigo_interno" value="<?= htmlspecialchars($_POST['codigo_interno'] ?? formata_codigo('EQ', $proximo_eq_num)) ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="designacao" class="form-label">Designação do equipamento</label>
                                    <input type="text" class="form-control" id="designacao" name="designacao" value="<?= htmlspecialchars($_POST['designacao'] ?? '') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option value="" selected disabled>Escolha...</option>
                                        <?php foreach ($lista_categorias as $cat) : ?>
                                            <option value="<?= $cat->idCategoria ?>" <?= (($_POST['categoria'] ?? '') == $cat->idCategoria) ? 'selected' : '' ?>><?= htmlspecialchars($cat->nome) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Informação Técnica</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="marca" name="marca" value="<?= htmlspecialchars($_POST['marca'] ?? '') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="modelo" name="modelo" value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="numero_serie" class="form-label">Número de série</label>
                                    <input type="text" class="form-control" id="numero_serie" name="numero_serie" value="<?= htmlspecialchars($_POST['numero_serie'] ?? '') ?>">
                                </div>

                            </div>

                            <hr>

                            <h5 class="mb-3">Componentes associados</h5>

                            <p class="text-muted">
                                Opcional. Adicione apenas se o equipamento tiver componentes associados.
                            </p>

                            <div id="componentes-container" data-proximo="<?= $proximo_comp_num ?>"></div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-componente">
                                <i class="fa-solid fa-plus me-1"></i>
                                Adicionar componente
                            </button>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="lista.php" class="btn btn-outline-secondary"><i
                                        class="fa-solid fa-xmark me-1"></i>Cancelar</a>
                                <button type="button" class="btn btn-pink btn-seguinte"
                                    data-passo-atual="0">Seguinte <i
                                        class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 2 -->
                        <div class="tab-pane fade" id="passo-aquisicao">
                            <h5 class="mb-3">Aquisição</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="data_aquisicao" class="form-label">Data de aquisição</label>
                                    <input type="text" class="form-control flatpickr-date" id="data_aquisicao"
                                        name="data_aquisicao" value="<?= htmlspecialchars($_POST['data_aquisicao'] ?? '') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="ano_fabrico" class="form-label">Ano de fabrico</label>
                                    <input type="number" class="form-control" id="ano_fabrico" name="ano_fabrico" value="<?= htmlspecialchars($_POST['ano_fabrico'] ?? '') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="custo" class="form-label">Custo de aquisição</label>
                                    <input type="number" class="form-control" id="custo" name="custo" step="0.01" value="<?= htmlspecialchars($_POST['custo'] ?? '') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="tipo_entrada" class="form-label">Tipo de entrada</label>
                                    <select class="form-select" id="tipo_entrada" name="tipo_entrada">
                                        <option value="" selected disabled>Escolha...</option>
                                        <?php foreach (['Compra', 'Doação', 'Aluguer', 'Empréstimo'] as $op) : ?>
                                            <option <?= (($_POST['tipo_entrada'] ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Estado | Criticidade</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="estado_atual" class="form-label">Estado atual</label>
                                    <select class="form-select" id="estado_atual" name="estado_atual">
                                        <option value="" selected disabled>Escolha...</option>
                                        <?php foreach (['Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido'] as $op) : ?>
                                            <option <?= (($_POST['estado_atual'] ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="criticidade" class="form-label">Criticidade</label>
                                    <select class="form-select" id="criticidade" name="criticidade">
                                        <option value="" selected disabled>Escolha...</option>
                                        <?php foreach (['Baixa', 'Média', 'Alta', 'Suporte de vida'] as $op) : ?>
                                            <option <?= (($_POST['criticidade'] ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Observações</h5>
                            <textarea class="form-control mb-4" id="observacoes_equipamento"
                                name="observacoes_equipamento" rows="4"><?= htmlspecialchars($_POST['observacoes_equipamento'] ?? '') ?></textarea>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior"
                                    data-passo-atual="1"><i
                                        class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte"
                                    data-passo-atual="1">Seguinte <i
                                        class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 3 -->
                        <div class="tab-pane fade" id="passo-fornecedor">

                            <h5 class="mb-3">
                                <i class="fa-solid fa-truck me-2"></i>
                                Fornecedores Associados
                            </h5>

                            <p class="text-muted">
                                Associe pelo menos um fornecedor e indique obrigatoriamente o tipo de relação.
                            </p>

                            <div id="fornecedores-container">

                                <div class="fornecedor-bloco border rounded-4 p-3 mb-3">

                                    <div class="row align-items-end mb-3">

                                        <div class="col-md-5">
                                            <label class="form-label">Fornecedor</label>
                                            <select class="form-select" name="fornecedores[0][id_fornecedor]">
                                                <option value="" selected disabled>Escolha...</option>
                                                <?php foreach ($lista_fornecedores as $forn) : ?>
                                                    <option value="<?= $forn->idFornecedor ?>"
                                                        data-nif="<?= htmlspecialchars($forn->nif ?? '') ?>"
                                                        data-telefone="<?= htmlspecialchars($forn->telefone ?? '') ?>"
                                                        data-email="<?= htmlspecialchars($forn->email ?? '') ?>"
                                                        <?= (($_POST['fornecedores'][0]['id_fornecedor'] ?? '') == $forn->idFornecedor) ? 'selected' : '' ?>><?= htmlspecialchars($forn->nome_empresa) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label">Tipo de relação</label>
                                            <select class="form-select" name="fornecedores[0][tipo_relacao]">
                                                <option value="" disabled <?= empty($_POST['fornecedores'][0]['tipo_relacao']) ? 'selected' : '' ?>>Escolha...</option>
                                                <?php foreach (['Fabricante', 'Distribuidor ou fornecedor comercial', 'Empresa de assistência técnica', 'Fornecedor de consumíveis ou acessórios'] as $opt): ?>
                                                    <option <?= (($_POST['fornecedores'][0]['tipo_relacao'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <button type="button"
                                                class="btn btn-outline-danger remover-fornecedor w-100 d-none">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>

                                    <div class="border rounded-4 p-3 bg-light">
                                        <h6 class="mb-3">Dados do fornecedor selecionado</h6>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">NIF</label>
                                                <input type="text" class="form-control"
                                                    name="fornecedores[0][nif_fornecedor]" readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Telefone</label>
                                                <input type="text" class="form-control"
                                                    name="fornecedores[0][telefone_fornecedor]" readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control"
                                                    name="fornecedores[0][email_fornecedor]" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-fornecedor">
                                <i class="fa-solid fa-plus me-1"></i>
                                Adicionar fornecedor
                            </button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior"
                                    data-passo-atual="2">
                                    <i class="fa-solid fa-arrow-left me-1"></i>
                                    Anterior
                                </button>

                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="2">
                                    Seguinte
                                    <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>

                        </div>

                        <!-- PASSO 4 -->
                        <div class="tab-pane fade" id="passo-localizacao">
                            <h5 class="mb-3"><i class="fa-solid fa-location-dot me-2"></i>Localização Associada</h5>

                            <div class="mb-3">
                                <label for="localizacao_associada" class="form-label">Selecionar localização</label>
                                <select class="form-select" id="localizacao_associada" name="localizacao_associada">
                                    <option value="" selected disabled>Escolha...</option>
                                    <?php foreach ($lista_localizacoes as $loc) : ?>
                                        <option value="<?= $loc->idLocalizacao ?>"
                                            data-edificio="<?= htmlspecialchars($loc->edificio ?? '') ?>"
                                            data-piso="<?= htmlspecialchars($loc->piso ?? '') ?>"
                                            data-servico="<?= htmlspecialchars($loc->servico ?? '') ?>"
                                            data-sala="<?= htmlspecialchars($loc->sala ?? '') ?>"
                                            <?= (($_POST['localizacao_associada'] ?? '') == $loc->idLocalizacao) ? 'selected' : '' ?>><?= htmlspecialchars($loc->servico) ?><?= $loc->sala ? ' — ' . htmlspecialchars($loc->sala) : '' ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="border rounded-4 p-3 bg-light mb-3">
                                <h6 class="mb-3">Dados da localização selecionada</h6>
                                <div class="row">
                                    <div class="col-md-3"><label class="form-label">Edifício</label><input
                                            type="text" class="form-control" id="localizacao_edificio" readonly>
                                    </div>
                                    <div class="col-md-2"><label class="form-label">Piso</label><input type="text"
                                            class="form-control" id="localizacao_piso" readonly></div>
                                    <div class="col-md-4"><label class="form-label">Serviço |
                                            Departamento</label><input type="text" class="form-control"
                                            id="localizacao_departamento" readonly></div>
                                    <div class="col-md-3"><label class="form-label">Sala</label><input type="text"
                                            class="form-control" id="localizacao_sala" readonly></div>
                                </div>
                            </div>

                            <p class="text-muted mb-0">A localização deve existir previamente no módulo de
                                localizações.</p>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior"
                                    data-passo-atual="3"><i
                                        class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte"
                                    data-passo-atual="3">Seguinte <i
                                        class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 5 -->
                        <div class="tab-pane fade" id="passo-documentacao">
                            <h5 class="mb-3"><i class="fa-solid fa-file-pdf me-2"></i>Documentação Associada</h5>
                            <p class="text-muted">Opcional. Adicione documentos apenas se existirem.
                            </p>

                            <div id="documentos-container" data-proximo="<?= $proximo_doc_num ?>">
                                <div class="documento-bloco border rounded-4 p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Documento 1</h6>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger remover-documento d-none"><i
                                                class="fa-solid fa-trash"></i> Remover</button>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4"><label class="form-label">Código</label><input
                                                type="text" class="form-control"
                                                name="documentos[0][codigo_documento]" value="<?= htmlspecialchars($_POST['documentos'][0]['codigo_documento'] ?? '') ?>"
                                                placeholder="<?= htmlspecialchars(formata_codigo('DOC', $proximo_doc_num)) ?>"></div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tipo de documento</label>
                                            <select class="form-select" name="documentos[0][tipo_documento]">
                                                <option value="" disabled <?= empty($_POST['documentos'][0]['tipo_documento']) ? 'selected' : '' ?>>Escolha...</option>
                                                <?php foreach (['Manual de Utilizador', 'Manual de Serviço', 'Certificado de Calibração', 'Fatura ou Guia de Aquisição', 'Declaração de Conformidade', 'Relatório Técnico'] as $opt): ?>
                                                    <option <?= (($_POST['documentos'][0]['tipo_documento'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4"><label class="form-label">Nome do
                                                documento</label><input type="text" class="form-control"
                                                name="documentos[0][nome_documento]"
                                                value="<?= htmlspecialchars($_POST['documentos'][0]['nome_documento'] ?? '') ?>"></div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4"><label class="form-label">Data do
                                                documento</label><input type="text" class="form-control flatpickr-date"
                                                name="documentos[0][data_documento]"
                                                value="<?= htmlspecialchars($_POST['documentos'][0]['data_documento'] ?? '') ?>"></div>
                                        <div class="col-md-4"><label class="form-label">Data de
                                                validade</label><input type="text" class="form-control flatpickr-date"
                                                name="documentos[0][validade]"
                                                value="<?= htmlspecialchars($_POST['documentos'][0]['validade'] ?? '') ?>"></div>
                                        <div class="col-md-4">
                                            <label class="form-label">Estado</label>
                                            <select class="form-select" name="documentos[0][estado_documento]">
                                                <option value="" disabled <?= empty($_POST['documentos'][0]['estado_documento']) ? 'selected' : '' ?>>Escolha...</option>
                                                <?php foreach (['Ativo', 'Prestes a Expirar', 'Expirado', 'Pendente', 'Anulado', 'Estendido', 'Não disponível'] as $opt): ?>
                                                    <option <?= (($_POST['documentos'][0]['estado_documento'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Ficheiro</label>
                                            <input type="file" class="form-control" name="documentos[0][ficheiro]"
                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control"
                                            name="documentos[0][observacoes_documentacao]" rows="3"><?= htmlspecialchars($_POST['documentos'][0]['observacoes_documentacao'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-documento"><i
                                    class="fa-solid fa-plus me-1"></i>Adicionar documento</button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior"
                                    data-passo-atual="4"><i
                                        class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte"
                                    data-passo-atual="4">Seguinte <i
                                        class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 6 -->
                        <div class="tab-pane fade" id="passo-garantia">
                            <h5 class="mb-3"><i class="fa-solid fa-shield-halved me-2"></i>Garantia</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="codigo_garantia" class="form-label">Código da
                                        garantia</label>
                                    <input type="text" class="form-control" id="codigo_garantia"
                                        name="codigo_garantia" value="<?= htmlspecialchars($_POST['codigo_garantia'] ?? formata_codigo('GAR', $proximo_gar_num)) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="entidade_garantia" class="form-label">Entidade responsável</label>
                                    <select class="form-select" id="entidade_garantia" name="entidade_garantia">
                                        <option value="" selected disabled>Escolha...</option>
                                        <?php foreach ($lista_fornecedores as $forn) : ?>
                                            <option value="<?= $forn->idFornecedor ?>" <?= (($_POST['entidade_garantia'] ?? '') == $forn->idFornecedor) ? 'selected' : '' ?>><?= htmlspecialchars($forn->nome_empresa) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3"><label for="data_inicio_garantia" class="form-label">Data de
                                        início</label><input type="text" class="form-control flatpickr-date"
                                        id="data_inicio_garantia" name="data_inicio_garantia"
                                        value="<?= htmlspecialchars($_POST['data_inicio_garantia'] ?? '') ?>"></div>
                                <div class="col-md-3"><label for="data_fim_garantia" class="form-label">Data de
                                        fim</label><input type="text" class="form-control flatpickr-date" id="data_fim_garantia"
                                        name="data_fim_garantia"
                                        value="<?= htmlspecialchars($_POST['data_fim_garantia'] ?? '') ?>"></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="estado_garantia" class="form-label">Estado da garantia</label>
                                    <select class="form-select" id="estado_garantia" name="estado_garantia">
                                        <option value="" disabled <?= empty($_POST['estado_garantia']) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Ativa', 'Prestes a Expirar', 'Expirada', 'Pendente', 'Anulada', 'Estendida', 'Não disponível'] as $opt): ?>
                                            <option <?= (($_POST['estado_garantia'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="ficheiro_garantia" class="form-label">Documento da garantia</label>
                                    <input type="file" class="form-control" id="ficheiro_garantia"
                                        name="ficheiro_garantia" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="observacoes_garantia" class="form-label">Observações da garantia</label>
                                <textarea class="form-control" id="observacoes_garantia" name="observacoes_garantia"
                                    rows="4"><?= htmlspecialchars($_POST['observacoes_garantia'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior"
                                    data-passo-atual="5"><i
                                        class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte"
                                    data-passo-atual="5">Seguinte <i
                                        class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 7 -->
                        <div class="tab-pane fade" id="passo-contrato">
                            <h5 class="mb-3"><i class="fa-solid fa-file-contract me-2"></i>Contrato de Manutenção
                            </h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="existe_contrato" class="form-label">Existe contrato de
                                        manutenção?</label>
                                    <select class="form-select" id="existe_contrato" name="existe_contrato">
                                        <option value="" disabled <?= empty($_POST['existe_contrato']) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Sim', 'Não'] as $opt): ?>
                                            <option <?= (($_POST['existe_contrato'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="codigo_contrato" class="form-label">Código do contrato</label>
                                    <input type="text" class="form-control" id="codigo_contrato" name="codigo_contrato" value="<?= htmlspecialchars($_POST['codigo_contrato'] ?? formata_codigo('CON', $proximo_con_num)) ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="tipo_contrato" class="form-label">Tipo de contrato</label>
                                    <select class="form-select" id="tipo_contrato" name="tipo_contrato">
                                        <option value="" disabled <?= empty($_POST['tipo_contrato']) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Contrato de Manutenção', 'Manutenção Preventiva', 'Contrato de Assistência Técnica', 'Sem Contrato'] as $opt): ?>
                                            <option <?= (($_POST['tipo_contrato'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="entidade_contrato" class="form-label">Entidade responsável</label>
                                    <select class="form-select" id="entidade_contrato" name="entidade_contrato">
                                        <option value="" selected disabled>Escolha...</option>
                                        <?php foreach ($lista_fornecedores as $forn) : ?>
                                            <option value="<?= $forn->idFornecedor ?>" <?= (($_POST['entidade_contrato'] ?? '') == $forn->idFornecedor) ? 'selected' : '' ?>><?= htmlspecialchars($forn->nome_empresa) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="periodicidade" class="form-label">Periodicidade</label>
                                    <select class="form-select" id="periodicidade" name="periodicidade">
                                        <option value="" disabled <?= empty($_POST['periodicidade']) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Mensal', 'Trimestral', 'Semestral', 'Anual', 'Bianual'] as $opt): ?>
                                            <option <?= (($_POST['periodicidade'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="ficheiro_contrato" class="form-label">Ficheiro do contrato</label>
                                    <input type="file" class="form-control" id="ficheiro_contrato"
                                        name="ficheiro_contrato" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="observacoes_contrato" class="form-label">Observações do contrato</label>
                                <textarea class="form-control" id="observacoes_contrato" name="observacoes_contrato"
                                    rows="4"><?= htmlspecialchars($_POST['observacoes_contrato'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior"
                                    data-passo-atual="6"><i
                                        class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="submit" class="btn btn-pink"><i
                                        class="fa-regular fa-floppy-disk me-1"></i>Guardar equipamento</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>