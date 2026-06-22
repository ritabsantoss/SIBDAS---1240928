<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
// Só o administrador pode exportar dados
if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}
// Validar o formato pedido — apenas csv, json e pdf são permitidos
$formato = $_GET['formato'] ?? '';
if (!in_array($formato, ['csv', 'json', 'pdf'])) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}
// Carregar os dados da BD para exportação
try {
    $ligacao = liga_bd();
    $resultados = $ligacao->query(
        "SELECT e.codigo_interno, e.designacao, c.nome AS categoria,
                e.marca, e.modelo, e.numero_serie,
                e.estado_atual, e.criticidade,
                s.nome AS servico, l.edificio, l.piso, l.sala,
                GROUP_CONCAT(f.nome_empresa SEPARATOR ', ') AS fornecedores,
                e.data_aquisicao, e.ano_fabrico, e.custo, e.tipo_entrada,
                IF(e.ativo = 1, 'Ativo', 'Inativo') AS estado_registo
        FROM Equipamentos e
        LEFT JOIN Categorias c ON e.idCategoria = c.idCategoria
        LEFT JOIN Localizacoes l ON e.idLocalizacao = l.idLocalizacao
        LEFT JOIN Servicos s ON l.idServico = s.idServico
        LEFT JOIN Equipamento_Fornecedor ef ON e.idEquipamento = ef.idEquipamento
        LEFT JOIN Fornecedores f ON ef.idFornecedor = f.idFornecedor
        GROUP BY e.idEquipamento
        ORDER BY e.codigo_interno"
    )->fetchAll(PDO::FETCH_ASSOC);
    $ligacao = null;
} catch (PDOException $err) {
    registar_log('ERRO_BD', "Equipamentos/exportar: " . $err->getMessage());
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}
// Exportar em CSV — formato compatível com Excel, com  UTF-8 para acentos
if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="equipamentos_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    fputcsv($output, ['Código', 'Designação', 'Categoria', 'Marca', 'Modelo', 'N.º Série', 'Estado', 'Criticidade', 'Fornecedores', 'Serviço', 'Edifício', 'Piso', 'Sala', 'Data Aquisição', 'Ano Fabrico', 'Custo', 'Tipo Entrada', 'Estado Registo'], ';');
    foreach ($resultados as $row) {
        fputcsv($output, $row, ';');
    }
    fclose($output);
    registar_log('EXPORTAR', "Equipamentos CSV exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}

// Exportar em JSON — formato legível e estruturado
if ($formato === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="equipamentos_' . date('Y-m-d') . '.json"');
    echo json_encode($resultados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    registar_log('EXPORTAR', "Equipamentos JSON exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
    exit;
}
// Exportar em PDF — gera HTML formatado e abre o diálogo de impressão do browse (HTML + window.print())
registar_log('EXPORTAR', "Equipamentos PDF exportado por " . ($_SESSION['email'] ?? 'desconhecido'));
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Equipamentos — <?= date('d/m/Y') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #003b66;
        }

        p.data {
            font-size: 11px;
            color: #666;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #003b66;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }

        .inativo {
            color: #999;
        }

        @media print {
            body {
                margin: 0;
            }

            button {
                display: none;
            }

            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
    </style>
</head>

<body>
    <h1>Lista de Equipamentos</h1>
    <p class="data">Exportado em <?= date('d/m/Y H:i') ?> por <?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Designação</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Criticidade</th>
                <th>Fornecedores</th>
                <th>Serviço</th>
                <th>Sala</th>
                <th>Estado Registo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $row) : ?>
                <tr class="<?= $row['estado_registo'] === 'Inativo' ? 'inativo' : '' ?>">
                    <td><?= htmlspecialchars($row['codigo_interno']) ?></td>
                    <td><?= htmlspecialchars($row['designacao']) ?></td>
                    <td><?= htmlspecialchars($row['categoria'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['marca'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['modelo'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['estado_atual'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['criticidade'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['fornecedores'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['servico'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['sala'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['estado_registo']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>