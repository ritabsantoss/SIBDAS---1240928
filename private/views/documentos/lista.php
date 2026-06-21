<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'documentos';

try {
    $ligacao = liga_bd();

    $filtro_ativo = $_SESSION['perfil'] === 'administrador' ? "" : "AND d.ativo = 1";
    $resultados = $ligacao->query(
        "SELECT d.idDocumento, d.codigo_documento, d.nome_documento, d.tipo_documento,
            d.validade, d.ativo, e.designacao AS equipamento, f.nome_empresa AS fornecedor
     FROM Documentos d
     JOIN Equipamentos e      ON d.idEquipamento = e.idEquipamento AND e.ativo = 1
     LEFT JOIN Fornecedores f ON d.idFornecedor  = f.idFornecedor
     $filtro_ativo
     ORDER BY d.ativo DESC, d.codigo_documento"
    )->fetchAll(PDO::FETCH_OBJ);

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
                    <i class="fa-solid fa-file-pdf"></i>
                    Lista de Documentação
                </h2>
                <p class="text-muted mb-0">
                    Gestão e consulta de documentação técnica hospitalar.
                </p>

            </div>

        </div>

        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-body">

                <div>

                    <div class="row mb-4">

                        <div class="col-md-5">
                            <label class="form-label">Pesquisa rápida</label>
                            <input type="text" class="form-control" id="pesquisa" name="pesquisa">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de Documento</label>
                            <select class="form-select" id="filtroTipo" name="tipo">
                                <option value="">Todos</option>
                                <option>Manual de Utilizador</option>
                                <option>Manual de Serviço</option>
                                <option>Certificado de Calibração</option>
                                <option>Fatura ou Guia de Aquisição</option>
                                <option>Declaração de Conformidade</option>
                                <option>Relatório Técnico</option>
                            </select>
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
                                <option value="1">Documento</option>
                                <option value="2">Tipo</option>
                                <option value="3">Equipamento</option>
                                <option value="4">Fornecedor</option>
                                <option value="5">Validade</option>
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

                        <table id="tabela-documentos" class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Documento</th>
                                    <th>Tipo</th>
                                    <th>Equipamento</th>
                                    <th>Fornecedor</th>
                                    <th>Validade</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($resultados as $doc) : ?>
                                    <tr <?= $doc->ativo == 0 ? 'class="linha-inativa"' : '' ?>>
                                        <td><?= htmlspecialchars($doc->codigo_documento) ?></td>
                                        <td><?= htmlspecialchars($doc->nome_documento) ?></td>
                                        <td><?= htmlspecialchars($doc->tipo_documento) ?></td>
                                        <td><?= htmlspecialchars($doc->equipamento) ?></td>
                                        <td><?= $doc->fornecedor ? htmlspecialchars($doc->fornecedor) : '—' ?></td>
                                        <td><?= $doc->validade ? htmlspecialchars($doc->validade) : '—' ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <a href="detalhes.php?id_documento=<?= aes_encrypt($doc->idDocumento) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </a>
                                                <?php if ($doc->ativo == 1) : ?>
                                                    <?php if ($_SESSION['perfil'] === 'administrador') : ?>
                                                        <button class="btn btn-sm btn-outline-danger btn-gestao"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminar"
                                                            data-nome="<?= htmlspecialchars($doc->nome_documento) ?>"
                                                            data-href="confirmar_apagar.php?id_documento=<?= aes_encrypt($doc->idDocumento) ?>">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <?php if ($_SESSION['perfil'] === 'administrador') : ?>
                                                        <a href="reativar.php?id_documento=<?= aes_encrypt($doc->idDocumento) ?>" class="btn btn-sm btn-outline-success">
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
                            Desativar documento?
                        </h5>

                        <p class="text-muted mb-1">Documento selecionado:</p>
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
    </main>
</div>

<?php if (empty($erro)) : ?>
    <script>
        $(document).ready(function() {
            const tabela = $('#tabela-documentos').DataTable({
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
                    emptyTable: "Não existem documentos registados.",
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

            // dropdown "Tipo" -> filtra a coluna do Tipo (coluna 2)
            $('#filtroTipo').on('change', function() {
                tabela.column(2).search(this.value).draw();
            });
        });
    </script>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>