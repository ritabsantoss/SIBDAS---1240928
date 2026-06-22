<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

$pagina_ativa = 'utilizadores';

try {
    $ligacao = liga_bd();

    $resultados = $ligacao->query(
        "SELECT idUtilizador, nome, email, perfil, genero, last_login, ativo
         FROM Utilizadores
         ORDER BY ativo DESC, perfil, nome"
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
                    <i class="fa-solid fa-users me-2"></i>
                    Lista de Utilizadores
                </h2>
                <p class="text-muted mb-0">
                    Gestão dos utilizadores do sistema.
                </p>
            </div>

            <a href="novo.php" class="btn btn-pink">
                <i class="fa-solid fa-plus me-2"></i>Novo Utilizador
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
                                <option value="1">Email</option>
                                <option value="2">Perfil</option>
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

                        <table id="tabela-utilizadores" class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th>Último login</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($resultados as $u) : ?>
                                    <tr <?= $u->ativo == 0 ? 'class="linha-inativa"' : '' ?>>
                                        <td><?= htmlspecialchars($u->nome) ?></td>
                                        <td><?= htmlspecialchars($u->email) ?></td>
                                        <td>
                                            <span class="badge <?= $u->perfil === 'administrador' ? 'badge-sihem' : 'badge-sihem-pink' ?>">
                                                <?= htmlspecialchars(ucfirst($u->perfil)) ?>
                                            </span>
                                        </td>
                                        <td><?= $u->last_login ? date('d/m/Y H:i', strtotime($u->last_login)) : '—' ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                                <?php if ($u->ativo == 1) : ?>
                                                    <a href="editar.php?id_utilizador=<?= aes_encrypt($u->idUtilizador) ?>" class="btn btn-sm btn-outline-warning">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                    <a href="editar_password.php?id_utilizador=<?= aes_encrypt($u->idUtilizador) ?>" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa-solid fa-key"></i>
                                                    </a>
                                                    <?php if ($u->email !== $_SESSION['email']) : ?>
                                                        <button class="btn btn-sm btn-outline-danger btn-gestao"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminar"
                                                            data-nome="<?= htmlspecialchars($u->nome) ?>"
                                                            data-href="confirmar_apagar.php?id_utilizador=<?= aes_encrypt($u->idUtilizador) ?>">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <?php if ($u->email !== $_SESSION['email']) : ?>
                                                        <a href="reativar.php?id_utilizador=<?= aes_encrypt($u->idUtilizador) ?>" class="btn btn-sm btn-outline-success">
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

                        <i class="fa-solid fa-triangle-exclamation fa-3x mb-3" style="color: var(--rosa-principal);"></i>

                        <h5 class="mb-2" style="color: var(--azul-principal);">Desativar utilizador?</h5>

                        <p class="text-muted mb-1">Utilizador selecionado:</p>
                        <p id="itemSelecionado" class="fw-bold mb-4" style="color: var(--azul-principal);">—</p>

                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark me-1"></i>Cancelar
                            </button>
                            <a id="linkConfirmar" href="#" class="btn btn-danger px-4">
                                <i class="fa-solid fa-trash-can me-1"></i>Desativar
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
            const tabela = $('#tabela-utilizadores').DataTable({
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
                    emptyTable: "Não existem utilizadores registados.",
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