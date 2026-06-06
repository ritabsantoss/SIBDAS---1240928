<?php $pagina_ativa = 'garantias'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

    <div class="private-container">

        <?php include '../../includes/sidebar.php'; ?>

        <!-- Conteúdo -->
        <main class="private-main">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h2 class="mb-1">
                        <i class="fa-solid fa-file-contract"></i>
                        Lista de Garantias e Contratos
                    </h2>
                    <p class="text-muted mb-0">
                        Gestão e consulta de garantias e contratos de manutenção.
                    </p>
                </div>

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
                                    <option>Estado</option>
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
                                    <th>Código</th>
                                    <th>Equipamento</th>
                                    <th>Tipo de Contrato</th>
                                    <th>Entidade Responsável</th>
                                    <th>Fim da Garantia</th>
                                    <th>Estado atual</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>GC-0091</td>
                                    <td>Ventilador Pulmonar</td>
                                    <td>Garantia do Fabricante</td>
                                    <td>Dräger</td>
                                    <td>2028-12-31</td>
                                    <td>
                                        <span class="badge bg-success">Ativa</span>
                                    </td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="GC-0091">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>GC-0122</td>
                                    <td>Bomba de Infusão</td>
                                    <td>Garantia do Fornecedor</td>
                                    <td>B. Braun</td>
                                    <td>2025-12-31</td>
                                    <td>
                                        <span class="badge bg-danger">Expirada</span>
                                    </td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="GC-0122">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>GC-0661</td>
                                    <td>Desfibrilhador</td>
                                    <td>Contrato de Assistência Técnica</td>
                                    <td>Zoll</td>
                                    <td>------------------</td>
                                    <td>
                                        <span class="badge bg-secondary">Não disponível</span>
                                    </td>

                                    <td class="text-center">
                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="GC-0661">
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

                            <h4 class="mb-3">Gestão de Registos</h4>

                            <p class="text-muted mb-2">
                                Equipamento selecionado:
                            </p>

                            <h5 id="itemSelecionado" class="mb-4 text-primary">
                                —
                            </h5>

                            <p class="text-muted mb-4">
                                Pretende arquivar ou eliminar este registo?
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

    <?php include '../../includes/footer.php'; ?>
    