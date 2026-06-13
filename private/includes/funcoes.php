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

function guarda_ficheiro_upload($campo, $prefixo)
{
    // Devolve o nome do ficheiro guardado, ou null se não foi enviado nenhum.
    // Lança Exception se o ficheiro for inválido.
    if (empty($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // campo opcional, sem ficheiro
    }

    $f = $_FILES[$campo];

    if ($f['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Ocorreu um erro ao carregar o ficheiro.");
    }
    if ($f['size'] > 5 * 1024 * 1024) {
        throw new Exception("O ficheiro excede o limite de 5 MB.");
    }

    $permitidas = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidas)) {
        throw new Exception("Tipo de ficheiro não permitido (use PDF, imagem ou Word).");
    }

    if (!is_dir(PASTA_UPLOADS)) {
        mkdir(PASTA_UPLOADS, 0775, true);
    }

    // nome único e seguro (não confiamos no nome original)
    $nome = $prefixo . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

    if (!move_uploaded_file($f['tmp_name'], PASTA_UPLOADS . $nome)) {
        throw new Exception("Não foi possível guardar o ficheiro no servidor.");
    }

    return $nome;
}

function proximo_numero_codigo($ligacao, $tabela, $coluna, $prefixo)
{
    $pos = strlen($prefixo) + 2; // posição do número depois de "PREFIXO-"
    $sql = "SELECT $coluna FROM $tabela
            WHERE $coluna LIKE '$prefixo-%'
            ORDER BY CAST(SUBSTRING($coluna, $pos) AS UNSIGNED) DESC
            LIMIT 1";
    $ultimo = $ligacao->query($sql)->fetchColumn();
    if ($ultimo !== false && $ultimo !== null) {
        return (int) substr($ultimo, strlen($prefixo) + 1) + 1;
    }
    return 1;
}

function formata_codigo($prefixo, $numero)
{
    return $prefixo . '-' . str_pad((string) $numero, 4, '0', STR_PAD_LEFT);
}


