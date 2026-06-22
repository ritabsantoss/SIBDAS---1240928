<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}

$formato = $_GET['formato'] ?? '';
if (!in_array($formato, ['csv', 'json', 'pdf'])) {
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}

try {
    $ligacao = liga_bd();
    $resultados = $ligacao->query(
        "SELECT d.codigo_documento, d.nome_documento, d.tipo_documento,
                e.designacao AS equipamento, e.codigo_interno,
                f.nome_empresa AS fornecedor,
                d.data_documento, d.validade, d.estado_documento,
                IF(d.ativo = 1, 'Ativo', 'Inativo') AS estado_registo
         FROM Documentos d
         JOIN Equipamentos e ON d.idEquipamento = e.idEquipamento
         LEFT JOIN Fornecedores f ON d.idFornecedor = f.idFornecedor
         ORDER BY d.codigo_documento"
    )->fetchAll(PDO::FETCH_ASSOC);
    $ligacao = null;
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Documentos/exportar: " . $err->getMessage());
    header('Location: ' . BASE_URL . '/private/views/documentos/lista.php');
    exit;
}

if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="documentos_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    fputcsv($output, ['Código', 'Nome', 'Tipo', 'Equipamento', 'Código Equip.', 'Fornecedor', 'Data Documento', 'Validade', 'Estado Documento', 'Estado Registo'], ';');
    foreach ($resultados as $row) {
        fputcsv($output, $row, ';');
    }
    fclose($output);
    registar_log('EXPORTAR', "Documentos CSV exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}

if ($formato === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="documentos_' . date('Y-m-d') . '.json"');
    echo json_encode($resultados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    registar_log('EXPORTAR', "Documentos JSON exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}

registar_log('EXPORTAR', "Documentos PDF exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Documentação — <?= date('d/m/Y') ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #003b66; }
        p.data { font-size: 11px; color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #003b66; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 10px; }
        tr:nth-child(even) { background: #f5f5f5; }
        .inativo { color: #999; }
        @media print {
            body { margin: 0; }
            button { display: none; }
            @page { size: A4 landscape; margin: 1cm; }
        }
    </style>
</head>
<body>
    <h1>Lista de Documentação</h1>
    <p class="data">Exportado em <?= date('d/m/Y H:i') ?> por <?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Equipamento</th>
                <th>Fornecedor</th>
                <th>Data Documento</th>
                <th>Validade</th>
                <th>Estado</th>
                <th>Estado Registo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $row) : ?>
                <tr class="<?= $row['estado_registo'] === 'Inativo' ? 'inativo' : '' ?>">
                    <td><?= htmlspecialchars($row['codigo_documento']) ?></td>
                    <td><?= htmlspecialchars($row['nome_documento'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['tipo_documento'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['equipamento']) ?></td>
                    <td><?= htmlspecialchars($row['fornecedor'] ?? '—') ?></td>
                    <td><?= $row['data_documento'] ? date('d/m/Y', strtotime($row['data_documento'])) : '—' ?></td>
                    <td><?= $row['validade'] ? date('d/m/Y', strtotime($row['validade'])) : '—' ?></td>
                    <td><?= htmlspecialchars($row['estado_documento'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['estado_registo']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>