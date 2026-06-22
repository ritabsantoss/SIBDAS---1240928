<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

$idEncriptado = $_GET['id_utilizador'] ?? null;
$idUtilizador = aes_decrypt($idEncriptado);
if (!$idUtilizador || !is_numeric($idUtilizador)) {
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Utilizadores SET ativo = 1 WHERE idUtilizador = :id");
    $stmt->execute([':id' => $idUtilizador]);
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa
}
$_SESSION['mensagem'] = 'Utilizador reativado com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
exit;