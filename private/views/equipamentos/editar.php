<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

// permitir apenas GET e POST (Ficha 13)
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'equipamentos';
$erros = [];
$erro_sistema = '';
$lista_categorias = [];
$lista_localizacoes = [];
$lista_fornecedores = [];
$ficheiros_guardados = [];
$mapa_doc_uploads = [];
$proximo_gar_num  = 1;
$proximo_con_num  = 1;
$proximo_doc_num  = 1;
$proximo_comp_num = 1;

// desencriptar e validar o ID (Ficha 13)
$idEncriptado = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEncriptado);
if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}

// POST: validar e atualizar (tratado antes do SELECT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    // 3. Atualizar se não houver erros (transação: equipamento + filhos)
    if (empty($erros)) {
        try {
            // uploads novos (opcionais); se enviados, substituem os atuais
            $novo_fich_gar = guarda_ficheiro_upload('ficheiro_garantia', 'garantia');
            if ($novo_fich_gar) $ficheiros_guardados[] = $novo_fich_gar;
            $novo_fich_con = guarda_ficheiro_upload('ficheiro_contrato', 'contrato');
            if ($novo_fich_con) $ficheiros_guardados[] = $novo_fich_con;

            $ligacao = liga_bd();

            // ficheiros atuais (para manter se não houver upload novo)
            $stmtTmp = $ligacao->prepare("SELECT ficheiro_garantia FROM Garantias WHERE idEquipamento = :id");
            $stmtTmp->execute([':id' => $idEquipamento]);
            $old_fich_gar = $stmtTmp->fetchColumn() ?: null;
            $stmtTmp = $ligacao->prepare("SELECT ficheiro_contrato FROM Contratos WHERE idEquipamento = :id");
            $stmtTmp->execute([':id' => $idEquipamento]);
            $old_fich_con = $stmtTmp->fetchColumn() ?: null;

            $ficheiro_garantia = $novo_fich_gar ?: $old_fich_gar;
            $ficheiro_contrato = $novo_fich_con ?: $old_fich_con;
            $tem_garantia_final = $tem_garantia || !empty($novo_fich_gar);

            $ligacao->beginTransaction();

            // 1) UPDATE do equipamento
            $sql = "UPDATE Equipamentos SET
                        codigo_interno = :codigo_interno, designacao = :designacao,
                        idCategoria = :idCategoria, idLocalizacao = :idLocalizacao,
                        marca = :marca, modelo = :modelo, numero_serie = :numero_serie,
                        data_aquisicao = :data_aquisicao, ano_fabrico = :ano_fabrico,
                        custo = :custo, tipo_entrada = :tipo_entrada,
                        estado_atual = :estado_atual, criticidade = :criticidade,
                        observacoes = :observacoes
                    WHERE idEquipamento = :id";
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
                ':observacoes'    => $observacoes ?: null,
                ':id'             => $idEquipamento
            ]);

            // 2) Fornecedores: apagar e reinserir
            $ligacao->prepare("DELETE FROM Equipamento_Fornecedor WHERE idEquipamento = :id")->execute([':id' => $idEquipamento]);
            if (!empty($_POST['fornecedores']) && is_array($_POST['fornecedores'])) {
                $stmtF = $ligacao->prepare("INSERT INTO Equipamento_Fornecedor (idEquipamento, idFornecedor, tipo_relacao) VALUES (:idEquipamento, :idFornecedor, :tipo_relacao)");
                foreach ($_POST['fornecedores'] as $f) {
                    $idForn  = $f['id_fornecedor'] ?? '';
                    $tipoRel = $f['tipo_relacao'] ?? '';
                    if (ctype_digit((string)$idForn) && $tipoRel !== '' && $tipoRel !== 'Escolha...') {
                        $stmtF->execute([':idEquipamento' => $idEquipamento, ':idFornecedor' => $idForn, ':tipo_relacao' => $tipoRel]);
                    }
                }
            }

            // 3) Documentos: apagar e reinserir
            $ligacao->prepare("DELETE FROM Documentos WHERE idEquipamento = :id")->execute([':id' => $idEquipamento]);
            if (!empty($_POST['documentos']) && is_array($_POST['documentos'])) {
                $sqlD = "INSERT INTO Documentos
                         (idEquipamento, codigo_documento, tipo_documento, nome_documento,
                          data_documento, validade, estado_documento, ficheiro, observacoes)
                         VALUES
                         (:idEquipamento, :codigo_documento, :tipo_documento, :nome_documento,
                          :data_documento, :validade, :estado_documento, :ficheiro, :observacoes)";
                $stmtD = $ligacao->prepare($sqlD);
                foreach ($_POST['documentos'] as $i => $doc) {
                    $cod = trim($doc['codigo_documento'] ?? '');
                    $tipo = $doc['tipo_documento'] ?? '';
                    $nomeDoc = trim($doc['nome_documento'] ?? '');
                    $dataDoc = trim($doc['data_documento'] ?? '');
                    $val = trim($doc['validade'] ?? '');
                    $estadoD = $doc['estado_documento'] ?? '';
                    $ficheiroD_atual = trim($doc['ficheiro_atual'] ?? '');
                    $ficheiroD_novo  = guarda_ficheiro_array(ficheiro_de_secao('documentos', $i, 'ficheiro'), 'documento');
                    if ($ficheiroD_novo) $ficheiros_guardados[] = $ficheiroD_novo;
                    $ficheiroD = $ficheiroD_novo ?: $ficheiroD_atual;
                    $mapa_doc_uploads[$i] = ['antigo' => $ficheiroD_atual, 'novo' => $ficheiroD_novo]; // <-- adiciona esta linha
                    $obsD = trim($doc['observacoes_documentacao'] ?? '');
                    $temDocumento = ($tipo !== '' || $nomeDoc !== '' || $dataDoc !== '' || $val !== '' || $estadoD !== '' || $ficheiroD !== '' || $obsD !== '');
                    if (!$temDocumento || $cod === '') continue;
                    $stmtD->execute([
                        ':idEquipamento'    => $idEquipamento,
                        ':codigo_documento' => $cod,
                        ':tipo_documento'   => ($tipo && $tipo !== 'Escolha...') ? $tipo : null,
                        ':nome_documento'   => $nomeDoc ?: null,
                        ':data_documento'   => $dataDoc ?: null,
                        ':validade'         => $val ?: null,
                        ':estado_documento' => ($estadoD && $estadoD !== 'Escolha...') ? $estadoD : null,
                        ':ficheiro' => $ficheiroD ?: null,
                        ':observacoes'      => $obsD ?: null
                    ]);
                }
            }

            // 4) Componentes: apagar e reinserir
            $ligacao->prepare("DELETE FROM Componentes WHERE idEquipamento = :id")->execute([':id' => $idEquipamento]);
            if (!empty($_POST['componentes']) && is_array($_POST['componentes'])) {
                $sqlComp = "INSERT INTO Componentes (idEquipamento, codigo_componente, nome_componente, estado_componente)
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

            // 5) Garantia: apagar e reinserir
            $ligacao->prepare("DELETE FROM Garantias WHERE idEquipamento = :id")->execute([':id' => $idEquipamento]);
            if ($tem_garantia_final && $codigo_garantia !== '') {
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

            // 6) Contrato: apagar e reinserir
            $ligacao->prepare("DELETE FROM Contratos WHERE idEquipamento = :id")->execute([':id' => $idEquipamento]);
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

            $ligacao->commit();

            // apagar ficheiros antigos que deixaram de ser usados
            $fich_gar_final = ($tem_garantia_final && $codigo_garantia !== '') ? $ficheiro_garantia : null;
            if ($old_fich_gar && $old_fich_gar !== $fich_gar_final && is_file(PASTA_UPLOADS . $old_fich_gar)) unlink(PASTA_UPLOADS . $old_fich_gar);
            $fich_con_final = ($existe_contrato === 'Sim' && $codigo_contrato !== '') ? $ficheiro_contrato : null;
            if ($old_fich_con && $old_fich_con !== $fich_con_final && is_file(PASTA_UPLOADS . $old_fich_con)) unlink(PASTA_UPLOADS . $old_fich_con);
            // apagar ficheiros de documentos substituídos por upload novo
            foreach ($mapa_doc_uploads as $entrada) {
                $antigo = $entrada['antigo'];
                $novo   = $entrada['novo'];
                if ($novo && $antigo && $antigo !== $novo && is_file(PASTA_UPLOADS . $antigo)) {
                    unlink(PASTA_UPLOADS . $antigo);
                }
            }

            header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
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
            $erro_sistema = erro_bd_equipamento($err, 'atualizar');

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
                } elseif (stripos($msg, 'foreign key') !== false) {
                    $erro_sistema = "Foi selecionada uma categoria, localização ou fornecedor inválido.";
                } else {
                    $erro_sistema = "Já existe um registo duplicado.";
                }
            } elseif (strpos($msg, 'ficheiro excede') !== false || strpos($msg, 'Tipo de ficheiro') !== false || strpos($msg, 'carregar o ficheiro') !== false || strpos($msg, 'guardar o ficheiro') !== false) {
                $erro_sistema = $msg;
            } elseif (strpos($msg, 'too long') !== false) {
                $erro_sistema = "Um dos campos tem texto demasiado comprido.";
            } else {
                $erro_sistema = "Não foi possível atualizar o equipamento. Verifique os dados e tente novamente.";
            }
            */
        }
        $ligacao = null;
    }
}

