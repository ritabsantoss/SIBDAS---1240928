<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'localizacoes';
$lista_servicos = [];
$lista_edificios = [];

try {
    $ligacao = liga_bd();

    $resultados = $ligacao->query(
        "SELECT l.idLocalizacao, l.edificio, l.piso, s.nome AS servico, l.sala
         FROM Localizacoes l
         JOIN Servicos s ON l.idServico = s.idServico
         ORDER BY l.edificio"
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

            <a href="novo.php" class="btn btn-pink">
                <i class="fa-solid fa-plus me-2"></i>Nova Localização
            </a>

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
                                    <tr>
                                        <td><?= htmlspecialchars($loc->edificio ?? '') ?></td>
                                        <td><?= htmlspecialchars($loc->piso ?? '') ?></td>
                                        <td><?= htmlspecialchars($loc->servico) ?></td>
                                        <td><?= htmlspecialchars($loc->sala ?? '') ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <a href="detalhes.php?id_localizacao=<?= aes_encrypt($loc->idLocalizacao) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-circle-info"></i></a>
                                                <a href="editar.php?id_localizacao=<?= aes_encrypt($loc->idLocalizacao) ?>" class="btn btn-sm btn-outline-warning"><i class="fa-regular fa-pen-to-square"></i></a>
                                                <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal" data-bs-target="#modalArquivar" data-nome="<?= htmlspecialchars($loc->sala) ?>"><i class="fa-solid fa-box-archive"></i></button>
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

                        <h4 class="mb-3">Gestão da Localização</h4>

                        <p class="text-muted mb-2">
                            Localização selecionada:
                        </p>

                        <h5 id="itemSelecionado" class="mb-4 text-primary">
                            —
                        </h5>

                        <p class="text-muted mb-4">
                            Pretende arquivar ou eliminar esta localização?
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