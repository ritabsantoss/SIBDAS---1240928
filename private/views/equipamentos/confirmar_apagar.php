<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}

$idEncriptado = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEncriptado);
if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Equipamentos SET ativo = 0 WHERE idEquipamento = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa
}

header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
exit;