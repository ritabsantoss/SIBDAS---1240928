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

function ficheiro_de_secao(string $secao, int $i, string $campo): ?array
{
    // normaliza a estrutura aninhada do $_FILES de uma secção repetível
    // ex.: documentos[i][ficheiro] -> array normal ['name','tmp_name','error','size']
    if (!isset($_FILES[$secao]['name'][$i][$campo])) {
        return null;
    }
    return [
        'name'     => $_FILES[$secao]['name'][$i][$campo],
        'type'     => $_FILES[$secao]['type'][$i][$campo] ?? '',
        'tmp_name' => $_FILES[$secao]['tmp_name'][$i][$campo],
        'error'    => $_FILES[$secao]['error'][$i][$campo],
        'size'     => $_FILES[$secao]['size'][$i][$campo],
    ];
}

function guarda_ficheiro_array(?array $f, string $prefixo): ?string
{
    // igual ao guarda_ficheiro_upload mas recebe o array do ficheiro diretamente
    // (útil para secções repetíveis onde o $_FILES vem aninhado)
    if (empty($f) || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
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

function data_real($valor)
{
    // true se vazio (campo opcional) ou se for uma data AAAA-MM-DD real
    if ($valor === '') return true;
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) return false;
    [$a, $m, $d] = explode('-', $valor);
    return checkdate((int)$m, (int)$d, (int)$a);
}

function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt($value, OPENSSL_METHOD, OPENSSL_KEY, OPENSSL_RAW_DATA, OPENSSL_IV));
}
function aes_decrypt($value)
{
    if (!is_string($value) || strlen($value) % 2 !== 0) return false;
    return openssl_decrypt(hex2bin($value), OPENSSL_METHOD, OPENSSL_KEY, OPENSSL_RAW_DATA, OPENSSL_IV);
}

function erro_bd_equipamento(Exception $err, string $acao = 'guardar'): string
{
    $msg = $err->getMessage();

    if ($err instanceof PDOException && strpos($msg, '23000') !== false) {
        if (strpos($msg, 'codigo_interno') !== false)    return "Já existe um equipamento com esse código interno.";
        if (strpos($msg, 'codigo_documento') !== false)  return "Já existe um documento com esse código.";
        if (strpos($msg, 'codigo_garantia') !== false)   return "Já existe uma garantia com esse código.";
        if (strpos($msg, 'codigo_contrato') !== false)   return "Já existe um contrato com esse código.";
        if (strpos($msg, 'codigo_componente') !== false) return "Já existe um componente com esse código.";
        if (strpos($msg, 'Equipamentos_index_0') !== false)           return "Já existe um equipamento com a mesma marca/modelo/número de série.";
        if (strpos($msg, 'Equipamento_Fornecedor_index_1') !== false) return "Esse fornecedor já foi associado a este equipamento com o mesmo tipo de relação.";
        if (stripos($msg, 'foreign key') !== false)      return "Foi selecionada uma categoria, localização ou fornecedor inválido.";
        return "Já existe um registo duplicado.";
    }
    if (strpos($msg, 'ficheiro excede') !== false || strpos($msg, 'Tipo de ficheiro') !== false || strpos($msg, 'carregar o ficheiro') !== false || strpos($msg, 'guardar o ficheiro') !== false) {
        return $msg;
    }
    if (strpos($msg, 'too long') !== false) {
        return "Um dos campos tem texto demasiado comprido.";
    }
    if (strpos($msg, 'Incorrect') !== false || strpos($msg, 'Data truncated') !== false) {
        return "Um dos valores selecionados não é válido.";
    }
    return "Não foi possível $acao o equipamento. Verifique os dados e tente novamente.";
}

function conteudo(array $conteudos, string $chave): string {
    return htmlspecialchars($conteudos[$chave] ?? '');
}