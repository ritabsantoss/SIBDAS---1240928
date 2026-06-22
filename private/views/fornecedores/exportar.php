<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

$formato = $_GET['formato'] ?? '';
if (!in_array($formato, ['csv', 'json', 'pdf'])) {
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

// Carregar dados
try {
    $ligacao = liga_bd();
    $resultados = $ligacao->query(
        "SELECT nome_empresa, nif, telefone, email, website, morada,
                pessoa_contacto, telefone_contacto, observacoes,
                IF(ativo = 1, 'Ativo', 'Inativo') AS estado
         FROM Fornecedores
         ORDER BY nome_empresa"
    )->fetchAll(PDO::FETCH_ASSOC);
    $ligacao = null;
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Fornecedores/exportar: " . $err->getMessage());
    header('Location: ' . BASE_URL . '/private/views/fornecedores/lista.php');
    exit;
}

// CSV
if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="fornecedores_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    // BOM UTF-8 para acentos no Excel
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    // cabeçalho
    fputcsv($output, ['Nome Empresa', 'NIF', 'Telefone', 'Email', 'Website', 'Morada', 'Pessoa de Contacto', 'Telefone Contacto', 'Observações', 'Estado'], ';');
    foreach ($resultados as $row) {
        fputcsv($output, $row, ';');
    }
    fclose($output);
    registar_log('EXPORTAR', "Fornecedores CSV exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}

// JSON
if ($formato === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="fornecedores_' . date('Y-m-d') . '.json"');
    echo json_encode($resultados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    registar_log('EXPORTAR', "Fornecedores JSON exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}

// PDF (HTML + window.print())
registar_log('EXPORTAR', "Fornecedores PDF exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fornecedores — <?= date('d/m/Y') ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #003b66; }
        p.data { font-size: 11px; color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #003b66; color: white; padding: 6px 8px; text-align: left; font-size: 11px; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        tr:nth-child(even) { background: #f5f5f5; }
        .inativo { color: #999; }
        @media print {
            body { margin: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <h1>Lista de Fornecedores</h1>
    <p class="data">Exportado em <?= date('d/m/Y H:i') ?> por <?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>

    <table>
        <thead>
            <tr>
                <th>Nome Empresa</th>
                <th>NIF</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Morada</th>
                <th>Pessoa de Contacto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $row) : ?>
                <tr class="<?= $row['estado'] === 'Inativo' ? 'inativo' : '' ?>">
                    <td><?= htmlspecialchars($row['nome_empresa']) ?></td>
                    <td><?= htmlspecialchars($row['nif'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['telefone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['morada'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['pessoa_contacto'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['estado']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>