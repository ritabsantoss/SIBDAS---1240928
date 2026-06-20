<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'equipamentos';

// opções fixas (ENUM da BD)
$opcoes_estado      = ['Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido'];
$opcoes_criticidade = ['Baixa', 'Média', 'Alta', 'Suporte de vida'];

// listas que vêm da BD (preenchidas mais abaixo)
$lista_categorias = [];
$lista_servicos   = [];

// valores dos filtros (para a query e para "lembrar" as escolhas no modal)
$f_codigo      = trim($_GET['codigo'] ?? '');
$f_designacao  = trim($_GET['designacao'] ?? '');
$f_marca       = trim($_GET['marca'] ?? '');
$f_categoria   = $_GET['categoria'] ?? 'Todas';
$f_servico     = $_GET['servico'] ?? 'Todos';
$f_estado      = $_GET['estado'] ?? 'Todos';
$f_criticidade = $_GET['criticidade'] ?? 'Todas';

try {
    $ligacao = liga_bd();

    // dropdowns dinâmicos
    $lista_categorias = $ligacao->query("SELECT nome FROM Categorias ORDER BY nome")->fetchAll(PDO::FETCH_COLUMN);
    $lista_servicos   = $ligacao->query("SELECT nome FROM Servicos ORDER BY nome")->fetchAll(PDO::FETCH_COLUMN);

    // --- Filtros avançados (lidos do modal) ---
    $where  = [];
    $params = [];

    if ($f_codigo !== '') {
        $where[] = "e.codigo_interno LIKE :codigo";
        $params[':codigo']     = "%$f_codigo%";
    }
    if ($f_designacao !== '') {
        $where[] = "e.designacao LIKE :designacao";
        $params[':designacao'] = "%$f_designacao%";
    }
    if ($f_marca !== '') {
        $where[] = "e.marca LIKE :marca";
        $params[':marca']      = "%$f_marca%";
    }
    if ($f_categoria !== 'Todas') {
        $where[] = "c.nome = :categoria";
        $params[':categoria']   = $f_categoria;
    }
    if ($f_servico !== 'Todos') {
        $where[] = "s.nome = :servico";
        $params[':servico']     = $f_servico;
    }
    if ($f_estado !== 'Todos') {
        $where[] = "e.estado_atual = :estado";
        $params[':estado']      = $f_estado;
    }
    if ($f_criticidade !== 'Todas') {
        $where[] = "e.criticidade = :criticidade";
        $params[':criticidade'] = $f_criticidade;
    }

    $sql = "SELECT e.idEquipamento, e.codigo_interno, e.designacao, e.marca,
                   e.modelo, e.numero_serie, c.nome AS categoria,
                   e.estado_atual, e.criticidade,
                   s.nome AS servico, l.sala
            FROM Equipamentos e
            JOIN Localizacoes l ON e.idLocalizacao = l.idLocalizacao
            JOIN Servicos s     ON l.idServico    = s.idServico
            JOIN Categorias c   ON e.idCategoria  = c.idCategoria";

    if (count($where) > 0) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY e.codigo_interno";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);

    // mapa de cores para os badges
    $cores = [
        'Ativo' => 'success',
        'Em manutenção' => 'warning text-dark',
        'Em calibração' => 'warning text-dark',
        'Em quarentena' => 'danger',
        'Inativo' => 'secondary',
        'Abatido' => 'secondary'
    ];
    $cores_crit = [
        'Baixa' => 'secondary',
        'Média' => 'info text-dark',
        'Alta' => 'warning text-dark',
        'Suporte de vida' => 'danger'
    ];
    $erro = '';
} catch (PDOException $err) {
    $erro = "Aconteceu um erro na ligação à base de dados.";
    $resultados = [];
}
$ligacao = null;

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>
<div class="private-container">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fa-solid fa-laptop-medical"></i>
                    Lista de Equipamentos
                </h2>
                <p class="text-muted mb-0">
                    Gestão e consulta de equipamentos hospitalares.
                </p>
            </div>

            <a href="novo.php" class="btn btn-pink">
                <i class="fa-solid fa-plus me-2"></i>Novo Equipamento
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-body">

                <div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label">Pesquisa rápida</label>
                            <input type="text" class="form-control" id="pesquisa" name="pesquisa">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#modalFiltros">
                                <i class="fa-solid fa-filter me-1"></i>
                                Filtros
                            </button>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="btnPesquisar" class="btn btn-pink w-100">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>
                                Pesquisar
                            </button>
                        </div>

                    </div>

                    <div class="row mb-4">

                        <div class="col-md-3">
                            <label class="form-label">Ordenar por</label>

                            <select class="form-select" id="ordenar" name="ordenar">
                                <option value="0" selected>Código</option>
                                <option value="1">Equipamento</option>
                                <option value="2">Marca</option>
                                <option value="3">Categoria</option>
                                <option value="4">Localização</option>
                                <option value="5">Estado</option>
                                <option value="6">Criticidade</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Sentido</label>
                            <select class="form-select" id="sentido" name="sentido">
                                <option value="asc" selected>Ascendente</option>
                                <option value="desc">Descendente</option>
                            </select>
                        </div>

                    </div>

                </div>

                <!-- Modal de Filtros Avançados -->
                <div class="modal fade" id="modalFiltros" tabindex="-1">

                    <div class="modal-dialog modal-lg modal-dialog-centered">

                        <div class="modal-content border-0 rounded-4">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fa-solid fa-filter me-2"></i>
                                    Filtros Avançados
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form method="get" action="lista.php">

                                <div class="modal-body">

                                    <p class="text-muted mb-4">
                                        Pode preencher um ou vários critérios. Os resultados deverão cumprir todos
                                        os filtros escolhidos.
                                    </p>

                                    <div class="row mb-3">

                                        <div class="col-md-4">
                                            <label class="form-label">Código interno</label>
                                            <input type="text" class="form-control" name="codigo"
                                                value="<?= htmlspecialchars($f_codigo) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Designação</label>
                                            <input type="text" class="form-control" name="designacao"
                                                value="<?= htmlspecialchars($f_designacao) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Marca</label>
                                            <input type="text" class="form-control" name="marca"
                                                value="<?= htmlspecialchars($f_marca) ?>">
                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-6">
                                            <label class="form-label">Categoria</label>
                                            <select class="form-select" name="categoria">
                                                <option <?= $f_categoria === 'Todas' ? 'selected' : '' ?>>Todas</option>
                                                <?php foreach ($lista_categorias as $cat) : ?>
                                                    <option <?= $f_categoria === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Serviço</label>
                                            <select class="form-select" name="servico">
                                                <option <?= $f_servico === 'Todos' ? 'selected' : '' ?>>Todos</option>
                                                <?php foreach ($lista_servicos as $serv) : ?>
                                                    <option <?= $f_servico === $serv ? 'selected' : '' ?>><?= htmlspecialchars($serv) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-6">
                                            <label class="form-label">Estado</label>
                                            <select class="form-select" name="estado">
                                                <option <?= $f_estado === 'Todos' ? 'selected' : '' ?>>Todos</option>
                                                <?php foreach ($opcoes_estado as $est) : ?>
                                                    <option <?= $f_estado === $est ? 'selected' : '' ?>><?= $est ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Criticidade</label>
                                            <select class="form-select" name="criticidade">
                                                <option <?= $f_criticidade === 'Todas' ? 'selected' : '' ?>>Todas</option>
                                                <?php foreach ($opcoes_criticidade as $crit) : ?>
                                                    <option <?= $f_criticidade === $crit ? 'selected' : '' ?>><?= $crit ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                    </div>

                                </div>

                                <div class="modal-footer">

                                    <a href="lista.php" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-rotate-left me-1"></i>
                                        Limpar filtros
                                    </a>

                                    <button type="submit" class="btn btn-pink">
                                        <i class="fa-solid fa-check me-1"></i>
                                        Aplicar filtros
                                    </button>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <hr>

                <?php if (!empty($erro)) : ?>
                    <div class="alert alert-danger text-center"><?= $erro ?></div>
                <?php else : ?>

                    <div class="table-responsive">

                        <table id="tabela-equipamentos" class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Equipamento</th>
                                    <th>Marca</th>
                                    <th>Categoria</th>
                                    <th>Localização</th>
                                    <th>Estado</th>
                                    <th>Criticidade</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($resultados as $eq) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($eq->codigo_interno) ?></td>
                                        <td><?= htmlspecialchars($eq->designacao) ?></td>
                                        <td><?= htmlspecialchars($eq->marca ?? '') ?></td>
                                        <td><?= htmlspecialchars($eq->categoria) ?></td>
                                        <td><?= htmlspecialchars($eq->servico) ?><?= $eq->sala ? ' — ' . htmlspecialchars($eq->sala) : '' ?></td>
                                        <td>
                                            <span class="badge bg-<?= $cores[$eq->estado_atual] ?? 'secondary' ?>">
                                                <?= htmlspecialchars($eq->estado_atual) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $cores_crit[$eq->criticidade] ?? 'secondary' ?>">
                                                <?= htmlspecialchars($eq->criticidade) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <a href="detalhes.php?id_equipamento=<?= aes_encrypt($eq->idEquipamento) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-circle-info"></i></a>
                                                <a href="editar.php?id_equipamento=<?= aes_encrypt($eq->idEquipamento) ?>" class="btn btn-sm btn-outline-warning"><i class="fa-regular fa-pen-to-square"></i></a>
                                                <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal" data-bs-target="#modalArquivar" data-nome="<?= htmlspecialchars($eq->designacao) ?>"><i class="fa-solid fa-box-archive"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="col">
                            <p class="mb-3">Total: <strong><?= count($resultados) ?></strong></p>
                        </div>

                    </div>

                <?php endif; ?>
            </div>
        </div>


        <div class="modal fade" id="modalArquivar" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 rounded-4">

                    <div class="modal-body text-center p-5">

                        <div class="text-warning mb-4">
                            <i class="fa-solid fa-triangle-exclamation fa-4x"></i>
                        </div>

                        <h4 class="mb-3">Gestão do Equipamento</h4>

                        <p class="text-muted mb-2">
                            Equipamento selecionado:
                        </p>

                        <h5 id="itemSelecionado" class="mb-4 text-primary">
                            —
                        </h5>

                        <p class="text-muted mb-4">
                            Pretende arquivar ou eliminar este equipamento?
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">

                            <button class="btn btn-warning px-4">
                                <i class="fa-solid fa-box-archive me-2"></i>
                                Arquivar
                            </button>

                            <button class="btn btn-danger px-4">
                                <i class="fa-solid fa-trash-can me-2"></i>
                                Eliminar
                            </button>

                            <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
