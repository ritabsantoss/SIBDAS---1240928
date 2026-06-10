<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'documentos';

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

                    <form action="#" method="get">

                        <div class="row mb-4">

                            <div class="col-md-5">
                                <label class="form-label">Pesquisa rápida</label>
                                <input type="text" class="form-control" name="pesquisa">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo de Documento</label>
                                <select class="form-select" name="tipo">
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
                                    <option value="tipo_documento" selected>Tipo de Documento</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sentido</label>
                                <select class="form-select" name="sentido">
                                    <option value="asc" selected>Ascendente</option>
                                    <option value="desc">Descendente</option>
                                </select>
                            </div>

                        </div>

                    </form>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

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
                                <tr>
                                    <td>MAN-0928</td>
                                    <td>Manual Técnico Evita V500</td>
                                    <td>Manual de Utilizador</td>
                                    <td>Ventilador Pulmonar</td>
                                    <td>Dräger</td>
                                    <td>2028-12-31</td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Manual Técnico Evita V500">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>AUT-0012</td>
                                    <td>Autorização Infusomat Space</td>
                                    <td>Manual de Serviço</td>
                                    <td>Bomba de Infusão</td>
                                    <td>B. Braun</td>
                                    <td>2025-12-31</td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Autorização Infusomat Space">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>

                                    </td>
                                </tr>

                                <tr>
                                    <td>FIC-0076</td>
                                    <td>Ficha R Series </td>
                                    <td>Certificado de Calibração</td>
                                    <td>Desfibrilhador</td>
                                    <td>Zoll</td>
                                    <td>2026-10-01</td>

                                    <td class="text-center">

                                        <a href="detalhes.php" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-circle-info"></i></a>

                                        <button class="btn btn-sm btn-outline-danger btn-gestao" data-bs-toggle="modal"
                                            data-bs-target="#modalArquivar" data-nome="Ficha R Series">
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

                            <h4 class="mb-3">Gestão do Documento</h4>

                            <p class="text-muted mb-2">
                                Documento selecionado:
                            </p>

                            <h5 id="itemSelecionado" class="mb-4 text-primary">
                                —
                            </h5>

                            <p class="text-muted mb-4">
                                Pretende arquivar ou eliminar este documento?
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