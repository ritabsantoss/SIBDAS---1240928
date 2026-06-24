<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] === 'profissional') {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}
require_once __DIR__ . '/../../includes/validacoes.php';

$pagina_ativa = 'localizacoes';

$erros = [];
$erro_sistema = '';
$lista_servicos = [];

// dropdown de serviços (da BD)
try {
    $ligacao = liga_bd();
    $lista_servicos = $ligacao->query("SELECT idServico, nome FROM Servicos ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os serviços.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Recolher dados
    $edificio    = mb_convert_case(trim($_POST['edificio'] ?? ''), MB_CASE_TITLE, "UTF-8");
    $piso        = trim($_POST['piso'] ?? '');
    $idServico   = $_POST['idServico'] ?? '';
    $sala        = trim($_POST['sala'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    /*
    // 2. Validar
    if (!ctype_digit((string)$idServico)) {
        $erros[] = "O serviço/departamento é obrigatório.";
    }
    // o piso, se preenchido, tem de ser um número inteiro (admite negativos, ex: -1 = cave)
    if ($piso !== '' && !preg_match('/^-?\d+$/', $piso)) {
        $erros[] = "O piso deve ser um número inteiro (ex: 0, 1, -1). Não pode conter letras.";
    }
    // pelo menos uma pista física da localização
    if ($edificio === '' && $piso === '' && $sala === '') {
        $erros[] = "Indique pelo menos o edifício, o piso ou a sala.";
    }
    */

    // 2. Validar (centralizado em validacoes.php)
    $erros = validar_localizacao([
        'edificio'  => $edificio,
        'piso'      => $piso,
        'idServico' => $idServico,
        'sala'      => $sala,
    ]);

    // 3. Inserir se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $sql = "INSERT INTO Localizacoes (edificio, piso, idServico, sala, observacoes)
                    VALUES (:edificio, :piso, :idServico, :sala, :observacoes)";
            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':edificio'    => $edificio ?: null,
                ':piso'        => $piso ?: null,
                ':idServico'   => $idServico,
                ':sala'        => $sala ?: null,
                ':observacoes' => $observacoes ?: null
            ]);
            $ligacao = null;
            registar_log('CRIAR', "Localização criada por " . ($_SESSION['email'] ?? 'desconhecido'));
            $_SESSION['mensagem'] = 'Localização criada com sucesso.';
            $_SESSION['mensagem_tipo'] = 'success';
            header("Location: lista.php");
            exit;
        } catch (PDOException $err) {
            registar_log('ERRO_BD', "Localizacoes: " . $err->getMessage());
            $msg = $err->getMessage();
            if (stripos($msg, 'foreign key') !== false) {
                $erro_sistema = "O serviço selecionado não é válido.";
            } elseif (strpos($msg, 'Data too long') !== false || strpos($msg, 'too long') !== false) {
                $erro_sistema = "Um dos campos tem texto demasiado comprido.";
            } else {
                $erro_sistema = "Não foi possível guardar a localização. Verifique os dados e tente novamente.";
            }
        }
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
            <h2 class="mb-1">
                <i class="fa-solid fa-plus me-2"></i>
                Inserir Localização
            </h2>

            <p class="text-muted mb-0">
                Registe uma nova localização física para equipamentos hospitalares.
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

                <form action="novo.php" method="post">

                    <h5 class="mb-3">
                        Local</h5>

                    <div class="row mb-3">

                        <div class="col-md-3">
                            <label for="edificio" class="form-label">Edifício</label>
                            <input type="text" class="form-control" id="edificio" name="edificio" value="<?= htmlspecialchars($_POST['edificio'] ?? '') ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="piso" class="form-label">Piso</label>
                            <input type="text" class="form-control" id="piso" name="piso" value="<?= htmlspecialchars($_POST['piso'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="idServico" class="form-label obrigatorio">Serviço | Departamento</label>
                            <select class="form-select" id="idServico" name="idServico">
                                <option value="" selected disabled>Escolha...</option>
                                <?php foreach ($lista_servicos as $s) : ?>
                                    <option value="<?= $s->idServico ?>" <?= (($_POST['idServico'] ?? '') == $s->idServico) ? 'selected' : '' ?>><?= htmlspecialchars($s->nome) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="sala" class="form-label">Sala | Gabinete</label>
                            <input type="text" class="form-control" id="sala" name="sala" value="<?= htmlspecialchars($_POST['sala'] ?? '') ?>">
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">Observações</h5>

                    <textarea class="form-control mb-4" id="observacoes" name="observacoes" rows="4"><?= htmlspecialchars($_POST['observacoes'] ?? '') ?></textarea>

                    <div class="d-flex justify-content-end gap-2">

                        <a href="lista.php" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-pink">
                            <i class="fa-regular fa-floppy-disk me-1"></i>
                            Guardar
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>