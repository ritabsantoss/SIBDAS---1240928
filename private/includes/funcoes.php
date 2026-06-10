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