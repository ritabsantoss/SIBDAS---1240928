<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

$pagina_ativa = 'utilizadores';

$erros = [];
$erro_sistema = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Recolher dados
    $nome     = trim($_POST['nome'] ?? '');
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';
    $perfil   = $_POST['perfil'] ?? '';
    $genero   = $_POST['genero'] ?? '';

    // 2. Validar
    if ($nome === '') {
        $erros[] = "O nome é obrigatório.";
    }
    if ($email === '') {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não tem um formato válido.";
    }
    if ($password === '') {
        $erros[] = "A password é obrigatória.";
    } elseif (strlen($password) < 8) {
        $erros[] = "A password deve ter pelo menos 8 caracteres.";
    } elseif ($password !== $confirmar_password) {
        $erros[] = "A password e a confirmação não coincidem.";
    }
    if ($perfil === '' || $perfil === 'Escolha...') {
        $erros[] = "O perfil é obrigatório.";
    }
    if ($genero === '' || $genero === 'Escolha...') {
        $erros[] = "O género é obrigatório.";
    }

    // 3. Inserir se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO Utilizadores (nome, email, password_hash, perfil, genero)
                    VALUES (:nome, :email, :password_hash, :perfil, :genero)";
            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':nome'          => $nome,
                ':email'         => $email,
                ':password_hash' => $password_hash,
                ':perfil'        => $perfil,
                ':genero'        => $genero
            ]);
            $ligacao = null;
            registar_log('CRIAR', "Utilizador criado por " . ($_SESSION['email'] ?? 'desconhecido'));
            $_SESSION['mensagem'] = 'Utilizador criado com sucesso.';
            $_SESSION['mensagem_tipo'] = 'success';
            header("Location: lista.php");
            exit;
        } catch (PDOException $err) {
            registar_log('ERRO_BD', "Utilizadores: " . $err->getMessage());
            $msg = $err->getMessage();
            if (strpos($msg, '23000') !== false) {
                $erro_sistema = "Já existe um utilizador com esse email.";
            } elseif (strpos($msg, 'too long') !== false) {
                $erro_sistema = "Um dos campos tem texto demasiado comprido.";
            } else {
                $erro_sistema = "Não foi possível criar o utilizador. Verifique os dados e tente novamente.";
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
                Novo Utilizador
            </h2>
            <p class="text-muted mb-0">
                Preencha as informações do novo utilizador.
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

                    <h5 class="mb-3">Identificação</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label obrigatorio">Nome completo</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label obrigatorio">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="perfil" class="form-label obrigatorio">Perfil</label>
                            <select class="form-select" id="perfil" name="perfil">
                                <option value="" disabled <?= empty($_POST['perfil']) ? 'selected' : '' ?>>Escolha...</option>
                                <?php foreach (['administrador', 'tecnico', 'profissional'] as $op) : ?>
                                    <option value="<?= $op ?>"
                                        <?= (($_POST['perfil'] ?? '') === $op) ? 'selected' : '' ?>>
                                        <?= ucfirst($op) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="genero" class="form-label obrigatorio">Género</label>
                            <select class="form-select" id="genero" name="genero">
                                <option value="" disabled <?= empty($_POST['genero']) ? 'selected' : '' ?>>Escolha...</option>
                                <option value="M" <?= (($_POST['genero'] ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= (($_POST['genero'] ?? '') === 'F') ? 'selected' : '' ?>>Feminino</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Password</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label obrigatorio">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="col-md-6">
                            <label for="confirmar_password" class="form-label obrigatorio">Confirmar password</label>
                            <input type="password" class="form-control" id="confirmar_password" name="confirmar_password">
                        </div>
                    </div>

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