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


function validar_equipamento(array $d): array
{
    $erros = [];
 
    if (($d['codigo_interno'] ?? '') === '') $erros[] = "O código interno é obrigatório.";
    if (($d['designacao'] ?? '') === '')     $erros[] = "A designação é obrigatória.";
    if (!ctype_digit((string)($d['idCategoria'] ?? '')))   $erros[] = "A categoria é obrigatória.";
    if (!ctype_digit((string)($d['idLocalizacao'] ?? ''))) $erros[] = "A localização é obrigatória.";
 
    $estado = $d['estado_atual'] ?? '';
    if ($estado === '' || $estado === 'Escolha...') $erros[] = "O estado é obrigatório.";
    $crit = $d['criticidade'] ?? '';
    if ($crit === '' || $crit === 'Escolha...') $erros[] = "A criticidade é obrigatória.";
 
    // Fornecedores: pelo menos um válido
    $fornecedores = $d['fornecedores'] ?? [];
    if (empty($fornecedores) || !is_array($fornecedores)) {
        $erros[] = "Adicione pelo menos um fornecedor.";
    } else {
        $temValido = false;
        $n = 0;
        foreach ($fornecedores as $f) {
            $n++;
            $idForn  = $f['id_fornecedor'] ?? '';
            $tipoRel = $f['tipo_relacao'] ?? '';
            if (!ctype_digit((string)$idForn)) $erros[] = "Fornecedor $n: selecione um fornecedor.";
            if ($tipoRel === '' || $tipoRel === 'Escolha...') $erros[] = "Fornecedor $n: selecione o tipo de relação.";
            if (ctype_digit((string)$idForn) && $tipoRel !== '' && $tipoRel !== 'Escolha...') $temValido = true;
        }
        if (!$temValido) $erros[] = "É obrigatório associar pelo menos um fornecedor válido.";
    }
 
    $custo = $d['custo'] ?? '';
    if ($custo !== '' && (!is_numeric($custo) || $custo < 0)) $erros[] = "O custo não pode ser negativo.";
 
    $ano = $d['ano_fabrico'] ?? '';
    if ($ano !== '' && (!ctype_digit((string)$ano) || $ano < 1950 || $ano > (int)date('Y')))
        $erros[] = "O ano de fabrico deve estar entre 1950 e " . date('Y') . ".";
 
    $data_aq = $d['data_aquisicao'] ?? '';
    if ($data_aq !== '' && $data_aq > date('Y-m-d')) $erros[] = "A data de aquisição não pode ser no futuro.";
    if (!data_real($data_aq)) $erros[] = "Data de aquisição inválida.";
 
    $dig = $d['data_inicio_garantia'] ?? '';
    $dfg = $d['data_fim_garantia'] ?? '';
    if (!data_real($dig)) $erros[] = "Data de início da garantia inválida.";
    if (!data_real($dfg)) $erros[] = "Data de fim da garantia inválida.";
    if (($d['tem_garantia'] ?? false) && $dig !== '' && $dfg !== '' && $dfg < $dig) {
        $erros[] = "A data de fim da garantia não pode ser anterior à data de início.";
    }
 
    // Documentos
    $documentos = $d['documentos'] ?? [];
    if (!empty($documentos) && is_array($documentos)) {
        $n = 0;
        foreach ($documentos as $doc) {
            $n++;
            $cod     = trim($doc['codigo_documento'] ?? '');
            $tipo    = $doc['tipo_documento'] ?? '';
            $nomeDoc = trim($doc['nome_documento'] ?? '');
            $dataDoc = trim($doc['data_documento'] ?? '');
            $val     = trim($doc['validade'] ?? '');
            $estadoD = $doc['estado_documento'] ?? '';
            $obsD    = trim($doc['observacoes_documentacao'] ?? '');
            $temDoc = ($tipo !== '' || $nomeDoc !== '' || $dataDoc !== '' || $val !== '' || $estadoD !== '' || $obsD !== '');
            if (!$temDoc) continue;
            if ($cod === '') $erros[] = "Documento $n: o código é obrigatório.";
            if ($tipo === '' || $tipo === 'Escolha...') $erros[] = "Documento $n: o tipo é obrigatório.";
            if ($dataDoc !== '' && $val !== '' && $val < $dataDoc) $erros[] = "Documento $n: a validade não pode ser anterior à data do documento.";
            if (!data_real($dataDoc)) $erros[] = "Documento $n: data do documento inválida.";
            if (!data_real($val))     $erros[] = "Documento $n: data de validade inválida.";
        }
    }
 
    // Componentes
    $componentes = $d['componentes'] ?? [];
    if (!empty($componentes) && is_array($componentes)) {
        $n = 0;
        foreach ($componentes as $comp) {
            $n++;
            $cod  = trim($comp['codigo_componente'] ?? '');
            $nome = trim($comp['nome_componente'] ?? '');
            if ($cod === '' && $nome === '') continue;
            if ($cod === '')  $erros[] = "Componente $n: o código é obrigatório.";
            if ($nome === '') $erros[] = "Componente $n: o nome é obrigatório.";
        }
    }
 
    return $erros;
}
 
