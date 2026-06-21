<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}

$idEncriptado = $_GET['id_documento'] ?? null;
$idDocumento = aes_decrypt($idEncriptado);
if (!$idDocumento || !is_numeric($idDocumento)) {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Documentos SET ativo = 1 WHERE idDocumento = :id");
    $stmt->bindParam(':id', $idDocumento, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa
}

header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
exit;