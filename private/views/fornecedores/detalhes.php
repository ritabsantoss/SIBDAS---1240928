<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'fornecedores';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>
    <div class="private-container">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <!--Conteúdo-->
        <main class="private-main">

            <div class="d-flex justify-content-center mt-4">
                <div class="card w-100 shadow rounded" style="max-width: 800px;">

                    <div class="card-body">
                        <h2 class="mb-3">
                            <strong>
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Detalhes do Fornecedor
                            </strong>
                        </h2>
                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Nome da empresa
                                </label>
                                <p class="form-control-plaintext">
                                    B. Braun Medical
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    NIF
                                </label>
                                <p class="form-control-plaintext">
                                    501506543
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Contacto telefónico
                                </label>
                                <p class="form-control-plaintext">
                                    214 368 200
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Email
                                </label>
                                <p class="form-control-plaintext">
                                    info.bbmp@bbraun.com
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Website
                                </label>
                                <p class="form-control-plaintext">
                                    https://www.bbraun.pt
                                </p>
                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Morada
                            </label>
                            <p class="form-control-plaintext">
                                Estrada Consiglieri Pedroso, 80, 2730-053 Barcarena, Portugal
                            </p>

                        </div>

                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Pessoa de contacto
                                </label>
                                <p class="form-control-plaintext">
                                    Rita Santos
                                </p>

                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Telefone da pessoa de contacto
                                </label>
                                <p class="form-control-plaintext">
                                    912345678
                                </p>
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">
                            <i class="fa-solid fa-stethoscope me-2"></i>
                            Equipamentos associados
                        </h5>

                        <div class="table-responsive mb-4">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Designação</th>
                                        <th>Categoria</th>
                                        <th>Estado</th>
                                        <th>Criticidade</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>EQ-0003</td>
                                        <td>Desfribilhador</td>
                                        <td>Suporte de vida</td>
                                        <td>
                                            <span class="badge badge-sihem">Ativo</span>
                                        </td>
                                        <td>Média</td>
                                        <td class="text-end">
                                            <a href="../equipamentos/detalhes.php"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye me-1"></i>
                                                Ver
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>EQ-0007</td>
                                        <td>Bomba de Infusão</td>
                                        <td>Terapia</td>
                                        <td>
                                            <span class="badge badge-sihem-pink">Em manutenção</span>
                                        </td>
                                        <td>Alta</td>
                                        <td class="text-end">
                                            <a href="../equipamentos/detalhes.php"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye me-1"></i>
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Observações
                            </label>
                            <p class="form-control-plaintext">
                                Último contacto realizado com Alexandra Bastos relativamente ao fornecimento de
                                consumíveis hospitalares.
                            </p>
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="lista.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Voltar
                            </a>

                            <a href="editar.php" class="btn btn-pink">
                                <i class="fa-regular fa-pen-to-square me-1"></i>
                                Editar
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>