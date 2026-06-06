<?php $pagina_ativa = 'documentos'; ?>
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
                                Detalhes do Documento
                            </strong>
                        </h2>
                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Código
                                </label>
                                <p class="form-control-plaintext">
                                    MAN-0928
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Equipamento associado
                                </label>
                                <p class="form-control-plaintext">
                                    Ventilador Pulmonar
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Fornecedor associado
                                </label>
                                <p class="form-control-plaintext">
                                    Dräger
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Data do documento
                                </label>
                                <p class="form-control-plaintext">
                                    2023-01-31
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Data de validade
                                </label>
                                <p class="form-control-plaintext">
                                    2028-12-31
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Estado
                                </label>
                                <p class="form-control-plaintext">
                                    Ativo
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Tipo de documento
                                </label>
                                <p class="form-control-plaintext">
                                    Manual de Utilizador
                                </p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Nome do documento
                                </label>
                                <p class="form-control-plaintext">
                                    Manual Técnico Evita V500
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Nome do ficheiro
                                </label>
                                <p class="form-control-plaintext">
                                    MAN-0928_Evita V500.pdf
                                </p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Localização do ficheiro
                                </label>
                                <p class="form-control-plaintext">
                                    Docs/MAN/Ventiladores/
                                </p>
                            </div>

                        </div>

                        <hr>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                    Observações
                            </label>
                            <p class="form-control-plaintext">
                                    Versão mais recente.
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