<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] === 'profissional') {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}
require_once __DIR__ . '/../../includes/validacoes.php';

// permitir apenas GET e POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'localizacoes';
$erros = [];
$erro_sistema = '';
$lista_servicos = [];

// desencriptar e validar o ID 
$idEncriptado = $_GET['id_localizacao'] ?? null;
$idLocalizacao = aes_decrypt($idEncriptado);
if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}

// POST: validar e atualizar (tratado antes do SELECT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recolher 
    $edificio    = mb_convert_case(trim($_POST['edificio'] ?? ''), MB_CASE_TITLE, "UTF-8");
    $piso        = trim($_POST['piso'] ?? '');
    $idServico   = $_POST['idServico'] ?? '';
    $sala        = trim($_POST['sala'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    // 2. Validar (centralizado em validacoes.php)
    $erros = validar_localizacao([
        'edificio'  => $edificio,
        'piso'      => $piso,
        'idServico' => $idServico,
        'sala'      => $sala,
    ]);

    // 3. Atualizar se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $sql = "UPDATE Localizacoes SET
                        edificio = :edificio, piso = :piso, idServico = :idServico,
                        sala = :sala, observacoes = :observacoes
                    WHERE idLocalizacao = :id";
            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':edificio'    => $edificio ?: null,
                ':piso'        => $piso !== '' ? $piso : null,
                ':idServico'   => $idServico,
                ':sala'        => $sala ?: null,
                ':observacoes' => $observacoes ?: null,
                ':id'          => $idLocalizacao
            ]);
            $ligacao = null;
            header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
            exit;
        } catch (PDOException $err) {
            $msg = $err->getMessage();
            if (stripos($msg, 'foreign key') !== false) {
                $erro_sistema = "O serviço selecionado não é válido.";
            } else {
                $erro_sistema = "Não foi possível atualizar a localização. Verifique os dados e tente novamente.";
            }
        }
    }
}

// Carregar o registo da BD (para preencher o formulário)
$localizacao = null;
try {
    $ligacao = liga_bd();
    $lista_servicos = $ligacao->query("SELECT idServico, nome FROM Servicos ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $stmt = $ligacao->prepare("SELECT * FROM Localizacoes WHERE idLocalizacao = :id");
    $stmt->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmt->execute();
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);
    $ligacao = null;
    if (!$localizacao) {
        header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
        exit;
    }
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar a localização.";
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
                <i class="fa-regular fa-pen-to-square me-2"></i>
                Editar Localização
            </h2>

            <p class="text-muted mb-0">
                Atualize as informações da localização selecionada.
            </p>

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

                <form action="editar.php?id_localizacao=<?= htmlspecialchars($idEncriptado) ?>" method="post">

                    <h5 class="mb-3">
                        Local</h5>

                    <div class="row mb-3">

                        <div class="col-md-3">
                            <label for="edificio" class="form-label">Edifício</label>
                            <input type="text" class="form-control" id="edificio" name="edificio"
                                value="<?= htmlspecialchars($_POST['edificio'] ?? $localizacao?->edificio ?? '') ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="piso" class="form-label">Piso</label>
                            <input type="text" class="form-control" id="piso" name="piso" value="<?= htmlspecialchars($_POST['piso'] ?? $localizacao?->piso ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="departamento" class="form-label">Serviço | Departamento</label>
                            <select class="form-select" id="idServico" name="idServico">
                                <option value="" disabled <?= empty($_POST['idServico'] ?? $localizacao?->idServico) ? 'selected' : '' ?>>Escolha...</option>
                                <?php foreach ($lista_servicos as $s) : ?>
                                    <option value="<?= $s->idServico ?>" <?= (($_POST['idServico'] ?? $localizacao?->idServico ?? '') == $s->idServico) ? 'selected' : '' ?>><?= htmlspecialchars($s->nome) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="sala" class="form-label">Sala | Gabinete</label>
                            <input type="text" class="form-control" id="sala" name="sala" value="<?= htmlspecialchars($_POST['sala'] ?? $localizacao?->sala ?? '') ?>">
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">Observações</h5>

                    <textarea class="form-control mb-4" id="observacoes" name="observacoes" rows="4"><?= htmlspecialchars($_POST['observacoes'] ?? $localizacao?->observacoes ?? '') ?></textarea>

                    <div class="d-flex justify-content-end gap-2">

                        <a href="lista.php" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-pink">
                            <i class="fa-regular fa-floppy-disk me-1"></i>
                            Guardar alterações
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>