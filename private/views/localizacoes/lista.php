<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'localizacoes';

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

                    <form action="#" method="get">

                        <div class="row mb-4">

                            <div class="col-md-5">
                                <label class="form-label">Pesquisa rápida</label>
                                <input type="text" class="form-control" name="pesquisa">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Serviço | Departamento</label>
                                <select class="form-select" name="tipo">
                                    <option value="">Todos</option>
                                    <option>Urgência</option>
                                    <option>Unidade de Cuidados Intensivos</option>
                                    <option>Bloco Operatório</option>
                                    <option>Radiologia</option>
                                    <option>Laboratório</option>
                                    <option>Consulta Externa</option>
                                    <option>Internamento</option>
                                    <option>Pediatria</option>
                                </select>
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
                                    <option selected>Serviço | Departamento</option>
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
                                    <th>Edifício</th>
                                    <th>Piso</th>
                                    <th>Serviço | Departamento</th>
                                    <th>Sala | Gabinete</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Edifício Principal</td>
                                    <td>2º</td>
                                    <td>Unidade de Cuidados Intensivos</td>
                                    <td>Sala UCI 07</td>
                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Sala UCI 07">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>Edifício Principal</td>
                                    <td>5º</td>
                                    <td>Bloco Operatório</td>
                                    <td>Bloco 05</td>
                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Bloco 05">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>Edifício Clínico</td>
                                    <td>1º</td>
                                    <td>Serviço de Medicina</td>
                                    <td>Sala 03</td>
                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Sala 03">
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

    <?php include __DIR__ . '/../../includes/footer.php'; ?>