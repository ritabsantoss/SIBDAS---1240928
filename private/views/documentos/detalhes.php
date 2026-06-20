<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'documentos';
$erro_sistema = '';
$documento = null;

$idEncriptado = $_GET['id_documento'] ?? null;
$idDocumento = aes_decrypt($idEncriptado);
if (!$idDocumento || !is_numeric($idDocumento)) {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare(
        "SELECT d.*,
                e.designacao AS equipamento, e.idEquipamento,
                f.nome_empresa AS fornecedor
         FROM Documentos d
         JOIN Equipamentos e ON d.idEquipamento = e.idEquipamento
         LEFT JOIN Fornecedores f ON d.idFornecedor = f.idFornecedor
         WHERE d.idDocumento = :id"
    );
    $stmt->bindParam(':id', $idDocumento, PDO::PARAM_INT);
    $stmt->execute();
    $documento = $stmt->fetch(PDO::FETCH_OBJ);
    $ligacao = null;

    if (!$documento) {
        header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
        exit;
    }
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os dados do documento.";
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
                    Detalhes do Documento
                </h2>
                <p class="text-muted mb-0">Informação detalhada do documento selecionado.</p>
            </div>

            <?php if (!empty($erro_sistema)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erro_sistema) ?></div>
            <?php endif; ?>

            <!-- card rosinho: identificação -->
            <div class="card-destaque">
                <p class="detalhe-nome"><?= htmlspecialchars($documento->nome_documento ?? '—') ?></p>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Código</label>
                        <p class="form-control-plaintext"><?= htmlspecialchars($documento->codigo_documento ?? '—') ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Equipamento associado</label>
                        <p class="form-control-plaintext"><?= htmlspecialchars($documento->equipamento ?? '—') ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fornecedor associado</label>
                        <p class="form-control-plaintext"><?= htmlspecialchars($documento->fornecedor ?? '—') ?></p>
                    </div>
                </div>
            </div>

            <!-- card branco: detalhes -->
            <div class="card shadow-sm border-0 rounded-4 mb-3">
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo de documento</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($documento->tipo_documento ?? '—') ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Data do documento</label>
                            <p class="form-control-plaintext"><?= $documento->data_documento ? date('d/m/Y', strtotime($documento->data_documento)) : '—' ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Data de validade</label>
                            <p class="form-control-plaintext"><?= $documento->validade ? date('d/m/Y', strtotime($documento->validade)) : '—' ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Estado</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($documento->estado_documento ?? '—') ?></p>
                        </div>
                        <?php if (!empty($documento->ficheiro)) : ?>
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Ficheiro:</label>
                                <a href="<?= BASE_URL ?>/public/uploads/<?= rawurlencode($documento->ficheiro) ?>" target="_blank" rel="noopener" class="ms-1">
                                    <?= htmlspecialchars($documento->ficheiro) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- card rosinho: observações -->
            <div class="card-destaque">
                <label class="form-label fw-bold">Observações</label>
                <p class="form-control-plaintext"><?= htmlspecialchars($documento->observacoes ?? '—') ?></p>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="lista.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>