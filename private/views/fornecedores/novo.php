<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] === 'profissional') {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}
require_once __DIR__ . '/../../includes/validacoes.php';

$pagina_ativa = 'fornecedores';

$erros = [];
$erro_sistema = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Recolher dados
    $nome_empresa      = trim($_POST['nome_empresa'] ?? '');
    $nif = preg_replace('/\s+/', '', trim($_POST['nif'] ?? ''));
    $telefone          = trim($_POST['telefone'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $website           = trim($_POST['website'] ?? '');
    $morada            = trim($_POST['morada'] ?? '');
    $pessoa_contacto = ucwords(strtolower(trim($_POST['pessoa_contacto'] ?? '')));
    $telefone_contacto = trim($_POST['telefone_contacto'] ?? '');
    $observacoes       = trim($_POST['observacoes'] ?? '');

    /*
    // 2. Validar
    if ($nome_empresa === '') {
        $erros[] = "O nome da empresa é obrigatório.";
    }
    if ($nif !== '' && (!ctype_digit($nif) || strlen($nif) !== 9)) {
        $erros[] = "O NIF deve ter 9 dígitos.";
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não tem um formato válido.";
    }
    $tel1 = preg_replace('/[\s+()-]/', '', $telefone);
    if ($telefone !== '' && (!ctype_digit($tel1) || strlen($tel1) < 7 || strlen($tel1) > 15)) {
        $erros[] = "O contacto telefónico não é válido.";
    }
    $tel2 = preg_replace('/[\s+()-]/', '', $telefone_contacto);
    if ($telefone_contacto !== '' && (!ctype_digit($tel2) || strlen($tel2) < 7 || strlen($tel2) > 15)) {
        $erros[] = "O telefone da pessoa de contacto não é válido.";
    }
        */

    // 2. Validar (centralizado em validacoes.php)
    $erros = validar_fornecedor([
        'nome_empresa'      => $nome_empresa,
        'nif'               => $nif,
        'email'             => $email,
        'telefone'          => $telefone,
        'telefone_contacto' => $telefone_contacto,
    ]);

    // 3. Inserir se não houver erros
    if (empty($erros)) {
        try {
            $ligacao = liga_bd();
            $sql = "INSERT INTO Fornecedores
                    (nome_empresa, nif, telefone, email, website, morada, pessoa_contacto, telefone_contacto, observacoes)
                    VALUES
                    (:nome_empresa, :nif, :telefone, :email, :website, :morada, :pessoa_contacto, :telefone_contacto, :observacoes)";
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
                ':observacoes'       => $observacoes ?: null
            ]);
            $ligacao = null;
            header("Location: lista.php");
            exit;
        } catch (PDOException $err) {
            $msg = $err->getMessage();
            if (strpos($msg, '23000') !== false) {
                if (strpos($msg, 'nif') !== false) {
                    $erro_sistema = "Já existe um fornecedor com esse NIF.";
                } else {
                    $erro_sistema = "Já existe um fornecedor com esses dados.";
                }
            } elseif (strpos($msg, 'Data too long') !== false || strpos($msg, 'too long') !== false) {
                $erro_sistema = "Um dos campos tem texto demasiado comprido.";
            } else {
                $erro_sistema = "Não foi possível guardar o fornecedor. Verifique os dados e tente novamente.";
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
                    Inserir Fornecedor
                </h2>

                <p class="text-muted mb-0">
                    Preencha as principais informações do fornecedor.
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

                    <form action="#" method="post" >

                        <h5 class="mb-3">
                            Identificação</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome_empresa" class="form-label">Nome da empresa</label>
                                <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" value="<?= htmlspecialchars($_POST['nome_empresa'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="nif" class="form-label">NIF</label>
                                <input type="text" class="form-control" id="nif" name="nif" value="<?= htmlspecialchars($_POST['nif'] ?? '') ?>">
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">
                            Contactos</h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label for="telefone" class="form-label">Contacto telefónico</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-12">
                                <label for="morada" class="form-label">Morada</label>
                                <input type="text" class="form-control" id="morada" name="morada" value="<?= htmlspecialchars($_POST['morada'] ?? '') ?>">
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">
                            Pessoa de contacto</h5>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="pessoa_contacto" class="form-label">Pessoa de contacto</label>
                                <input type="text" class="form-control" id="pessoa_contacto" name="pessoa_contacto" value="<?= htmlspecialchars($_POST['pessoa_contacto'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="telefone_contacto" class="form-label">Telefone da pessoa de contacto</label>
                                <input type="text" class="form-control" id="telefone_contacto" name="telefone_contacto" value="<?= htmlspecialchars($_POST['telefone_contacto'] ?? '') ?>">
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