// Carregar dropdowns, o equipamento e os registos filhos
$equipamento = null;
$fornecedores_bd = [];
$documentos_bd = [];
$componentes_bd = [];
$garantia = null;
$contrato = null;
try {
    $ligacao = liga_bd();
    $lista_categorias   = $ligacao->query("SELECT idCategoria, nome FROM Categorias ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $lista_localizacoes = $ligacao->query(
        "SELECT l.idLocalizacao, l.edificio, l.piso, l.sala, s.nome AS servico
         FROM Localizacoes l JOIN Servicos s ON l.idServico = s.idServico ORDER BY s.nome"
    )->fetchAll(PDO::FETCH_OBJ);
    $lista_fornecedores = $ligacao->query("SELECT idFornecedor, nome_empresa, nif, telefone, email FROM Fornecedores ORDER BY nome_empresa")->fetchAll(PDO::FETCH_OBJ);
    $proximo_gar_num  = proximo_numero_codigo($ligacao, 'Garantias', 'codigo_garantia', 'GAR');
    $proximo_con_num  = proximo_numero_codigo($ligacao, 'Contratos', 'codigo_contrato', 'CON');
    $proximo_doc_num  = proximo_numero_codigo($ligacao, 'Documentos', 'codigo_documento', 'DOC');
    $proximo_comp_num = proximo_numero_codigo($ligacao, 'Componentes', 'codigo_componente', 'COMP');

    $stmt = $ligacao->prepare("SELECT * FROM Equipamentos WHERE idEquipamento = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);
    if (!$equipamento) {
        header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
        exit;
    }

    $stmt = $ligacao->prepare("SELECT idFornecedor, tipo_relacao FROM Equipamento_Fornecedor WHERE idEquipamento = :id");
    $stmt->execute([':id' => $idEquipamento]);
    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
        $fornecedores_bd[] = ['id_fornecedor' => $r->idFornecedor, 'tipo_relacao' => $r->tipo_relacao];
    }

    $stmt = $ligacao->prepare("SELECT * FROM Documentos WHERE idEquipamento = :id AND ativo = 1");
    $stmt->execute([':id' => $idEquipamento]);
    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
        $documentos_bd[] = [
            'codigo_documento'         => $r->codigo_documento,
            'tipo_documento'           => $r->tipo_documento,
            'nome_documento'           => $r->nome_documento,
            'data_documento'           => $r->data_documento,
            'validade'                 => $r->validade,
            'estado_documento'         => $r->estado_documento,
            'ficheiro'                 => $r->ficheiro,
            'observacoes_documentacao' => $r->observacoes,
        ];
    }

    $stmt = $ligacao->prepare("SELECT codigo_componente, nome_componente, estado_componente FROM Componentes WHERE idEquipamento = :id");
    $stmt->execute([':id' => $idEquipamento]);
    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
        $componentes_bd[] = [
            'codigo_componente' => $r->codigo_componente,
            'nome_componente'   => $r->nome_componente,
            'estado_componente' => $r->estado_componente,
        ];
    }

    $stmt = $ligacao->prepare("SELECT * FROM Garantias WHERE idEquipamento = :id");
    $stmt->execute([':id' => $idEquipamento]);
    $garantia = $stmt->fetch(PDO::FETCH_OBJ) ?: null;

    $stmt = $ligacao->prepare("SELECT * FROM Contratos WHERE idEquipamento = :id");
    $stmt->execute([':id' => $idEquipamento]);
    $contrato = $stmt->fetch(PDO::FETCH_OBJ) ?: null;

    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar o equipamento.";
}

