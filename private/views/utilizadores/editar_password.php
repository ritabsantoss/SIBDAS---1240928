<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
//só o admin
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

$pagina_ativa = 'utilizadores';
$erros = [];
$erro_sistema = '';
$utilizador = null;
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_utilizador'] ?? null;
$idUtilizador = aes_decrypt($idEncriptado);
if (!$idUtilizador || !is_numeric($idUtilizador)) {
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recolher dados
    $nova_password      = $_POST['nova_password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';

    // 2. Validar
    if ($nova_password === '') {
        $erros[] = "A nova password é obrigatória.";
    } elseif (strlen($nova_password) < 8) {
        $erros[] = "A password deve ter pelo menos 8 caracteres.";
    } elseif ($nova_password !== $confirmar_password) {
        $erros[] = "A password e a confirmação não coincidem.";
    }

    // 3. Atualizar se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $novo_hash = password_hash($nova_password, PASSWORD_BCRYPT);
            $stmt = $ligacao->prepare("UPDATE Utilizadores SET password_hash = :hash WHERE idUtilizador = :id");
            $stmt->execute([
                ':hash' => $novo_hash,
                ':id'   => $idUtilizador
            ]);
            $ligacao = null;
            registar_log('EDITAR', "Password redefinida por " . ($_SESSION['email'] ?? 'desconhecido'));
            $_SESSION['mensagem'] = 'Password redefinida com sucesso.';
            $_SESSION['mensagem_tipo'] = 'success';
            header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
            exit;
        } catch (PDOException $err) {
            registar_log('ERRO_BD', "Utilizadores: " . $err->getMessage());
            $erro_sistema = "Não foi possível atualizar a password. Tente novamente.";
        }
    }
}

// Carregar o utilizador para mostrar o nome
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("SELECT nome, email FROM Utilizadores WHERE idUtilizador = :id");
    $stmt->execute([':id' => $idUtilizador]);
    $utilizador = $stmt->fetch(PDO::FETCH_OBJ);
    $ligacao = null;
    if (!$utilizador) {
        header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
        exit;
    }
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Utilizadores: " . $err->getMessage());
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
                <i class="fa-solid fa-key me-2"></i>
                Redefinir Password
            </h2>
            <p class="text-muted mb-0">
                A redefinir a password de <strong><?= htmlspecialchars($utilizador->nome ?? '') ?></strong>.
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

                <form action="editar_password.php?id_utilizador=<?= htmlspecialchars($idEncriptado) ?>" method="post">

                    <h5 class="mb-3">Nova Password</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nova_password" class="form-label obrigatorio">Nova password</label>
                            <input type="password" class="form-control" id="nova_password" name="nova_password">
                        </div>
                        <div class="col-md-6">
                            <label for="confirmar_password" class="form-label obrigatorio">Confirmar nova password</label>
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