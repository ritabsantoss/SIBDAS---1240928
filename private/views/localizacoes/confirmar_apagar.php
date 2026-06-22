<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Só o administrador pode desativar documentos
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_localizacao'] ?? null;
$idLocalizacao = aes_decrypt($idEncriptado);
if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}
// Desativar (soft delete — ativo = 0)
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Localizacoes SET ativo = 0 WHERE idLocalizacao = :id");
    $stmt->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa - redireciona para a lista de qualquer forma
}
// Registar o evento no log e redirecionar com mensagem de sucesso
registar_log('DESATIVAR', "Localização desativada por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Localização desativada com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
exit;