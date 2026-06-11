<?php
require_once __DIR__ . '/../../config/config.php'; 

// Inicia a sessão só se ainda não estiver iniciada
function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Há utilizador autenticado?
function check_session() {
    return isset($_SESSION['email']);
}

// Reencaminha para o login se NÃO estiver autenticado
function redirect_if_not_logged($redirect_to = '/private/login.php')
{
    start_session();

    if (!check_session()) {
        header('Location: ' . BASE_URL . $redirect_to);
        exit;
    }
}

// Termina a sessão e volta ao login
function logout_and_redirect($redirect_to = '/private/login.php')
{
    start_session();
    session_unset();
    session_destroy();

    header('Location: ' . BASE_URL . $redirect_to);
    exit;
}

// Abre e devolve uma ligação PDO à base de dados
function liga_bd()
{
    $dsn = "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT
         . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4";
    $ligacao = new PDO($dsn, MYSQL_USERNAME, MYSQL_PASSWORD);
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $ligacao;
}