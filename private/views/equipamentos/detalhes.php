<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Garantir que a página só é acedida por GET (não por POST direto)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'equipamentos';
$erro_sistema = '';
$equipamento = null;
$categoria = null;
$localizacao = null;
$fornecedores = [];
$documentos = [];
$componentes = [];
$garantia = null;
$contrato = null;
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEncriptado);
if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();

    // carregar os dados equipamento + categoria + localização
    $stmt = $ligacao->prepare(
        "SELECT e.*,
                c.nome AS nome_categoria,
                l.edificio, l.piso, l.sala,
                s.nome AS nome_servico
         FROM Equipamentos e
         LEFT JOIN Categorias c ON e.idCategoria = c.idCategoria
         LEFT JOIN Localizacoes l ON e.idLocalizacao = l.idLocalizacao
         LEFT JOIN Servicos s ON l.idServico = s.idServico
         WHERE e.idEquipamento = :id"
    );
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);
     // Se não existir, redireciona para a lista
    if (!$equipamento) {
        header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
        exit;
    }

    // fornecedores associados
    $stmt = $ligacao->prepare(
        "SELECT f.nome_empresa, f.nif, f.telefone, f.email, f.website,
                ef.tipo_relacao
         FROM Equipamento_Fornecedor ef
         JOIN Fornecedores f ON ef.idFornecedor = f.idFornecedor
         WHERE ef.idEquipamento = :id"
    );
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedores = $stmt->fetchAll(PDO::FETCH_OBJ);

    // documentos
    $stmt = $ligacao->prepare("SELECT * FROM Documentos WHERE idEquipamento = :id AND ativo = 1 ORDER BY codigo_documento");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);

    // componentes
    $stmt = $ligacao->prepare("SELECT * FROM Componentes WHERE idEquipamento = :id ORDER BY codigo_componente");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $componentes = $stmt->fetchAll(PDO::FETCH_OBJ);

    // garantia
    $stmt = $ligacao->prepare(
        "SELECT g.*, f.nome_empresa AS nome_entidade
         FROM Garantias g
         LEFT JOIN Fornecedores f ON g.idEntidade = f.idFornecedor
         WHERE g.idEquipamento = :id"
    );
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $garantia = $stmt->fetch(PDO::FETCH_OBJ) ?: null;

    // contrato
    $stmt = $ligacao->prepare(
        "SELECT c.*, f.nome_empresa AS nome_entidade
         FROM Contratos c
         LEFT JOIN Fornecedores f ON c.idEntidade = f.idFornecedor
         WHERE c.idEquipamento = :id"
    );
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $contrato = $stmt->fetch(PDO::FETCH_OBJ) ?: null;

    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os dados do equipamento.";
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>


