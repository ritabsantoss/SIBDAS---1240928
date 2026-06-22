<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Só o administrador pode desativar documentos
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_utilizador'] ?? null;
$idUtilizador = aes_decrypt($idEncriptado);
if (!$idUtilizador || !is_numeric($idUtilizador)) {
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}
// Desativar  (soft delete — ativo = 0)
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("SELECT email FROM Utilizadores WHERE idUtilizador = :id");
    $stmt->execute([':id' => $idUtilizador]);
    $u = $stmt->fetch(PDO::FETCH_OBJ);

    if ($u && $u->email === $_SESSION['email']) {
        header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
        exit;
    }

    $stmt = $ligacao->prepare("UPDATE Utilizadores SET ativo = 0 WHERE idUtilizador = :id");
    $stmt->execute([':id' => $idUtilizador]);
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa redireciona para a lista de qualquer forma
}
// Registar o evento no log e redirecionar com mensagem de sucesso
registar_log('DESATIVAR', "Utilizador desativado por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Utilizador desativado com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
exit;