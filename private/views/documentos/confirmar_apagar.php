<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Só o administrador pode desativar
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_documento'] ?? null;
$idDocumento = aes_decrypt($idEncriptado);
if (!$idDocumento || !is_numeric($idDocumento)) {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}
// Desativar o documento (soft delete — ativo = 0)
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Documentos SET ativo = 0 WHERE idDocumento = :id");
    $stmt->bindParam(':id', $idDocumento, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa - redireciona para a lista de qualquer forma
}
// Registar o evento no log e redirecionar com mensagem de sucesso
registar_log('DESATIVAR', "Documento desativado por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Documento desativado com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
exit;