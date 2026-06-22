<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Só o administrador pode desativar
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}
// Desencriptar e validar o ID recebido por GET
$idEncriptado = $_GET['id_fornecedor'] ?? null;
$idFornecedor = aes_decrypt($idEncriptado);
if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}
// Desativar (soft delete — ativo = 0)
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->prepare("UPDATE Fornecedores SET ativo = 0 WHERE idFornecedor = :id");
    $stmt->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa — redireciona para a lista de qualquer forma
}
// Registar o evento no log e redirecionar com mensagem de sucesso
registar_log('DESATIVAR', "Fornecedor desativado por " . ($_SESSION['email'] ?? 'desconhecido'));
$_SESSION['mensagem'] = 'Fornecedor desativado com sucesso.';
$_SESSION['mensagem_tipo'] = 'success';
header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
exit;