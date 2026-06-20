<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'fornecedores';

try {
    $ligacao = liga_bd();

    $resultados = $ligacao->query(
        "SELECT idFornecedor, nome_empresa, nif, telefone, email
         FROM Fornecedores
         ORDER BY nome_empresa"
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
                    <i class="fa-solid fa-truck me-2"></i>
                    Lista de Fornecedores
                </h2>
                <p class="text-muted mb-0">
                    Gestão e consulta dos fornecedores associados aos equipamentos.
                </p>
            </div>

            <a href="novo.php" class="btn btn-pink">
                <i class="fa-solid fa-plus me-2"></i>Novo Fornecedor
            </a>

        </div>

        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-body">

                <div>

                    <div class="row mb-4">

                        <div class="col-md-9">
                            <label class="form-label">Pesquisa rápida</label>
                            <input type="text" class="form-control" id="pesquisa" name="pesquisa">
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
                                <option value="0" selected>Nome</option>
                                <option value="1">Contacto</option>
                                <option value="2">Email</option>
                                <option value="3">NIF</option>
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

                        <table id="tabela-fornecedores" class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Contacto</th>
                                    <th>Email</th>
                                    <th>NIF</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($resultados as $f) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($f->nome_empresa) ?></td>
                                        <td><?= htmlspecialchars($f->telefone) ?></td>
                                        <td><?= htmlspecialchars($f->email) ?></td>
                                        <td><?= htmlspecialchars($f->nif) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <a href="detalhes.php?id_fornecedor=<?= aes_encrypt($f->idFornecedor) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-circle-info"></i></a>
                                                <a href="editar.php?id_fornecedor=<?= aes_encrypt($f->idFornecedor) ?>" class="btn btn-sm btn-outline-warning"><i class="fa-regular fa-pen-to-square"></i></a>
                                                <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal" data-bs-target="#modalArquivar" data-nome="<?= htmlspecialchars($f->nome_empresa) ?>"><i class="fa-solid fa-box-archive"></i></button>
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

                        <h4 class="mb-3">Gestão do Fornecedor</h4>

                        <p class="text-muted mb-2">
                            Fornecedor selecionado:
                        </p>

                        <h5 id="itemSelecionado" class="mb-4 text-primary">
                            —
                        </h5>

                        <p class="text-muted mb-4">
                            Pretende arquivar ou eliminar este fornecedor?
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
            const tabela = $('#tabela-fornecedores').DataTable({
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
                    emptyTable: "Não existem fornecedores registados.",
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
        });
    </script>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>