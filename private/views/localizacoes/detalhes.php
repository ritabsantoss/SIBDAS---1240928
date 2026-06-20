<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'localizacoes';
$erro_sistema = '';
$localizacao = null;
$equipamentos_localizacao = [];

$idEncriptado = $_GET['id_localizacao'] ?? null;
$idLocalizacao = aes_decrypt($idEncriptado);
if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();

    $stmt = $ligacao->prepare(
        "SELECT l.*, s.nome AS servico
         FROM Localizacoes l
         JOIN Servicos s ON l.idServico = s.idServico
         WHERE l.idLocalizacao = :id"
    );
    $stmt->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmt->execute();
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
        exit;
    }

    $stmtEq = $ligacao->prepare(
        "SELECT e.codigo_interno, e.designacao, c.nome AS categoria,
                e.estado_atual, e.criticidade, e.idEquipamento
         FROM Equipamentos e
         LEFT JOIN Categorias c ON e.idCategoria = c.idCategoria
         WHERE e.idLocalizacao = :id
         ORDER BY e.codigo_interno"
    );
    $stmtEq->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmtEq->execute();
    $equipamentos_localizacao = $stmtEq->fetchAll(PDO::FETCH_OBJ);

    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os dados da localização.";
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
                Detalhes da Localização
                <?php if ($localizacao) : ?>
                    <?php if ($localizacao->ativo == 1) : ?>
                        <span class="badge badge-sihem ms-2">Ativo</span>
                    <?php else : ?>
                        <span class="badge badge-sihem-pink ms-2">Inativo</span>
                    <?php endif; ?>
                <?php endif; ?>
            </h2>
            <p class="text-muted mb-0">Informação detalhada da localização selecionada.</p>
        </div>

        <?php if (!empty($erro_sistema)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro_sistema) ?></div>
        <?php endif; ?>


        <div class="card-destaque">
            <p class="detalhe-nome"><?= htmlspecialchars($localizacao->servico ?? '') ?></p>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Edifício</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->edificio ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Piso</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->piso ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Sala | Gabinete</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->sala ?? '—') ?></p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-body p-4">
                <p class="detalhe-secao-titulo">
                    <i class="fa-solid fa-stethoscope me-2"></i>Equipamentos nesta localização
                </p>
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Designação</th>
                            <th>Categoria</th>
                            <th>Estado</th>
                            <th>Criticidade</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($equipamentos_localizacao)) : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Nenhum equipamento nesta localização.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($equipamentos_localizacao as $eq) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($eq->codigo_interno) ?></td>
                                    <td><?= htmlspecialchars($eq->designacao) ?></td>
                                    <td><?= htmlspecialchars($eq->categoria ?? '—') ?></td>
                                    <td><span class="badge badge-sihem"><?= htmlspecialchars($eq->estado_atual) ?></span></td>
                                    <td><?= htmlspecialchars($eq->criticidade) ?></td>
                                    <td class="text-end">
                                        <a href="../equipamentos/detalhes.php?id_equipamento=<?= aes_encrypt($eq->idEquipamento) ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye me-1"></i>Ver
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-destaque">
            <label class="form-label fw-bold">Observações</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->observacoes ?? '—') ?></p>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="lista.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Voltar
            </a>
            <a href="editar.php?id_localizacao=<?= htmlspecialchars($idEncriptado) ?>" class="btn btn-pink">
                <i class="fa-regular fa-pen-to-square me-1"></i>Editar
            </a>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>