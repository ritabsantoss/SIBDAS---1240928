<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'fornecedores';
$erro_sistema = '';
$fornecedor = null;
$equipamentos_associados = [];

$idEncriptado = $_GET['id_fornecedor'] ?? null;
$idFornecedor = aes_decrypt($idEncriptado);
if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();

    // carregar o fornecedor
    $stmt = $ligacao->prepare("SELECT * FROM Fornecedores WHERE idFornecedor = :id");
    $stmt->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
        exit;
    }

    // carregar equipamentos associados a este fornecedor
    $stmtEq = $ligacao->prepare(
        "SELECT e.codigo_interno, e.designacao, c.nome AS categoria,
                e.estado_atual, e.criticidade, e.idEquipamento
         FROM Equipamento_Fornecedor ef
         JOIN Equipamentos e ON ef.idEquipamento = e.idEquipamento
         LEFT JOIN Categorias c ON e.idCategoria = c.idCategoria
         WHERE ef.idFornecedor = :id
         ORDER BY e.codigo_interno"
    );
    $stmtEq->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmtEq->execute();
    $equipamentos_associados = $stmtEq->fetchAll(PDO::FETCH_OBJ);

    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os dados do fornecedor.";
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!--Conteúdo-->
    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1">
                <i class="fa-solid fa-circle-info me-2"></i>
                Detalhes do Fornecedor
            </h2>
            <p class="text-muted mb-0">
                Informação detalhada do fornecedor selecionado.
            </p>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <div class="row mb-3">

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Nome da empresa
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->nome_empresa ?? '') ?>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            NIF
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->nif ?? '') ?>
                        </p>
                    </div>

                </div>

                <hr>

                <div class="row mb-3">

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Contacto telefónico
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->telefone ?? '') ?>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Email
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->email ?? '') ?>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Website
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->website ?? '') ?>
                        </p>
                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Morada
                    </label>
                    <p class="form-control-plaintext">
                        <?= htmlspecialchars($fornecedor->morada ?? '') ?>
                    </p>

                </div>

                <hr>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Pessoa de contacto
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->pessoa_contacto ?? '') ?>
                        </p>

                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Telefone da pessoa de contacto
                        </label>
                        <p class="form-control-plaintext">
                            <?= htmlspecialchars($fornecedor->telefone_contacto ?? '') ?>
                        </p>
                    </div>

                </div>

                <hr>

                <h5 class="mb-3">
                    <i class="fa-solid fa-stethoscope me-2"></i>
                    Equipamentos associados
                </h5>

                <div class="table-responsive mb-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Designação</th>
                                <th>Categoria</th>
                                <th>Estado</th>
                                <th>Criticidade</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($equipamentos_associados)) : ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Nenhum equipamento associado a este fornecedor.
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($equipamentos_associados as $eq) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($eq->codigo_interno) ?></td>
                                        <td><?= htmlspecialchars($eq->designacao) ?></td>
                                        <td><?= htmlspecialchars($eq->categoria ?? '') ?></td>
                                        <td>
                                            <span class="badge badge-sihem">
                                                <?= htmlspecialchars($eq->estado_atual) ?>
                                            </span>
                                        </td>
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

                <hr>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Observações
                    </label>
                    <p class="form-control-plaintext">
                        <?= htmlspecialchars($fornecedor->observacoes ?? '') ?>
                    </p>
                </div>

                <div class="d-flex justify-content-end gap-2">

                    <a href="lista.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Voltar
                    </a>

                    <a href="editar.php?id_fornecedor=<?= htmlspecialchars($idEncriptado) ?>" class="btn btn-pink">
                        <i class="fa-regular fa-pen-to-square me-1"></i>
                        Editar
                    </a>

                </div>
            </div>
        </div>
</div>
</main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>