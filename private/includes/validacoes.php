<?php
// Validações reutilizáveis (Ficha 13) — usadas no novo e no editar
// Cada função recebe os dados já recolhidos e devolve um array de erros.
function validar_fornecedor(array $d): array
{
    $erros = [];

    if (($d['nome_empresa'] ?? '') === '') {
        $erros[] = "O nome da empresa é obrigatório.";
    }

    $nif = $d['nif'] ?? '';
    if ($nif !== '' && (!ctype_digit($nif) || strlen($nif) !== 9)) {
        $erros[] = "O NIF deve ter 9 dígitos.";
    }

    $email = $d['email'] ?? '';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não tem um formato válido.";
    }

    $telefone = $d['telefone'] ?? '';
    $tel1 = preg_replace('/[\s+()-]/', '', $telefone);
    if ($telefone !== '' && (!ctype_digit($tel1) || strlen($tel1) < 7 || strlen($tel1) > 15)) {
        $erros[] = "O contacto telefónico não é válido.";
    }

    $telefone_contacto = $d['telefone_contacto'] ?? '';
    $tel2 = preg_replace('/[\s+()-]/', '', $telefone_contacto);
    if ($telefone_contacto !== '' && (!ctype_digit($tel2) || strlen($tel2) < 7 || strlen($tel2) > 15)) {
        $erros[] = "O telefone da pessoa de contacto não é válido.";
    }

    return $erros;
}

function validar_localizacao(array $d): array
{
    $erros = [];

    if (!ctype_digit((string)($d['idServico'] ?? ''))) {
        $erros[] = "O serviço/departamento é obrigatório.";
    }

    $piso = $d['piso'] ?? '';
    if ($piso !== '' && !preg_match('/^-?\d+$/', $piso)) {
        $erros[] = "O piso deve ser um número inteiro (ex: 0, 1, -1). Não pode conter letras.";
    }

    if (($d['edificio'] ?? '') === '' && $piso === '' && ($d['sala'] ?? '') === '') {
        $erros[] = "Indique pelo menos o edifício, o piso ou a sala.";
    }

    return $erros;
}