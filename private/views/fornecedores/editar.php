<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] === 'profissional') {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}
require_once __DIR__ . '/../../includes/validacoes.php';

// permitir apenas GET e POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_ativa = 'fornecedores';
$erros = [];
$erro_sistema = '';
$sucesso = '';

// desencriptar e validar o ID 
$idEncriptado = $_GET['id_fornecedor'] ?? null;
$idFornecedor = aes_decrypt($idEncriptado);
if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

// POST: validar e atualizar (tratado antes do SELECT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recolher (recolha fica no ficheiro)
    $nome_empresa      = trim($_POST['nome_empresa'] ?? '');
    $nif               = preg_replace('/\s+/', '', trim($_POST['nif'] ?? ''));
    $telefone          = trim($_POST['telefone'] ?? '');
    $email             = strtolower(trim($_POST['email'] ?? ''));
    $website           = trim($_POST['website'] ?? '');
    $morada            = trim($_POST['morada'] ?? '');
    $pessoa_contacto   = trim($_POST['pessoa_contacto'] ?? '');
    $telefone_contacto = trim($_POST['telefone_contacto'] ?? '');
    $observacoes       = trim($_POST['observacoes'] ?? '');

    // 2. Validar (centralizado em validacoes.php)
    $erros = validar_fornecedor([
        'nome_empresa'      => $nome_empresa,
        'nif'               => $nif,
        'email'             => $email,
        'telefone'          => $telefone,
        'telefone_contacto' => $telefone_contacto,
    ]);

    // 3. Atualizar se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $sql = "UPDATE Fornecedores SET
                        nome_empresa = :nome_empresa, nif = :nif, telefone = :telefone,
                        email = :email, website = :website, morada = :morada,
                        pessoa_contacto = :pessoa_contacto, telefone_contacto = :telefone_contacto,
                        observacoes = :observacoes
                    WHERE idFornecedor = :id";
            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':nome_empresa'      => $nome_empresa,
                ':nif'               => $nif ?: null,
                ':telefone'          => $telefone ?: null,
                ':email'             => $email ?: null,
                ':website'           => $website ?: null,
                ':morada'            => $morada ?: null,
                ':pessoa_contacto'   => $pessoa_contacto ?: null,
                ':telefone_contacto' => $telefone_contacto ?: null,
                ':observacoes'       => $observacoes ?: null,
                ':id'                => $idFornecedor
            ]);
            $ligacao = null;
            registar_log('EDITAR', "Fornecedor editado por " . ($_SESSION['email'] ?? 'desconhecido'));
            $sucesso = 'Fornecedor atualizado com sucesso.';
        } catch (PDOException $err) {
            registar_log('ERRO_BD', "Fornecedores: " . $err->getMessage());
            $msg = $err->getMessage();
            if (strpos($msg, '23000') !== false) {
                $erro_sistema = (strpos($msg, 'nif') !== false)
                    ? "Já existe um fornecedor com esse NIF."
                    : "Já existe um fornecedor com esses dados.";
            } else {
                $erro_sistema = "Não foi possível atualizar o fornecedor. Verifique os dados e tente novamente.";
            }
        }
    }
}

// Carregar o registo da BD (para preencher o formulário)
$fornecedor = null;
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("SELECT * FROM Fornecedores WHERE idFornecedor = :id");
    $stmt->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);
    $ligacao = null;
    if (!$fornecedor) {
        header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
        exit;
    }
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Fornecedores: " . $err->getMessage());
    $erro_sistema = "Erro ao carregar o fornecedor.";
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
                Editar Fornecedor
            </h2>

            <p class="text-muted mb-0">
                Atualize as informações do fornecedor selecionado.
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

                <form action="editar.php?id_fornecedor=<?= htmlspecialchars($idEncriptado) ?>" method="post">

                    <h5 class="mb-3">
                        Identificação</h5>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="nome_empresa" class="form-label obrigatorio">Nome da empresa</label>
                            <input type="text" class="form-control" id="nome_empresa" name="nome_empresa"
                                value="<?= htmlspecialchars($_POST['nome_empresa'] ?? $fornecedor->nome_empresa ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="nif" class="form-label">NIF</label>
                            <input type="text" class="form-control" id="nif" name="nif"
                                value="<?= htmlspecialchars($_POST['nif'] ?? $fornecedor->nif ?? '') ?>">
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">
                        Contactos</h5>

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label for="telefone" class="form-label">Contacto telefónico</label>
                            <input type="text" class="form-control" id="telefone" name="telefone"
                                value="<?= htmlspecialchars($_POST['telefone'] ?? $fornecedor->telefone ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? $fornecedor->email ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" id="website" name="website"
                                value="<?= htmlspecialchars($_POST['website'] ?? $fornecedor->website ?? '') ?>">
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-12">
                            <label for="morada" class="form-label">Morada</label>
                            <input type="text" class="form-control" id="morada" name="morada"
                                value="<?= htmlspecialchars($_POST['morada'] ?? $fornecedor->morada ?? '') ?>">
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">
                        Pessoa de contacto</h5>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="pessoa_contacto" class="form-label">Pessoa de contacto</label>
                            <input type="text" class="form-control" id="pessoa_contacto" name="pessoa_contacto"
                                value="<?= htmlspecialchars($_POST['pessoa_contacto'] ?? $fornecedor->pessoa_contacto ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="telefone_contacto" class="form-label">Telefone da pessoa de contacto</label>
                            <input type="text" class="form-control" id="telefone_contacto" name="telefone_contacto"
                                value="<?= htmlspecialchars($_POST['telefone_contacto'] ?? $fornecedor->telefone_contacto ?? '') ?>">
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">Observações</h5>

                    <textarea class="form-control mb-4" id="observacoes" name="observacoes"
                        rows="4"><?= htmlspecialchars($_POST['observacoes'] ?? $fornecedor->observacoes ?? '') ?></textarea>

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