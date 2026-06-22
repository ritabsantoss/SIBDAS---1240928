<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

$idEncriptado = $_GET['id_fornecedor'] ?? null;
$idFornecedor = aes_decrypt($idEncriptado);
if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Fornecedores SET ativo = 1 WHERE idFornecedor = :id");
    $stmt->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa
}
registar_log('REATIVAR', "Fornecedor reativado por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Fornecedor reativado com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
exit;