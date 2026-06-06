<?php $pagina_ativa = 'equipamentos'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

    <div class="private-container">

        <?php include '../../includes/sidebar.php'; ?>

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

                    <form action="#" method="get">

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Pesquisa rápida</label>
                                <input type="text" class="form-control" name="pesquisa">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#modalFiltros">
                                    <i class="fa-solid fa-filter me-1"></i>
                                    Filtros
                                </button>
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
                                    <option selected>Código interno</option>
                                    <option>Designação</option>
                                    <option>Marca</option>
                                    <option>Modelo</option>
                                    <option>Número de série</option>
                                    <option>Serviço</option>
                                    <option>Estado</option>
                                    <option>Fornecedor</option>
                                    <option>Categoria</option>
                                    <option>Criticidade</option>
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

                        <!-- Modal -->
                        <div class="modal fade" id="modalFiltros" tabindex="-1">

                            <div class="modal-dialog modal-xl modal-dialog-centered">

                                <div class="modal-content border-0 rounded-4">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fa-solid fa-filter me-2"></i>
                                            Filtros Avançados
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <p class="text-muted mb-4">
                                            Pode preencher um ou vários critérios. Os resultados deverão cumprir todos
                                            os filtros escolhidos.
                                        </p>

                                        <div class="row mb-3">

                                            <div class="col-md-4">
                                                <label class="form-label">Código interno</label>
                                                <input type="text" class="form-control" name="codigo">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Designação</label>
                                                <input type="text" class="form-control" name="designacao">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Marca</label>
                                                <input type="text" class="form-control" name="marca">
                                            </div>

                                        </div>

                                        <div class="row mb-3">

                                            <div class="col-md-4">
                                                <label class="form-label">Modelo</label>
                                                <input type="text" class="form-control" name="modelo">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Número de série</label>
                                                <input type="text" class="form-control" name="numero_serie">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Fornecedor</label>
                                                <select class="form-select" name="fornecedor">
                                                    <option selected>Todos</option>
                                                    <option>Philips Healthcare</option>
                                                    <option>Dräger Portugal</option>
                                                    <option>B. Braun Medical</option>
                                                    <option>Zoll Medical</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mb-3">

                                            <div class="col-md-3">
                                                <label class="form-label">Serviço</label>
                                                <select class="form-select" name="servico">
                                                    <option selected>Todos</option>
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

                                            <div class="col-md-3">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="estado">
                                                    <option selected>Todos</option>
                                                    <option>Ativo</option>
                                                    <option>Em manutenção</option>
                                                    <option>Inativo</option>
                                                    <option>Em calibração</option>
                                                    <option>Em quarentena</option>
                                                    <option>Abatido</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Categoria</label>
                                                <select class="form-select" name="categoria">
                                                    <option selected>Todas</option>
                                                    <option>Monitorização</option>
                                                    <option>Suporte de vida</option>
                                                    <option>Diagnóstico</option>
                                                    <option>Terapia</option>
                                                    <option>Laboratório</option>
                                                    <option>Esterilização</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Criticidade</label>
                                                <select class="form-select" name="criticidade">
                                                    <option selected>Todas</option>
                                                    <option>Baixa</option>
                                                    <option>Média</option>
                                                    <option>Alta</option>
                                                    <option>Suporte de vida</option>
                                                </select>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-rotate-left me-1"></i>
                                            Limpar filtros
                                        </button>

                                        <button type="button" class="btn btn-pink" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-check me-1"></i>
                                            Aplicar filtros
                                        </button>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>

                    <hr>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Equipamento</th>
                                    <th>Marca</th>
                                    <th>Localização</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>EQ-0001</td>
                                    <td>Monitor Multiparamétrico de Sinais Vitais</td>
                                    <td>Philips</td>
                                    <td>Unidade de Cuidados Intensivos</td>
                                    <td>
                                        <span class="badge bg-success">
                                            Ativo
                                        </span>
                                    </td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Monitor Multiparamétrico">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>EQ-0002</td>
                                    <td>Ventilador Pulmonar</td>
                                    <td>Dräger</td>
                                    <td>Unidade de Cuidados Intensivos</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            Em quarentena
                                        </span>
                                    </td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Ventilador Pulmonar">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>EQ-0003</td>
                                    <td>Bomba de Infusão</td>
                                    <td>B. Braun</td>
                                    <td>Serviço de Medicina</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            Em manutenção
                                        </span>
                                    </td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Bomba de Infusão">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>EQ-0004</td>
                                    <td>Desfibrilhador</td>
                                    <td>Zoll</td>
                                    <td>Urgência</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            Inativo
                                        </span>
                                    </td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <a href="editar.php" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Desfibrilhador">
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

    <?php include '../../includes/footer.php'; ?>