<?php if (empty($erro)) : ?>
    <script>
        $(document).ready(function() {
            const tabela = $('#tabela-equipamentos').DataTable({
                pageLength: 5,
                pagingType: "full_numbers",
                dom: 'rtip', // esconde o "Filtrar:" e o "Mostrar X registos" do DataTables
                order: [
                    [0, 'asc']
                ],
                language: {
                    info: "Mostrando _START_ até _END_ de _TOTAL_ registos",
                    infoEmpty: "Mostrando 0 até 0 de 0 registos",
                    infoFiltered: "(Filtrando _MAX_ total de registos)",
                    zeroRecords: "Nenhum registo encontrado.",
                    emptyTable: "Não existem equipamentos registados.",
                    paginate: {
                        first: "Primeira",
                        last: "Última",
                        next: "Seguinte",
                        previous: "Anterior"
                    }
                }
            });

            // "Pesquisa rápida" -> pesquisa ao escrever
            $('#pesquisa').on('keyup', function() {
                tabela.search(this.value).draw();
            });

            // "Pesquisar" -> aplica a pesquisa
            $('#btnPesquisar').on('click', function() {
                tabela.search($('#pesquisa').val()).draw();
            });

            // "Ordenar por" + "Sentido" -> ordenacao
            function aplicarOrdenacao() {
                tabela.order([parseInt($('#ordenar').val(), 10), $('#sentido').val()]).draw();
            }
            $('#ordenar, #sentido').on('change', aplicarOrdenacao);
        });
    </script>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>