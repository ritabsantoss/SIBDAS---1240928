<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'localizacoes';
$lista_servicos = [];
$lista_edificios = [];

try {
    $ligacao = liga_bd();

    $filtro_ativo = $_SESSION['perfil'] === 'administrador' ? "" : "WHERE l.ativo = 1";
    $resultados = $ligacao->query(
        "SELECT l.idLocalizacao, l.edificio, l.piso, s.nome AS servico, l.sala, l.ativo
     FROM Localizacoes l
     JOIN Servicos s ON l.idServico = s.idServico
     $filtro_ativo
     ORDER BY l.ativo DESC, l.edificio"
    )->fetchAll(PDO::FETCH_OBJ);

    $lista_servicos  = $ligacao->query("SELECT nome FROM Servicos ORDER BY nome")->fetchAll(PDO::FETCH_COLUMN);
    $lista_edificios = $ligacao->query("SELECT DISTINCT edificio FROM Localizacoes WHERE edificio IS NOT NULL ORDER BY edificio")->fetchAll(PDO::FETCH_COLUMN);

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
        <?php if (!empty($_SESSION['mensagem'])) : ?>
            <div class="alert alert-<?= $_SESSION['mensagem_tipo'] ?? 'success' ?> alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['mensagem']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">
                    <i class="fa-solid fa-location-dot me-2"></i>
                    Lista de Localizações
                </h2>
                <p class="text-muted mb-0">
                    Gestão das localizações físicas dos equipamentos hospitalares.
                </p>
            </div>

            <div class="d-flex gap-2">
        <?php if ($_SESSION['perfil'] !== 'profissional') : ?>
                <a href="novo.php" class="btn btn-pink">
                    <i class="fa-solid fa-plus me-2"></i>Nova Localização
                </a>
            <?php endif; ?>
        <?php if ($_SESSION['perfil'] === 'administrador') : ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-download me-1"></i>Exportar
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="exportar.php?formato=csv">
                            <i class="fa-solid fa-file-csv me-2"></i>CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="exportar.php?formato=json">
                            <i class="fa-solid fa-file-code me-2"></i>JSON
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="exportar.php?formato=pdf" target="_blank">
                            <i class="fa-solid fa-file-pdf me-2"></i>PDF
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

            

        

        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-body">

                <div>

                    <div class="row mb-4">

                        <div class="col-md-4">
                            <label class="form-label">Pesquisa rápida</label>
                            <input type="text" class="form-control" id="pesquisa" name="pesquisa">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Edifício</label>
                            <select class="form-select" id="filtroEdificio" name="filtro_edificio">
                                <option value="">Todos</option>
                                <?php foreach ($lista_edificios as $ed) : ?>
                                    <option><?= htmlspecialchars($ed) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Serviço | Departamento</label>
                            <select class="form-select" id="filtroServico" name="tipo">
                                <option value="">Todos</option>
                                <?php foreach ($lista_servicos as $serv) : ?>
                                    <option><?= htmlspecialchars($serv) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
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
                                <option value="0" selected>Edifício</option>
                                <option value="1">Piso</option>
                                <option value="2">Serviço | Departamento</option>
                                <option value="3">Sala | Gabinete</option>
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

                <hr>

                <?php if (!empty($erro)) : ?>
                    <div class="alert alert-danger text-center"><?= $erro ?></div>
                <?php else : ?>

                    <div class="table-responsive">

                        <table id="tabela-localizacoes" class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Edifício</th>
                                    <th>Piso</th>
                                    <th>Serviço | Departamento</th>
                                    <th>Sala | Gabinete</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($resultados as $loc) : ?>
                                    <tr <?= $loc->ativo == 0 ? 'class="linha-inativa"' : '' ?>>
                                        <td><?= htmlspecialchars($loc->edificio ?? '') ?></td>
                                        <td><?= htmlspecialchars($loc->piso ?? '') ?></td>
                                        <td><?= htmlspecialchars($loc->servico) ?></td>
                                        <td><?= htmlspecialchars($loc->sala ?? '') ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <a href="detalhes.php?id_localizacao=<?= aes_encrypt($loc->idLocalizacao) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </a>
                                                <?php if ($loc->ativo == 1) : ?>
                                                    <?php if ($_SESSION['perfil'] !== 'profissional') : ?>
                                                        <a href="editar.php?id_localizacao=<?= aes_encrypt($loc->idLocalizacao) ?>" class="btn btn-sm btn-outline-warning">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($_SESSION['perfil'] === 'administrador') : ?>
                                                        <button class="btn btn-sm btn-outline-danger btn-gestao"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminar"
                                                            data-nome="<?= htmlspecialchars($loc->servico) ?>"
                                                            data-href="confirmar_apagar.php?id_localizacao=<?= aes_encrypt($loc->idLocalizacao) ?>">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <?php if ($_SESSION['perfil'] === 'administrador') : ?>
                                                        <a href="reativar.php?id_localizacao=<?= aes_encrypt($loc->idLocalizacao) ?>" class="btn btn-sm btn-outline-success">
                                                            <i class="fa-solid fa-rotate-left me-1"></i>Reativar
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
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

        <div class="modal fade" id="modalEliminar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-body text-center p-5">

                        <i class="fa-solid fa-triangle-exclamation fa-3x mb-3"
                            style="color: var(--rosa-principal);"></i>

                        <h5 class="mb-2" style="color: var(--azul-principal);">
                            Desativar localização?
                        </h5>

                        <p class="text-muted mb-1">Localização selecionada:</p>
                        <p id="itemSelecionado" class="fw-bold mb-4"
                            style="color: var(--azul-principal);">—</p>

                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark me-1"></i>Cancelar
                            </button>
                            <a id="linkConfirmar" href="#" class="btn btn-danger px-4">
                                <i class="fa-solid fa-trash-can me-1"></i>Eliminar
                            </a>
                        </div>

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
            const tabela = $('#tabela-localizacoes').DataTable({
                pageLength: 5,
                pagingType: "full_numbers",
                dom: 'rtip',
                order: [
                    [0, 'asc']
                ],
                language: {
                    info: "Mostrando _START_ até _END_ de _TOTAL_ registos",
                    infoEmpty: "Mostrando 0 até 0 de 0 registos",
                    infoFiltered: "(Filtrando _MAX_ total de registos)",
                    zeroRecords: "Nenhum registo encontrado.",
                    emptyTable: "Não existem localizações registadas.",
                    paginate: {
                        first: "Primeira",
                        last: "Última",
                        next: "Seguinte",
                        previous: "Anterior"
                    }
                }
            });

            $('#pesquisa').on('keyup', function() {
                tabela.search(this.value).draw();
            });
            $('#btnPesquisar').on('click', function() {
                tabela.search($('#pesquisa').val()).draw();
            });

            function aplicarOrdenacao() {
                tabela.order([parseInt($('#ordenar').val(), 10), $('#sentido').val()]).draw();
            }
            $('#ordenar, #sentido').on('change', aplicarOrdenacao);

            // dropdown "Serviço" -> filtra a coluna do Serviço (coluna 2)
            $('#filtroServico').on('change', function() {
                tabela.column(2).search(this.value).draw();
            });
            $('#filtroEdificio').on('change', function() {
                tabela.column(0).search(this.value).draw();
            });
        });
    </script>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>