// fontes para preencher o formulário (POST tem prioridade; senão a BD)
$forn_dados = $_POST['fornecedores'] ?? $fornecedores_bd;
if (empty($forn_dados)) $forn_dados = [['id_fornecedor' => '', 'tipo_relacao' => '']];
$doc_dados = $_POST['documentos'] ?? $documentos_bd;
if (empty($doc_dados)) $doc_dados = [[]];
$comp_dados = $_POST['componentes'] ?? $componentes_bd;

// lookup para preencher os campos read-only no carregamento
$forn_lookup = [];
foreach ($lista_fornecedores as $f) $forn_lookup[$f->idFornecedor] = $f;
$loc_sel_id = $_POST['localizacao_associada'] ?? $equipamento?->idLocalizacao ?? '';
$loc_sel = null;
foreach ($lista_localizacoes as $l) if ($l->idLocalizacao == $loc_sel_id) $loc_sel = $l;

$existe_contrato_val = $_POST['existe_contrato'] ?? ($contrato ? 'Sim' : '');

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1"><i class="fa-regular fa-pen-to-square me-2"></i>Editar Equipamento</h2>
            <p class="text-muted mb-0">Atualize as informações do equipamento médico por etapas.</p>
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

                <form id="form-equipamento" action="editar.php?id_equipamento=<?= htmlspecialchars($idEncriptado) ?>" method="post" enctype="multipart/form-data">

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
                                    <input type="text" class="form-control" id="codigo_interno" name="codigo_interno" value="<?= htmlspecialchars($_POST['codigo_interno'] ?? $equipamento?->codigo_interno ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="designacao" class="form-label">Designação do equipamento</label>
                                    <input type="text" class="form-control" id="designacao" name="designacao" value="<?= htmlspecialchars($_POST['designacao'] ?? $equipamento?->designacao ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option value="" disabled <?= empty($_POST['categoria'] ?? $equipamento?->idCategoria) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach ($lista_categorias as $cat) : ?>
                                            <option value="<?= $cat->idCategoria ?>" <?= (($_POST['categoria'] ?? $equipamento?->idCategoria ?? '') == $cat->idCategoria) ? 'selected' : '' ?>><?= htmlspecialchars($cat->nome) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Informação Técnica</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="marca" name="marca" value="<?= htmlspecialchars($_POST['marca'] ?? $equipamento?->marca ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="modelo" name="modelo" value="<?= htmlspecialchars($_POST['modelo'] ?? $equipamento?->modelo ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="numero_serie" class="form-label">Número de série</label>
                                    <input type="text" class="form-control" id="numero_serie" name="numero_serie" value="<?= htmlspecialchars($_POST['numero_serie'] ?? $equipamento?->numero_serie ?? '') ?>">
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Componentes associados</h5>
                            <p class="text-muted">Opcional. Adicione apenas se o equipamento tiver componentes associados.</p>

                            <div id="componentes-container" data-proximo="<?= $proximo_comp_num ?>">
                                <?php $ci = 0;
                                foreach ($comp_dados as $cd) : ?>
                                    <div class="componente-bloco border rounded-4 p-3 mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label">Código</label>
                                                <input type="text" class="form-control" name="componentes[<?= $ci ?>][codigo_componente]" value="<?= htmlspecialchars($cd['codigo_componente'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Nome</label>
                                                <input type="text" class="form-control" name="componentes[<?= $ci ?>][nome_componente]" value="<?= htmlspecialchars($cd['nome_componente'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="componentes[<?= $ci ?>][estado_componente]">
                                                    <option value="" disabled <?= empty($cd['estado_componente'] ?? '') ? 'selected' : '' ?>>Escolha...</option>
                                                    <?php foreach (['Ativo', 'Inativo', 'Em manutenção'] as $op) : ?>
                                                        <option <?= (($cd['estado_componente'] ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-outline-danger remover-componente"><i class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php $ci++;
                                endforeach; ?>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-componente"><i class="fa-solid fa-plus me-1"></i>Adicionar componente</button>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="lista.php" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark me-1"></i>Cancelar</a>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="0">Seguinte <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 2 -->
                        <div class="tab-pane fade" id="passo-aquisicao">
                            <h5 class="mb-3">Aquisição</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="data_aquisicao" class="form-label">Data de aquisição</label>
                                    <input type="text" class="form-control flatpickr-date" id="data_aquisicao" name="data_aquisicao" value="<?= htmlspecialchars($_POST['data_aquisicao'] ?? $equipamento?->data_aquisicao ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="ano_fabrico" class="form-label">Ano de fabrico</label>
                                    <input type="number" class="form-control" id="ano_fabrico" name="ano_fabrico" value="<?= htmlspecialchars($_POST['ano_fabrico'] ?? $equipamento?->ano_fabrico ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="custo" class="form-label">Custo de aquisição</label>
                                    <input type="number" class="form-control" id="custo" name="custo" step="0.01" value="<?= htmlspecialchars($_POST['custo'] ?? $equipamento?->custo ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="tipo_entrada" class="form-label">Tipo de entrada</label>
                                    <select class="form-select" id="tipo_entrada" name="tipo_entrada">
                                        <option value="" disabled <?= empty($_POST['tipo_entrada'] ?? $equipamento?->tipo_entrada) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Compra', 'Doação', 'Aluguer', 'Empréstimo'] as $op) : ?>
                                            <option <?= (($_POST['tipo_entrada'] ?? $equipamento?->tipo_entrada ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
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
                                        <option value="" disabled <?= empty($_POST['estado_atual'] ?? $equipamento?->estado_atual) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido'] as $op) : ?>
                                            <option <?= (($_POST['estado_atual'] ?? $equipamento?->estado_atual ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="criticidade" class="form-label">Criticidade</label>
                                    <select class="form-select" id="criticidade" name="criticidade">
                                        <option value="" disabled <?= empty($_POST['criticidade'] ?? $equipamento?->criticidade) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Baixa', 'Média', 'Alta', 'Suporte de vida'] as $op) : ?>
                                            <option <?= (($_POST['criticidade'] ?? $equipamento?->criticidade ?? '') === $op) ? 'selected' : '' ?>><?= $op ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Observações</h5>
                            <textarea class="form-control mb-4" id="observacoes_equipamento" name="observacoes_equipamento" rows="4"><?= htmlspecialchars($_POST['observacoes_equipamento'] ?? $equipamento?->observacoes ?? '') ?></textarea>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="1"><i class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="1">Seguinte <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 3 -->
                        <div class="tab-pane fade" id="passo-fornecedor">
                            <h5 class="mb-3"><i class="fa-solid fa-truck me-2"></i>Fornecedores Associados</h5>
                            <p class="text-muted">Associe pelo menos um fornecedor e indique obrigatoriamente o tipo de relação.</p>

                            <div id="fornecedores-container">
                                <?php $fi = 0;
                                foreach ($forn_dados as $fd) :
                                    $fid = $fd['id_fornecedor'] ?? '';
                                    $ftipo = $fd['tipo_relacao'] ?? '';
                                    $finfo = $forn_lookup[$fid] ?? null;
                                ?>
                                    <div class="fornecedor-bloco border rounded-4 p-3 mb-3">
                                        <div class="row align-items-end mb-3">
                                            <div class="col-md-5">
                                                <label class="form-label">Fornecedor</label>
                                                <select class="form-select" name="fornecedores[<?= $fi ?>][id_fornecedor]">
                                                    <option value="" disabled <?= $fid === '' ? 'selected' : '' ?>>Escolha...</option>
                                                    <?php foreach ($lista_fornecedores as $forn) : ?>
                                                        <option value="<?= $forn->idFornecedor ?>"
                                                            data-nif="<?= htmlspecialchars($forn->nif ?? '') ?>"
                                                            data-telefone="<?= htmlspecialchars($forn->telefone ?? '') ?>"
                                                            data-email="<?= htmlspecialchars($forn->email ?? '') ?>"
                                                            <?= ($fid == $forn->idFornecedor) ? 'selected' : '' ?>><?= htmlspecialchars($forn->nome_empresa) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Tipo de relação</label>
                                                <select class="form-select" name="fornecedores[<?= $fi ?>][tipo_relacao]">
                                                    <option value="" disabled <?= $ftipo === '' ? 'selected' : '' ?>>Escolha...</option>
                                                    <?php foreach (['Fabricante', 'Distribuidor ou fornecedor comercial', 'Empresa de assistência técnica', 'Fornecedor de consumíveis ou acessórios'] as $opt) : ?>
                                                        <option <?= ($ftipo === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger remover-fornecedor w-100 <?= $fi === 0 ? 'd-none' : '' ?>"><i class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <div class="border rounded-4 p-3 bg-light">
                                            <h6 class="mb-3">Dados do fornecedor selecionado</h6>
                                            <div class="row">
                                                <div class="col-md-4"><label class="form-label">NIF</label><input type="text" class="form-control" name="fornecedores[<?= $fi ?>][nif_fornecedor]" value="<?= htmlspecialchars($finfo->nif ?? '') ?>" readonly></div>
                                                <div class="col-md-4"><label class="form-label">Telefone</label><input type="text" class="form-control" name="fornecedores[<?= $fi ?>][telefone_fornecedor]" value="<?= htmlspecialchars($finfo->telefone ?? '') ?>" readonly></div>
                                                <div class="col-md-4"><label class="form-label">Email</label><input type="text" class="form-control" name="fornecedores[<?= $fi ?>][email_fornecedor]" value="<?= htmlspecialchars($finfo->email ?? '') ?>" readonly></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php $fi++;
                                endforeach; ?>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-fornecedor"><i class="fa-solid fa-plus me-1"></i>Adicionar fornecedor</button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="2"><i class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="2">Seguinte <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 4 -->
                        <div class="tab-pane fade" id="passo-localizacao">
                            <h5 class="mb-3"><i class="fa-solid fa-location-dot me-2"></i>Localização Associada</h5>
                            <div class="mb-3">
                                <label for="localizacao_associada" class="form-label">Selecionar localização</label>
                                <select class="form-select" id="localizacao_associada" name="localizacao_associada">
                                    <option value="" disabled <?= empty($loc_sel_id) ? 'selected' : '' ?>>Escolha...</option>
                                    <?php foreach ($lista_localizacoes as $loc) : ?>
                                        <option value="<?= $loc->idLocalizacao ?>"
                                            data-edificio="<?= htmlspecialchars($loc->edificio ?? '') ?>"
                                            data-piso="<?= htmlspecialchars($loc->piso ?? '') ?>"
                                            data-servico="<?= htmlspecialchars($loc->servico ?? '') ?>"
                                            data-sala="<?= htmlspecialchars($loc->sala ?? '') ?>"
                                            <?= ($loc_sel_id == $loc->idLocalizacao) ? 'selected' : '' ?>><?= htmlspecialchars($loc->servico) ?><?= $loc->sala ? ' — ' . htmlspecialchars($loc->sala) : '' ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="border rounded-4 p-3 bg-light mb-3">
                                <h6 class="mb-3">Dados da localização selecionada</h6>
                                <div class="row">
                                    <div class="col-md-3"><label class="form-label">Edifício</label><input type="text" class="form-control" id="localizacao_edificio" value="<?= htmlspecialchars($loc_sel->edificio ?? '') ?>" readonly></div>
                                    <div class="col-md-2"><label class="form-label">Piso</label><input type="text" class="form-control" id="localizacao_piso" value="<?= htmlspecialchars($loc_sel->piso ?? '') ?>" readonly></div>
                                    <div class="col-md-4"><label class="form-label">Serviço | Departamento</label><input type="text" class="form-control" id="localizacao_departamento" value="<?= htmlspecialchars($loc_sel->servico ?? '') ?>" readonly></div>
                                    <div class="col-md-3"><label class="form-label">Sala</label><input type="text" class="form-control" id="localizacao_sala" value="<?= htmlspecialchars($loc_sel->sala ?? '') ?>" readonly></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="3"><i class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="3">Seguinte <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 5 -->
                        <div class="tab-pane fade" id="passo-documentacao">
                            <h5 class="mb-3"><i class="fa-solid fa-file-pdf me-2"></i>Documentação Associada</h5>
                            <p class="text-muted">Opcional. Adicione documentos apenas se existirem.</p>

                            <div id="documentos-container" data-proximo="<?= $proximo_doc_num ?>">
                                <?php $di = 0;
                                foreach ($doc_dados as $dd) : ?>
                                    <div class="documento-bloco border rounded-4 p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Documento <?= $di + 1 ?></h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger remover-documento <?= $di === 0 ? 'd-none' : '' ?>"><i class="fa-solid fa-trash"></i> Remover</button>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4"><label class="form-label">Código</label><input type="text" class="form-control" name="documentos[<?= $di ?>][codigo_documento]" value="<?= htmlspecialchars($dd['codigo_documento'] ?? '') ?>" placeholder="<?= htmlspecialchars(formata_codigo('DOC', $proximo_doc_num)) ?>"></div>
                                            <div class="col-md-4">
                                                <label class="form-label">Tipo de documento</label>
                                                <select class="form-select" name="documentos[<?= $di ?>][tipo_documento]">
                                                    <option value="" disabled <?= empty($dd['tipo_documento'] ?? '') ? 'selected' : '' ?>>Escolha...</option>
                                                    <?php foreach (['Manual de Utilizador', 'Manual de Serviço', 'Certificado de Calibração', 'Fatura ou Guia de Aquisição', 'Declaração de Conformidade', 'Relatório Técnico'] as $opt) : ?>
                                                        <option <?= (($dd['tipo_documento'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4"><label class="form-label">Nome do documento</label><input type="text" class="form-control" name="documentos[<?= $di ?>][nome_documento]" value="<?= htmlspecialchars($dd['nome_documento'] ?? '') ?>"></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4"><label class="form-label">Data do documento</label><input type="text" class="form-control flatpickr-date" name="documentos[<?= $di ?>][data_documento]" value="<?= htmlspecialchars($dd['data_documento'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Data de validade</label><input type="text" class="form-control flatpickr-date" name="documentos[<?= $di ?>][validade]" value="<?= htmlspecialchars($dd['validade'] ?? '') ?>"></div>
                                            <div class="col-md-4">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="documentos[<?= $di ?>][estado_documento]">
                                                    <option value="" disabled <?= empty($dd['estado_documento'] ?? '') ? 'selected' : '' ?>>Escolha...</option>
                                                    <?php foreach (['Ativo', 'Prestes a Expirar', 'Expirado', 'Pendente', 'Anulado', 'Estendido', 'Não disponível'] as $opt) : ?>
                                                        <option <?= (($dd['estado_documento'] ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label">Ficheiro</label>
                                                <input type="file" class="form-control"
                                                    name="documentos[<?= $di ?>][ficheiro]"
                                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                <?php if (!empty($dd['ficheiro'])) : ?>
                                                    <div class="form-text">
                                                        Ficheiro atual:
                                                        <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($dd['ficheiro']) ?>"
                                                            target="_blank">
                                                            <?= htmlspecialchars($dd['ficheiro']) ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <input type="hidden"
                                                    name="documentos[<?= $di ?>][ficheiro_atual]"
                                                    value="<?= htmlspecialchars($dd['ficheiro'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Observações</label>
                                            <textarea class="form-control" name="documentos[<?= $di ?>][observacoes_documentacao]" rows="3"><?= htmlspecialchars($dd['observacoes_documentacao'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                <?php $di++;
                                endforeach; ?>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-documento"><i class="fa-solid fa-plus me-1"></i>Adicionar documento</button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="4"><i class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="4">Seguinte <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 6 -->
                        <div class="tab-pane fade" id="passo-garantia">
                            <h5 class="mb-3"><i class="fa-solid fa-shield-halved me-2"></i>Garantia</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="codigo_garantia" class="form-label">Código da garantia</label>
                                    <input type="text" class="form-control" id="codigo_garantia" name="codigo_garantia" value="<?= htmlspecialchars($_POST['codigo_garantia'] ?? $garantia?->codigo_garantia ?? formata_codigo('GAR', $proximo_gar_num)) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="entidade_garantia" class="form-label">Entidade responsável</label>
                                    <select class="form-select" id="entidade_garantia" name="entidade_garantia">
                                        <option value="" disabled <?= empty($_POST['entidade_garantia'] ?? $garantia?->idEntidade) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach ($lista_fornecedores as $forn) : ?>
                                            <option value="<?= $forn->idFornecedor ?>" <?= (($_POST['entidade_garantia'] ?? $garantia?->idEntidade ?? '') == $forn->idFornecedor) ? 'selected' : '' ?>><?= htmlspecialchars($forn->nome_empresa) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3"><label for="data_inicio_garantia" class="form-label">Data de início</label><input type="text" class="form-control flatpickr-date" id="data_inicio_garantia" name="data_inicio_garantia" value="<?= htmlspecialchars($_POST['data_inicio_garantia'] ?? $garantia?->data_inicio ?? '') ?>"></div>
                                <div class="col-md-3"><label for="data_fim_garantia" class="form-label">Data de fim</label><input type="text" class="form-control flatpickr-date" id="data_fim_garantia" name="data_fim_garantia" value="<?= htmlspecialchars($_POST['data_fim_garantia'] ?? $garantia?->data_fim ?? '') ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="estado_garantia" class="form-label">Estado da garantia</label>
                                    <select class="form-select" id="estado_garantia" name="estado_garantia">
                                        <option value="" disabled <?= empty($_POST['estado_garantia'] ?? $garantia?->estado_garantia) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Ativa', 'Prestes a Expirar', 'Expirada', 'Pendente', 'Anulada', 'Estendida', 'Não disponível'] as $opt) : ?>
                                            <option <?= (($_POST['estado_garantia'] ?? $garantia?->estado_garantia ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="ficheiro_garantia" class="form-label">Documento da garantia</label>
                                    <input type="file" class="form-control" id="ficheiro_garantia" name="ficheiro_garantia" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <?php if (!empty($garantia?->ficheiro_garantia)) : ?>
                                        <div class="form-text">
                                            Ficheiro atual:
                                            <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($garantia->ficheiro_garantia) ?>"
                                                target="_blank" rel="noopener">
                                                <?= htmlspecialchars($garantia->ficheiro_garantia) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="observacoes_garantia" class="form-label">Observações da garantia</label>
                                <textarea class="form-control" id="observacoes_garantia" name="observacoes_garantia" rows="4"><?= htmlspecialchars($_POST['observacoes_garantia'] ?? $garantia?->observacoes ?? '') ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="5"><i class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="5">Seguinte <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>

                        <!-- PASSO 7 -->
                        <div class="tab-pane fade" id="passo-contrato">
                            <h5 class="mb-3"><i class="fa-solid fa-file-contract me-2"></i>Contrato de Manutenção</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="existe_contrato" class="form-label">Existe contrato de manutenção?</label>
                                    <select class="form-select" id="existe_contrato" name="existe_contrato">
                                        <option value="" disabled <?= empty($existe_contrato_val) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Sim', 'Não'] as $opt) : ?>
                                            <option <?= ($existe_contrato_val === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="codigo_contrato" class="form-label">Código do contrato</label>
                                    <input type="text" class="form-control" id="codigo_contrato" name="codigo_contrato" value="<?= htmlspecialchars($_POST['codigo_contrato'] ?? $contrato?->codigo_contrato ?? formata_codigo('CON', $proximo_con_num)) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="tipo_contrato" class="form-label">Tipo de contrato</label>
                                    <select class="form-select" id="tipo_contrato" name="tipo_contrato">
                                        <option value="" disabled <?= empty($_POST['tipo_contrato'] ?? $contrato?->tipo_contrato) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Contrato de Manutenção', 'Manutenção Preventiva', 'Contrato de Assistência Técnica', 'Sem Contrato'] as $opt) : ?>
                                            <option <?= (($_POST['tipo_contrato'] ?? $contrato?->tipo_contrato ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="entidade_contrato" class="form-label">Entidade responsável</label>
                                    <select class="form-select" id="entidade_contrato" name="entidade_contrato">
                                        <option value="" disabled <?= empty($_POST['entidade_contrato'] ?? $contrato?->idEntidade) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach ($lista_fornecedores as $forn) : ?>
                                            <option value="<?= $forn->idFornecedor ?>" <?= (($_POST['entidade_contrato'] ?? $contrato?->idEntidade ?? '') == $forn->idFornecedor) ? 'selected' : '' ?>><?= htmlspecialchars($forn->nome_empresa) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="periodicidade" class="form-label">Periodicidade</label>
                                    <select class="form-select" id="periodicidade" name="periodicidade">
                                        <option value="" disabled <?= empty($_POST['periodicidade'] ?? $contrato?->periodicidade) ? 'selected' : '' ?>>Escolha...</option>
                                        <?php foreach (['Mensal', 'Trimestral', 'Semestral', 'Anual', 'Bianual'] as $opt) : ?>
                                            <option <?= (($_POST['periodicidade'] ?? $contrato?->periodicidade ?? '') === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="ficheiro_contrato" class="form-label">Ficheiro do contrato</label>
                                    <input type="file" class="form-control" id="ficheiro_contrato" name="ficheiro_contrato" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <?php if (!empty($contrato?->ficheiro_contrato)) : ?>
                                        <div class="form-text">
                                            Ficheiro atual:
                                            <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($contrato->ficheiro_contrato) ?>"
                                                target="_blank" rel="noopener">
                                                <?= htmlspecialchars($contrato->ficheiro_contrato) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="observacoes_contrato" class="form-label">Observações do contrato</label>
                                <textarea class="form-control" id="observacoes_contrato" name="observacoes_contrato" rows="4"><?= htmlspecialchars($_POST['observacoes_contrato'] ?? $contrato?->observacoes ?? '') ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="6"><i class="fa-solid fa-arrow-left me-1"></i>Anterior</button>
                                <button type="submit" class="btn btn-pink"><i class="fa-regular fa-floppy-disk me-1"></i>Guardar alterações</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>