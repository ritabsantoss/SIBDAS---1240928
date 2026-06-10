<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'localizacoes';

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
                                Detalhes da Localização
                            </strong>
                        </h2>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Edifício
                                </label>
                                <p class="form-control-plaintext">
                                    Edifício Principal
                                </p>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">
                                    Piso
                                </label>
                                <p class="form-control-plaintext">
                                    5º
                                </p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Serviço | Departamento
                                </label>
                                <p class="form-control-plaintext">
                                    Bloco Operatório
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Sala | Gabinete
                                </label>
                                <p class="form-control-plaintext">
                                    Bloco 05
                                </p>
                            </div>

                        </div>

                        <hr>

                            <h5 class="mb-3">
                                <i class="fa-solid fa-stethoscope me-2"></i>
                                Equipamentos nesta localização
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
                                            <td>EQ-0002</td>
                                            <td>Desfibrilhador Zoll R Series</td>
                                            <td>Emergência</td>
                                            <td>
                                                <span class="badge badge-sihem">
                                                    Ativo
                                                </span>
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

                                        <tr>
                                            <td>EQ-0015</td>
                                            <td>Monitor Multiparamétrico</td>
                                            <td>Monitorização</td>
                                            <td>
                                                <span class="badge badge-sihem">
                                                    Ativo
                                                </span>
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

                                    </tbody>

                                </table>

                            </div>

                        <hr>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Observações
                            </label>
                            <p class="form-control-plaintext">
                                Localização de acesso restrito.
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