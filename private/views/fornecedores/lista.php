<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'fornecedores';

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

                    <form action="#" method="get">

                        <div class="row mb-4">

                            <div class="col-md-9">
                                <label class="form-label">Pesquisa rápida</label>
                                <input type="text" class="form-control" name="pesquisa">
                            </div>

                            

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-pink w-100">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                                    Pesquisar
                                </button>
                            </div>

                        </div>

                        <div class="row mb-4">

                            <div class="col-md-3">
                                <label class="form-label">Ordenar por</label>
                                <select class="form-select" name="ordenar">
                                    <option selected>Nome</option>
                                    <option>NIF</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sentido</label>
                                <select class="form-select" name="sentido">
                                    <option selected>Ascendente</option>
                                    <option>Descendente</option>
                                </select>
                            </div>

                        </div>

                    </form>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

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

                                <tr>
                                    <td>Philips Healthcare</td>
                                    <td>800 201 766</td>
                                    <td>healthcare.portugal@philips.com</td>
                                    <td>500216843</td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Philips Healthcare">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Dräger Portugal</td>
                                    <td>211 554 586</td>
                                    <td>clientesdraegerportugal@draeger.com</td>
                                    <td>508771323</td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Dräger Portugal">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>B. Braun Medical</td>
                                    <td>214 368 200</td>
                                    <td>info.bbmp@bbraun.com</td>
                                    <td>501506543</td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="B. Braun Medical">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>ZOLL Medical</td>
                                    <td>218 367 928</td>
                                    <td>emea@zoll.com</td>
                                    <td>501444555</td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="ZOLL Medical">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
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

    <?php include __DIR__ . '/../../includes/footer.php'; ?>