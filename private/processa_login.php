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

// Ligação à BD 
try {
    $ligacao = liga_bd();

    // Definir os parâmetros de login 
    $parametros = [
        ':email' => $email
    ];

    // Procurar o utilizador pelo email 
    $comando = $ligacao->prepare("SELECT * FROM Utilizadores WHERE email = :email");
    $comando->execute($parametros);
    $resultados = $comando->fetchAll(PDO::FETCH_OBJ);

    // Verificar se foram encontrados resultados 
    if (count($resultados) === 0) {
        $_SESSION['server_error'] = 'Email ou palavra-passe incorretos.';
        header('Location: ' . BASE_URL . '/private/login.php');
        return;
    }

    // Recolher os dados do utilizador autenticado 
    $utilizador = $resultados[0];

    // Verificar a password (bcrypt em vez de texto simples como na ficha)
    if (!password_verify($password, $utilizador->password_hash)) {
        registar_log('LOGIN_FAIL', "tentativa: $email");
        $_SESSION['server_error'] = 'Email ou palavra-passe incorretos.';
        header('Location: ' . BASE_URL . '/private/login.php');
        return;
    }

    // Verificar se o utilizador está ativo
    if ($utilizador->ativo == 0) {
        registar_log('LOGIN_FAIL', "conta desativada: $email");
        $_SESSION['server_error'] = 'A sua conta está desativada. Contacte o administrador.';
        header('Location: ' . BASE_URL . '/private/login.php');
        return;
    }

    // Atualizar o last_login 
    $comando = $ligacao->prepare("UPDATE Utilizadores SET last_login = NOW() WHERE idUtilizador = ?");
    $comando->execute([$utilizador->idUtilizador]);

    // Guardar dados na sessão 
    $_SESSION['email']  = $utilizador->email;
    $_SESSION['nome']   = $utilizador->nome;
    $_SESSION['perfil'] = $utilizador->perfil;
    $_SESSION['genero'] = $utilizador->genero;

    $ligacao = null;

    // Vai para a área privada
    registar_log('LOGIN_OK', $utilizador->email);
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Login: " . $err->getMessage());
    // Capturar exceções 
    $_SESSION['server_error'] = 'Erro ao ligar à base de dados.';
    header('Location: ' . BASE_URL . '/private/login.php');
    return;
}
