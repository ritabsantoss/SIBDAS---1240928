<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Só o administrador pode exportar dados
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}
// Validar o formato pedido — apenas csv, json e pdf são permitidos
$formato = $_GET['formato'] ?? '';
if (!in_array($formato, ['csv', 'json', 'pdf'])) {
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}
// Carregar os dados da BD para exportação
try {
    $ligacao = liga_bd();
    $resultados = $ligacao->query(
        "SELECT nome, email,
                CASE perfil
                    WHEN 'administrador' THEN 'Administrador'
                    WHEN 'tecnico' THEN 'Técnico'
                    WHEN 'profissional' THEN 'Profissional'
                END AS perfil,
                CASE genero
                    WHEN 'M' THEN 'Masculino'
                    WHEN 'F' THEN 'Feminino'
                END AS genero,
                last_login,
                IF(ativo = 1, 'Ativo', 'Inativo') AS estado
         FROM Utilizadores
         ORDER BY perfil, nome"
    )->fetchAll(PDO::FETCH_ASSOC);
    $ligacao = null;
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Utilizadores/exportar: " . $err->getMessage());
    header('Location: ' . BASE_URL . '/private/views/utilizadores/lista.php');
    exit;
}
// Exportar em CSV — formato compatível com Excel, com  UTF-8 para acentos
if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="utilizadores_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    fputcsv($output, ['Nome', 'Email', 'Perfil', 'Género', 'Último Login', 'Estado'], ';');
    foreach ($resultados as $row) {
        fputcsv($output, $row, ';');
    }
    fclose($output);
    registar_log('EXPORTAR', "Utilizadores CSV exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}

// Exportar em JSON — formato legível e estruturado
if ($formato === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="utilizadores_' . date('Y-m-d') . '.json"');
    echo json_encode($resultados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    registar_log('EXPORTAR', "Utilizadores JSON exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}
// Exportar em PDF — gera HTML formatado e abre o diálogo de impressão do browse (HTML + window.print())
registar_log('EXPORTAR', "Utilizadores PDF exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Utilizadores — <?= date('d/m/Y') ?></title>
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
    <h1>Lista de Utilizadores</h1>
    <p class="data">Exportado em <?= date('d/m/Y H:i') ?> por <?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Género</th>
                <th>Último Login</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $row) : ?>
                <tr class="<?= $row['estado'] === 'Inativo' ? 'inativo' : '' ?>">
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['perfil']) ?></td>
                    <td><?= htmlspecialchars($row['genero'] ?? '—') ?></td>
                    <td><?= $row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : '—' ?></td>
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