<div class="private-container">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1">
                <i class="fa-solid fa-circle-info me-2"></i>
                Detalhes do Equipamento
                <?php if ($equipamento) : ?>
                    <?php if ($equipamento->ativo == 1) : ?>
                        <span class="badge badge-sihem ms-2">Ativo</span>
                    <?php else : ?>
                        <span class="badge badge-sihem-pink ms-2">Inativo</span>
                    <?php endif; ?>
                <?php endif; ?>
            </h2>
            <p class="text-muted mb-0">Consulte as informações completas do equipamento selecionado.</p>
        </div>

        <?php if (!empty($erro_sistema)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro_sistema) ?></div>
        <?php endif; ?>

        <!-- card identificação -->
        <div class="card-destaque">
            <p class="detalhe-nome"><?= htmlspecialchars($equipamento->designacao ?? '') ?></p>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Código interno</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->codigo_interno ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Categoria</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->nome_categoria ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Estado</label>
                    <p class="form-control-plaintext">
                        <span class="badge badge-sihem"><?= htmlspecialchars($equipamento->estado_atual ?? '—') ?></span>
                    </p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Criticidade</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->criticidade ?? '—') ?></p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-body p-4">

                <ul class="nav nav-tabs mb-4" id="equipamentoTabs">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#passo-identificacao" type="button">1. Identificação</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#passo-aquisicao" type="button">2. Aquisição</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#passo-fornecedor" type="button">3. Fornecedor</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#passo-localizacao" type="button">4. Localização</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#passo-documentacao" type="button">5. Documentação</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#passo-garantia" type="button">6. Garantia</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#passo-contrato" type="button">7. Contrato</button></li>
                </ul>

                <div class="tab-content">

                    <!-- PASSO 1: Identificação -->
                    <div class="tab-pane fade show active" id="passo-identificacao">

                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Informação Técnica</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Marca</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->marca ?? '—') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Modelo</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->modelo ?? '—') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Número de série</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->numero_serie ?? '—') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Componentes associados</h5>
                                <?php if (empty($componentes)) : ?>
                                    <p class="text-muted">Nenhum componente associado.</p>
                                <?php else : ?>
                                    <?php foreach ($componentes as $comp) : ?>
                                        <div class="border rounded-4 p-3 mb-2">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">Código</label>
                                                    <p class="form-control-plaintext"><?= htmlspecialchars($comp->codigo_componente) ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">Nome</label>
                                                    <p class="form-control-plaintext"><?= htmlspecialchars($comp->nome_componente) ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">Estado</label>
                                                    <p class="form-control-plaintext"><?= htmlspecialchars($comp->estado_componente ?? '—') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-pink btn-tab-seguinte" data-bs-target="#passo-aquisicao">
                                Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASSO 2: Aquisição -->
                    <div class="tab-pane fade" id="passo-aquisicao">

                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Aquisição</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de aquisição</label>
                                        <p class="form-control-plaintext"><?= $equipamento->data_aquisicao ? date('d/m/Y', strtotime($equipamento->data_aquisicao)) : '—' ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ano de fabrico</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->ano_fabrico ?? '—') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Custo de aquisição</label>
                                        <p class="form-control-plaintext"><?= $equipamento->custo !== null ? number_format($equipamento->custo, 2, ',', '.') . ' €' : '—' ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo de entrada</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->tipo_entrada ?? '—') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Observações</h5>
                                <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->observacoes ?? '—') ?></p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-tab-anterior" data-bs-target="#passo-identificacao">
                                <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                            </button>
                            <button type="button" class="btn btn-pink btn-tab-seguinte" data-bs-target="#passo-fornecedor">
                                Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASSO 3: Fornecedor -->
                    <div class="tab-pane fade" id="passo-fornecedor">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-truck me-2"></i>Fornecedores Associados
                        </h5>
                        <?php if (empty($fornecedores)) : ?>
                            <p class="text-muted">Nenhum fornecedor associado.</p>
                        <?php else : ?>
                            <?php foreach ($fornecedores as $forn) : ?>
                                <div class="border rounded-4 p-3 mb-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Fornecedor</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($forn->nome_empresa) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo de relação</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($forn->tipo_relacao) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">NIF</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($forn->nif ?? '—') ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($forn->telefone ?? '—') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($forn->email ?? '—') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Website</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($forn->website ?? '—') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-tab-anterior" data-bs-target="#passo-aquisicao">
                                <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                            </button>
                            <button type="button" class="btn btn-pink btn-tab-seguinte" data-bs-target="#passo-localizacao">
                                Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASSO 4: Localização -->
                    <div class="tab-pane fade" id="passo-localizacao">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-location-dot me-2"></i>Localização Associada
                        </h5>
                        <div class="border rounded-4 p-3 bg-light mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Edifício</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->edificio ?? '—') ?></p>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Piso</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->piso ?? '—') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Serviço | Departamento</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->nome_servico ?? '—') ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Sala</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($equipamento->sala ?? '—') ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-tab-anterior" data-bs-target="#passo-fornecedor">
                                <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                            </button>
                            <button type="button" class="btn btn-pink btn-tab-seguinte" data-bs-target="#passo-documentacao">
                                Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASSO 5: Documentação -->
                    <div class="tab-pane fade" id="passo-documentacao">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-file-pdf me-2"></i>Documentação Associada
                        </h5>
                        <?php if (empty($documentos)) : ?>
                            <p class="text-muted">Nenhum documento associado.</p>
                        <?php else : ?>
                            <?php $di = 1;
                            foreach ($documentos as $doc) : ?>
                                <div class="documento-bloco border rounded-4 p-3 mb-3">
                                    <h6 class="mb-3">Documento <?= $di ?></h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Código</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($doc->codigo_documento) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($doc->tipo_documento ?? '—') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nome</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($doc->nome_documento ?? '—') ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Data do documento</label>
                                            <p class="form-control-plaintext"><?= $doc->data_documento ? date('d/m/Y', strtotime($doc->data_documento)) : '—' ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Validade</label>
                                            <p class="form-control-plaintext"><?= $doc->validade ? date('d/m/Y', strtotime($doc->validade)) : '—' ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Estado</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($doc->estado_documento ?? '—') ?></p>
                                        </div>
                                    </div>
                                    <?php if (!empty($doc->ficheiro)) : ?>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Ficheiro:</label>
                                            <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($doc->ficheiro) ?>" target="_blank" rel="noopener" class="ms-1">
                                                <?= htmlspecialchars($doc->ficheiro) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($doc->observacoes)) : ?>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Observações</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($doc->observacoes) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php $di++;
                            endforeach; ?>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-tab-anterior" data-bs-target="#passo-localizacao">
                                <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                            </button>
                            <button type="button" class="btn btn-pink btn-tab-seguinte" data-bs-target="#passo-garantia">
                                Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASSO 6: Garantia -->
                    <div class="tab-pane fade" id="passo-garantia">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-shield-halved me-2"></i>Garantia
                        </h5>
                        <?php if (!$garantia) : ?>
                            <p class="text-muted">Sem garantia registada.</p>
                        <?php else : ?>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Código</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($garantia->codigo_garantia) ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Entidade responsável</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($garantia->nome_entidade ?? '—') ?></p>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Data de início</label>
                                    <p class="form-control-plaintext"><?= $garantia->data_inicio ? date('d/m/Y', strtotime($garantia->data_inicio)) : '—' ?></p>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Data de fim</label>
                                    <p class="form-control-plaintext"><?= $garantia->data_fim ? date('d/m/Y', strtotime($garantia->data_fim)) : '—' ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Estado</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($garantia->estado_garantia ?? '—') ?></p>
                                </div>
                            </div>
                            <?php if (!empty($garantia->ficheiro_garantia)) : ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ficheiro:</label>
                                    <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($garantia->ficheiro_garantia) ?>" target="_blank" rel="noopener" class="ms-1">
                                        <?= htmlspecialchars($garantia->ficheiro_garantia) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($garantia->observacoes)) : ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Observações</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($garantia->observacoes) ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-tab-anterior" data-bs-target="#passo-documentacao">
                                <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                            </button>
                            <button type="button" class="btn btn-pink btn-tab-seguinte" data-bs-target="#passo-contrato">
                                Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- PASSO 7: Contrato -->
                    <div class="tab-pane fade" id="passo-contrato">
                        <h5 class="mb-3">
                            <i class="fa-solid fa-file-contract me-2"></i>Contrato de Manutenção
                        </h5>
                        <?php if (!$contrato) : ?>
                            <p class="text-muted">Sem contrato registado.</p>
                        <?php else : ?>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Código</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($contrato->codigo_contrato) ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Tipo de contrato</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($contrato->tipo_contrato ?? '—') ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Entidade responsável</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($contrato->nome_entidade ?? '—') ?></p>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Periodicidade</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($contrato->periodicidade ?? '—') ?></p>
                                </div>
                            </div>
                            <?php if (!empty($contrato->ficheiro_contrato)) : ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ficheiro:</label>
                                    <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($contrato->ficheiro_contrato) ?>" target="_blank" rel="noopener" class="ms-1">
                                        <?= htmlspecialchars($contrato->ficheiro_contrato) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($contrato->observacoes)) : ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Observações</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($contrato->observacoes) ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-tab-anterior" data-bs-target="#passo-garantia">
                                <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                            </button>
                            <div class="d-flex gap-2">
                                <a href="lista.php" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                                </a>
                                <a href="editar.php?id_equipamento=<?= htmlspecialchars($idEncriptado) ?>" class="btn btn-pink">
                                    <i class="fa-regular fa-pen-to-square me-1"></i>Editar
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>