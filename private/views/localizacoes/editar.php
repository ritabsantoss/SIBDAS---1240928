<?php
require_once '../../includes/funcoes.php';
redirect_if_not_logged();
?>
<?php $pagina_ativa = 'localizacoes'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

    <div class="private-container">

        <?php include '../../includes/sidebar.php'; ?>

        <!--Conteúdo-->
        <main class="private-main">

            <div class="mb-4">
                <h2 class="mb-1">
                    <i class="fa-regular fa-pen-to-square me-2"></i>
                    Editar Localização
                </h2>

                <p class="text-muted mb-0">
                    Atualize as informações da localização selecionada.
                </p>

            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <form action="#" method="post" >

                        <h5 class="mb-3">
                            Local</h5>

                        <div class="row mb-3">

                            <div class="col-md-3">
                                <label for="edificio" class="form-label">Edifício</label>
                                <input type="text" class="form-control" id="edificio" name="edificio"
                                    value="Edifício Principal">
                            </div>

                            <div class="col-md-3">
                                <label for="piso" class="form-label">Piso</label>
                                <input type="text" class="form-control" id="piso" name="piso" value="5º">
                            </div>

                            <div class="col-md-6">
                                <label for="departamento" class="form-label">Serviço | Departamento</label>
                                <select class="form-select" id="departamento" name="departamento">
                                    <option>Urgência</option>
                                    <option>Unidade de Cuidados Intensivos</option>
                                    <option selected>Bloco Operatório</option>
                                    <option>Radiologia</option>
                                    <option>Laboratório</option>
                                    <option>Consulta Externa</option>
                                    <option>Internamento</option>
                                    <option>Pediatria</option>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="sala" class="form-label">Sala | Gabinete</label>
                                <input type="text" class="form-control" id="sala" name="sala" value="Bloco 05">
                            </div>

                            <div class="col-md-6">
                                <label for="equipamento" class="form-label">Equipamento associado</label>
                                <select class="form-select" id="equipamento" name="equipamento">
                                    <option>Monitor Multiparamétrico</option>
                                    <option>Ventilador Pulmonar</option>
                                    <option>Bomba de Infusão</option>
                                    <option selected>Desfibrilhador</option>
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

                        <textarea class="form-control mb-4" id="observacoes" name="observacoes" rows="4">Localização de acesso restrito.</textarea>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="lista.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-pink">
                                <i class="fa-regular fa-floppy-disk me-1"></i>
                                Guardar alterações
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../includes/footer.php'; ?>