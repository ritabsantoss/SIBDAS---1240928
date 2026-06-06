<?php
require_once '../../includes/funcoes.php';
redirect_if_not_logged();
?>
<?php $pagina_ativa = 'garantias'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

    <div class="private-container">

        <?php include '../../includes/sidebar.php'; ?>

        <!-- Conteúdo -->
        <main class="private-main">
            <div class="d-flex justify-content-center mt-4">
                <div class="card w-100 shadow rounded" style="max-width: 900px;">

                    <div class="card-body">
                        <h2 class="mb-3">
                            <strong>
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Detalhes do Registo
                            </strong>
                        </h2>
                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Código
                                </label>
                                <p class="form-control-plaintext">
                                    GC-0122
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Equipamento associado
                                </label>
                                <p class="form-control-plaintext">
                                    Bomba de Infusão
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Entidade responsável
                                </label>
                                <p class="form-control-plaintext">
                                    B.Braun
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Data de início
                                </label>
                                <p class="form-control-plaintext">
                                    2023-12-13
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Data de fim
                                </label>
                                <p class="form-control-plaintext">
                                    2025-12-31
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Estado
                                </label>
                                <p class="form-control-plaintext">
                                    Expirada
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Contrato de manutenção
                                </label>
                                <p class="form-control-plaintext">
                                    Não
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Tipo de contrato
                                </label>
                                <p class="form-control-plaintext">
                                    Garantia do Fornecedor
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Periodicidade
                                </label>
                                <p class="form-control-plaintext">
                                    Bianual
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                    Observações
                            </label>
                            <p class="form-control-plaintext">
                                    Contactar apoio.
                            </p>

                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="lista.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Voltar
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../includes/footer.php'; ?>
    