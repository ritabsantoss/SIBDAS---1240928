<?php
require_once '../../includes/funcoes.php';
redirect_if_not_logged();
?>
<?php $pagina_ativa = 'fornecedores'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>
    
    <div class="private-container">

        <?php include '../../includes/sidebar.php'; ?>

        <!-- Conteúdo -->
        <main class="private-main">

            <div class="mb-4">
                <h2 class="mb-1">
                    <i class="fa-solid fa-plus me-2"></i>
                    Inserir Fornecedor
                </h2>

                <p class="text-muted mb-0">
                    Preencha as principais informações do fornecedor.
                </p>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <form action="#" method="post" >

                        <h5 class="mb-3">
                            Identificação</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome_empresa" class="form-label">Nome da empresa</label>
                                <input type="text" class="form-control" id="nome_empresa" name="nome_empresa">
                            </div>

                            <div class="col-md-6">
                                <label for="nif" class="form-label">NIF</label>
                                <input type="text" class="form-control" id="nif" name="nif">
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">
                            Contactos</h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label for="telefone" class="form-label">Contacto telefónico</label>
                                <input type="text" class="form-control" id="telefone" name="telefone">
                            </div>

                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <div class="col-md-4">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-12">
                                <label for="morada" class="form-label">Morada</label>
                                <input type="text" class="form-control" id="morada" name="morada">
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">
                            Pessoa de contacto</h5>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="pessoa_contacto" class="form-label">Pessoa de contacto</label>
                                <input type="text" class="form-control" id="pessoa_contacto" name="pessoa_contacto">
                            </div>

                            <div class="col-md-6">
                                <label for="telefone_contacto" class="form-label">Telefone da pessoa de contacto</label>
                                <input type="text" class="form-control" id="telefone_contacto" name="telefone_contacto">
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">Associação a equipamento</h5>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="equipamento_associado" class="form-label">Equipamento associado</label>
                                <select class="form-select" id="equipamento_associado" name="equipamento_associado">
                                    <option selected disabled>Escolha...</option>
                                    <option>Monitor Multiparamétrico de Sinais Vitais</option>
                                    <option>Ventilador Pulmonar</option>
                                    <option>Bomba de Infusão</option>
                                    <option>Eletrocardiógrafo</option>
                                    <option>Endoscópio</option>
                                    <option>Ecógrafo</option>
                                    <option>Carrinho de emergência</option>
                                    <option>Ressonância magnética</option>
                                </select>
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">Observações</h5>

                        <textarea class="form-control mb-4" id="observacoes" name="observacoes" rows="4">
                         </textarea>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="lista.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-pink">
                                <i class="fa-regular fa-floppy-disk me-1"></i>
                                Guardar
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../includes/footer.php'; ?>