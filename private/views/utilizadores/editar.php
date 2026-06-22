<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'utilizadores';
$erros = [];
$erro_sistema = '';
$sucesso = '';

// desencriptar e validar o ID (Ficha 13)
$idEncriptado = $_GET['id_utilizador'] ?? null;
$idUtilizador = aes_decrypt($idEncriptado);
if (!$idUtilizador || !is_numeric($idUtilizador)) {
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}

// POST: validar e atualizar (antes do SELECT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recolher dados
    $nome   = trim($_POST['nome'] ?? '');
    $email  = strtolower(trim($_POST['email'] ?? ''));
    $perfil = $_POST['perfil'] ?? '';
    $genero = $_POST['genero'] ?? '';

    // 2. Validar
    if ($nome === '') {
        $erros[] = "O nome é obrigatório.";
    }
    if ($email === '') {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não tem um formato válido.";
    }
    if ($perfil === '' || $perfil === 'Escolha...') {
        $erros[] = "O perfil é obrigatório.";
    }
    if ($genero === '' || $genero === 'Escolha...') {
        $erros[] = "O género é obrigatório.";
    }

    // 3. Atualizar se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $stmt = $ligacao->prepare(
                "UPDATE Utilizadores SET nome = :nome, email = :email,
                 perfil = :perfil, genero = :genero
                 WHERE idUtilizador = :id"
            );
            $stmt->execute([
                ':nome'   => $nome,
                ':email'  => $email,
                ':perfil' => $perfil,
                ':genero' => $genero,
                ':id'     => $idUtilizador
            ]);
            $ligacao = null;
            $sucesso = 'Utilizador atualizado com sucesso.';
        } catch (PDOException $err) {
            $msg = $err->getMessage();
            if (strpos($msg, '23000') !== false) {
                $erro_sistema = "Já existe um utilizador com esse email.";
            } elseif (strpos($msg, 'too long') !== false) {
                $erro_sistema = "Um dos campos tem texto demasiado comprido.";
            } else {
                $erro_sistema = "Não foi possível atualizar o utilizador. Verifique os dados e tente novamente.";
            }
        }
    }
}

// SELECT: carregar o registo
$utilizador = null;
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("SELECT * FROM Utilizadores WHERE idUtilizador = :id");
    $stmt->bindParam(':id', $idUtilizador, PDO::PARAM_INT);
    $stmt->execute();
    $utilizador = $stmt->fetch(PDO::FETCH_OBJ);
    $ligacao = null;
    if (!$utilizador) {
        header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
        exit;
    }
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar o utilizador.";
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1">
                <i class="fa-regular fa-pen-to-square me-2"></i>
                Editar Utilizador
            </h2>
            <p class="text-muted mb-0">
                Atualize as informações do utilizador selecionado.
            </p>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <?php if (!empty($sucesso)) : ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($sucesso) ?>
                    </div>
                <?php endif; ?>

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

                <form action="editar.php?id_utilizador=<?= htmlspecialchars($idEncriptado) ?>" method="post">

                    <h5 class="mb-3">Identificação</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                value="<?= htmlspecialchars($_POST['nome'] ?? $utilizador?->nome ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? $utilizador?->email ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="perfil" class="form-label">Perfil</label>
                            <select class="form-select" id="perfil" name="perfil">
                                <option value="" disabled <?= empty($_POST['perfil'] ?? $utilizador?->perfil) ? 'selected' : '' ?>>Escolha...</option>
                                <?php foreach (['administrador', 'tecnico', 'profissional'] as $op) : ?>
                                    <option value="<?= $op ?>"
                                        <?= (($_POST['perfil'] ?? $utilizador?->perfil ?? '') === $op) ? 'selected' : '' ?>>
                                        <?= ucfirst($op) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="genero" name="genero">
                                <option value="" disabled <?= empty($_POST['genero'] ?? $utilizador?->genero) ? 'selected' : '' ?>>Escolha...</option>
                                <option value="M" <?= (($_POST['genero'] ?? $utilizador?->genero ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= (($_POST['genero'] ?? $utilizador?->genero ?? '') === 'F') ? 'selected' : '' ?>>Feminino</option>
                            </select>
                        </div>
                    </div>

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