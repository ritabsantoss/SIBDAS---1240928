<?php
require_once __DIR__ . '/includes/funcoes.php';
start_session();

// Segurança: só por POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php');
    return;
}

// Recolha
$email    = isset($_POST['email'])    ? $_POST['email']    : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validação
$validation_errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O utilizador tem de ser um email válido.';
}
if (strlen($email) > 100) {
    $validation_errors[] = 'O email é demasiado longo.';
}
if (strlen($password) < 8) {
    $validation_errors[] = 'A palavra-passe deve ter pelo menos 8 caracteres.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: ' . BASE_URL . '/private/login.php');
    return;
}

// SIMULAÇÃO do resultado da BD (depois troca-se por consulta real)
$result['status'] = 1;
if (!$result['status']) {
    $_SESSION['server_error'] = 'Email ou palavra-passe incorretos.';
    header('Location: ' . BASE_URL . '/private/login.php');
    return;
}

// LOGIN VÁLIDO — guardar o utilizador
$_SESSION['email'] = $email;
// Com a BD: $_SESSION['nome'] = $row['nome'];

// Vai para a área privada
header('Location: ' . BASE_URL . '/private/index.php');
exit;