<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEncriptado);
if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}
// Reativar (ativo = 1)
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Equipamentos SET ativo = 1 WHERE idEquipamento = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa
}
registar_log('REATIVAR', "Equipamento reativado por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Equipamento reativado com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
exit;