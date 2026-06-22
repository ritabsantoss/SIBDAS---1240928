<?php
require_once __DIR__ . '/../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] === 'profissional') {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}

$pagina_ativa = 'dashboard';
$erro_sistema = '';
$total               = 0;
$ativos              = 0;
$manutencao          = 0;
$inativos_est        = 0;
$garantias_expiradas = 0;
$sem_documentacao    = 0;
$garantias_30dias    = 0;
$criticidade_elevada = 0;
$estados_raw         = [];
$servicos_raw        = [];
$criticidade_raw     = [];
$categorias_raw      = [];
$alertas_raw         = [];

// filtro de ativos consoante o perfil
$filtro             = $_SESSION['perfil'] === 'administrador' ? "" : "AND e.ativo = 1";
$filtro_simples     = $_SESSION['perfil'] === 'administrador' ? "" : "WHERE Equipamentos.ativo = 1";
$filtro_and_simples = $_SESSION['perfil'] === 'administrador' ? "" : "AND Equipamentos.ativo = 1";

    try {
    $ligacao = liga_bd();

    $total               = $ligacao->query("SELECT COUNT(*) FROM Equipamentos $filtro_simples")->fetchColumn() ?: 0;
    $ativos              = $ligacao->query("SELECT COUNT(*) FROM Equipamentos WHERE estado_atual = 'Ativo' $filtro_and_simples")->fetchColumn() ?: 0;
    $manutencao          = $ligacao->query("SELECT COUNT(*) FROM Equipamentos WHERE estado_atual = 'Em manutenção' $filtro_and_simples")->fetchColumn() ?: 0;
    $inativos_est        = $ligacao->query("SELECT COUNT(*) FROM Equipamentos WHERE estado_atual = 'Inativo' $filtro_and_simples")->fetchColumn() ?: 0;
    $garantias_expiradas = $ligacao->query("SELECT COUNT(*) FROM Garantias g JOIN Equipamentos e ON g.idEquipamento = e.idEquipamento WHERE g.estado_garantia = 'Expirada' $filtro")->fetchColumn() ?: 0;
    $sem_documentacao    = $ligacao->query("SELECT COUNT(*) FROM Equipamentos e LEFT JOIN Documentos d ON e.idEquipamento = d.idEquipamento AND d.ativo = 1 WHERE d.idEquipamento IS NULL $filtro")->fetchColumn() ?: 0;
    $garantias_30dias    = $ligacao->query("SELECT COUNT(*) FROM Garantias g JOIN Equipamentos e ON g.idEquipamento = e.idEquipamento WHERE g.data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) $filtro")->fetchColumn() ?: 0;
    $criticidade_elevada = $ligacao->query("SELECT COUNT(*) FROM Equipamentos WHERE criticidade IN ('Alta', 'Suporte de vida') $filtro_and_simples")->fetchColumn() ?: 0;
    $estados_raw         = $ligacao->query("SELECT estado_atual, COUNT(*) as total FROM Equipamentos $filtro_simples GROUP BY estado_atual")->fetchAll(PDO::FETCH_OBJ);
    $alertas_raw         = $ligacao->query("SELECT e.designacao, e.criticidade, e.estado_atual, g.estado_garantia, g.data_fim FROM Equipamentos e LEFT JOIN Garantias g ON e.idEquipamento = g.idEquipamento WHERE (g.estado_garantia IN ('Expirada', 'Prestes a Expirar') OR e.criticidade IN ('Alta', 'Suporte de vida') OR e.estado_atual = 'Em manutenção') $filtro ORDER BY e.criticidade DESC LIMIT 10")->fetchAll(PDO::FETCH_OBJ);

    // só admin
    if ($_SESSION['perfil'] === 'administrador') {
        $servicos_raw    = $ligacao->query("SELECT s.nome, COUNT(e.idEquipamento) as total, SUM(CASE WHEN e.criticidade = 'Suporte de vida' THEN 1 ELSE 0 END) as suporte_vida FROM Servicos s LEFT JOIN Localizacoes l ON s.idServico = l.idServico LEFT JOIN Equipamentos e ON l.idLocalizacao = e.idLocalizacao AND e.ativo = 1 GROUP BY s.idServico, s.nome ORDER BY total DESC")->fetchAll(PDO::FETCH_OBJ);
        $criticidade_raw = $ligacao->query("SELECT criticidade, COUNT(*) as total FROM Equipamentos WHERE ativo = 1 GROUP BY criticidade")->fetchAll(PDO::FETCH_OBJ);
        $categorias_raw  = $ligacao->query("SELECT c.nome, COUNT(e.idEquipamento) as total FROM Categorias c LEFT JOIN Equipamentos e ON c.idCategoria = e.idCategoria AND e.ativo = 1 GROUP BY c.idCategoria, c.nome ORDER BY total DESC")->fetchAll(PDO::FETCH_OBJ);
    }

    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro: " . $err->getMessage();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

    <?php if (!empty($erro_sistema)) : ?>
    <div class="alert alert-danger m-3"><?= $erro_sistema ?></div>
<?php endif; ?>

        <div class="page-header mb-4">
            <h2><i class="fa-solid fa-chart-line me-2"></i>Dashboard</h2>
            <p>Visão geral do parque tecnológico hospitalar.</p>
        </div>

        <!-- Cards principais -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-stethoscope"></i>
                    <h3><?= $total ?? 0 ?></h3>
                    <p>Total</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-circle-check text-success"></i>
                    <h3><?= $ativos ?? 0 ?></h3>
                    <p>Ativos</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-screwdriver-wrench text-warning"></i>
                    <h3><?= $manutencao ?? 0 ?></h3>
                    <p>Manutenção</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-circle-xmark text-secondary"></i>
                    <h3><?= $inativos_est ?? 0 ?></h3>
                    <p>Inativos</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                    <h3><?= $garantias_expiradas ?? 0 ?></h3>
                    <p>Garantias expiradas</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-file-circle-xmark text-danger"></i>
                    <h3><?= $sem_documentacao ?? 0 ?></h3>
                    <p>Sem documentação</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-calendar-days text-warning"></i>
                    <h3><?= $garantias_30dias ?? 0 ?></h3>
                    <p>Garantias a expirar</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fa-solid fa-heart-pulse text-danger"></i>
                    <h3><?= $criticidade_elevada ?? 0 ?></h3>
                    <p>Criticidade elevada</p>
                </div>
            </div>
        </div>

     <div class="row g-4 align-items-stretch">

    <!-- Coluna esquerda -->
    <div class="col-lg-6 d-flex flex-column gap-4">

        <div class="dashboard-box">
            <h5>Equipamentos por estado</h5>
            <canvas id="graficoEstado"></canvas>
        </div>

        <?php if ($_SESSION['perfil'] === 'administrador') : ?>
            <div class="dashboard-box flex-fill">
                <h5>Equipamentos por serviço</h5>
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            <th>N.º Equipamentos</th>
                            <th>Suporte de vida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos_raw as $s) : ?>
                            <tr>
                                <td><?= htmlspecialchars($s->nome) ?></td>
                                <td><span class="badge badge-sihem"><?= $s->total ?></span></td>
                                <td><span class="badge badge-sihem-pink"><?= $s->suporte_vida ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

    <!-- Coluna direita -->
    <div class="col-lg-6 d-flex flex-column gap-4">

        <?php if ($_SESSION['perfil'] === 'administrador') : ?>
            <div class="dashboard-box">
                <h5>Equipamentos por criticidade</h5>
                <canvas id="graficoCriticidade"></canvas>
            </div>
            <div class="dashboard-box">
                <h5>Distribuição por categoria</h5>
                <canvas id="graficoCategoria"></canvas>
            </div>
        <?php endif; ?>

        <div class="dashboard-box flex-fill">
            <h5>Alertas importantes</h5>
            <table id="tabelaAlertas" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Equipamento</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alertas_raw as $a) : ?>
                        <tr>
                            <td><?= htmlspecialchars($a->designacao) ?></td>
                            <td>
                                <?php
                                $situacoes = [];
                                if ($a->estado_garantia === 'Expirada') $situacoes[] = 'Garantia expirada';
                                if ($a->estado_garantia === 'Prestes a Expirar') $situacoes[] = 'Garantia a expirar';
                                if (in_array($a->criticidade, ['Alta', 'Suporte de vida'])) $situacoes[] = 'Criticidade ' . $a->criticidade;
                                if ($a->estado_atual === 'Em manutenção') $situacoes[] = 'Em manutenção';
                                echo htmlspecialchars(implode(', ', $situacoes));
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<?php if (!empty($alertas_raw)) : ?>
<script>
    $(document).ready(function() {
        $('#tabelaAlertas').DataTable({
            pageLength: 5,
            pagingType: "full_numbers",
            dom: 'rtip',
            language: {
                info: "Mostrando _START_ até _END_ de _TOTAL_ alertas",
                infoEmpty: "Sem alertas",
                zeroRecords: "Nenhum alerta encontrado.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Seguinte",
                    previous: "Anterior"
                }
            }
        });
    });
</script>
<?php endif; ?>


    </main>

</div>



<!-- Chart.js antes do footer  -->
<script src="<?php echo BASE_URL; ?>/assets/chartjs/chart.umd.min.js"></script>

<script>
    const dadosEstado = <?= json_encode(array_map(fn($r) => ['estado' => $r->estado_atual, 'total' => (int)$r->total], $estados_raw)) ?>;

    <?php if ($_SESSION['perfil'] === 'administrador') : ?>
        const dadosCriticidade = <?= json_encode(array_map(fn($r) => ['criticidade' => $r->criticidade, 'total' => (int)$r->total], $criticidade_raw)) ?>;
        const dadosCategorias = <?= json_encode(array_map(fn($r) => ['nome' => $r->nome, 'total' => (int)$r->total], $categorias_raw)) ?>;
    <?php endif; ?>
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>