<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}

$idEncriptado = $_GET['id_localizacao'] ?? null;
$idLocalizacao = aes_decrypt($idEncriptado);
if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Localizacoes SET ativo = 0 WHERE idLocalizacao = :id");
    $stmt->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa
}
registar_log('DESATIVAR', "Localização desativada por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Localização desativada com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/localizacoes/lista.php');
exit;