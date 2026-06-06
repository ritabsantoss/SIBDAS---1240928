<?php $pagina_ativa = 'equipamentos'; ?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<div class="private-container">

    <?php include '../../includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1">
                <i class="fa-regular fa-pen-to-square me-2"></i>
                Editar Equipamento
            </h2>
            <p class="text-muted mb-0">Atualize as informações do equipamento médico por etapas.</p>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <div id="aviso-passos" class="alert alert-warning d-none" role="alert"></div>

                <form id="form-equipamento" action="#" method="post" enctype="multipart/form-data">

                    <ul class="nav nav-tabs mb-4" id="equipamentoTabs">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#passo-identificacao" type="button">1. Identificação</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-aquisicao" type="button">2. Aquisição</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-fornecedor" type="button">3. Fornecedor</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-localizacao" type="button">4. Localização</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-documentacao" type="button">5. Documentação</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-garantia" type="button">6. Garantia</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#passo-contrato" type="button">7. Contrato</button></li>
                    </ul>

                    <div class="tab-content">

                        <!-- PASSO 1 -->
                        <div class="tab-pane fade show active" id="passo-identificacao">
                            <h5 class="mb-3">Identificação</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="codigo_interno" class="form-label">Código interno</label>
                                    <input type="text" class="form-control" id="codigo_interno" name="codigo_interno"
                                        value="EQ-0001">
                                </div>

                                <div class="col-md-6">
                                    <label for="designacao" class="form-label">Designação do equipamento</label>
                                    <input type="text" class="form-control" id="designacao" name="designacao"
                                        value="Monitor Multiparamétrico de Sinais Vitais">
                                </div>

                                <div class="col-md-3">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option>Escolha...</option>
                                        <option selected>Monitorização</option>
                                        <option>Suporte de vida</option>
                                        <option>Diagnóstico</option>
                                        <option>Cirurgia</option>
                                        <option>Laboratório</option>
                                        <option>Neonatologia</option>
                                        <option>Reabilitação</option>
                                        <option>Imagem médica</option>
                                        <option>Terapia</option>
                                        <option>Anestesia</option>
                                        <option>Emergência</option>
                                        <option>Odontologia</option>
                                        <option>Cardiologia</option>
                                        <option>Fisioterapia</option>
                                        <option>Esterilização</option>
                                        <option>Transporte hospitalar</option>
                                        <option>Equipamento auxiliar</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Informação Técnica</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="marca" name="marca" value="Philips">
                                </div>

                                <div class="col-md-3">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="modelo" name="modelo"
                                        value="IntelliVue MP5">
                                </div>

                                <div class="col-md-3">
                                    <label for="numero_serie" class="form-label">Número de série</label>
                                    <input type="text" class="form-control" id="numero_serie" name="numero_serie"
                                        value="MP5-2022-45873">
                                </div>

                                <div class="col-md-3">
                                    <label for="fabricante" class="form-label">Fabricante</label>
                                    <input type="text" class="form-control" id="fabricante" name="fabricante"
                                        value="Philips Healthcare">
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Componentes associados</h5>

                            <p class="text-muted">
                                Edite, remova ou adicione componentes associados ao equipamento.
                            </p>

                            <div id="componentes-container">
                                <div class="componente-bloco border rounded-4 p-3 mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label">Código</label>
                                            <input type="text" class="form-control"
                                                name="componentes[0][codigo_componente]" value="COMP-001">
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label">Nome</label>
                                            <input type="text" class="form-control" name="componentes[0][nome_componente]"
                                                value="Sensor SpO₂">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Estado</label>
                                            <select class="form-select" name="componentes[0][estado_componente]">
                                                <option>Escolha...</option>
                                                <option selected>Ativo</option>
                                                <option>Inativo</option>
                                                <option>Em manutenção</option>
                                            </select>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-outline-danger remover-componente">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-componente">
                                <i class="fa-solid fa-plus me-1"></i>
                                Adicionar componente
                            </button>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="lista.php" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-xmark me-1"></i>Cancelar
                                </a>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="0">
                                    Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASSO 2 -->
                        <div class="tab-pane fade" id="passo-aquisicao">
                            <h5 class="mb-3">Aquisição</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="data_aquisicao" class="form-label">Data de aquisição</label>
                                    <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao"
                                        value="2023-01-31">
                                </div>

                                <div class="col-md-3">
                                    <label for="ano_fabrico" class="form-label">Ano de fabrico</label>
                                    <input type="number" class="form-control" id="ano_fabrico" name="ano_fabrico"
                                        value="2022">
                                </div>

                                <div class="col-md-3">
                                    <label for="custo" class="form-label">Custo de aquisição</label>
                                    <input type="number" class="form-control" id="custo" name="custo" step="0.01"
                                        value="1200.00">
                                </div>

                                <div class="col-md-3">
                                    <label for="tipo_entrada" class="form-label">Tipo de entrada</label>
                                    <select class="form-select" id="tipo_entrada" name="tipo_entrada">
                                        <option>Escolha...</option>
                                        <option selected>Compra</option>
                                        <option>Doação</option>
                                        <option>Aluguer</option>
                                        <option>Empréstimo</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Estado | Criticidade</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="estado_atual" class="form-label">Estado atual</label>
                                    <select class="form-select" id="estado_atual" name="estado_atual">
                                        <option>Escolha...</option>
                                        <option selected>Ativo</option>
                                        <option>Em manutenção</option>
                                        <option>Inativo</option>
                                        <option>Em calibração</option>
                                        <option>Em quarentena</option>
                                        <option>Abatido</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="criticidade" class="form-label">Criticidade</label>
                                    <select class="form-select" id="criticidade" name="criticidade">
                                        <option>Escolha...</option>
                                        <option>Baixa</option>
                                        <option>Média</option>
                                        <option>Alta</option>
                                        <option selected>Suporte de vida</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Observações</h5>
                            <textarea class="form-control mb-4" id="observacoes_equipamento"
                                name="observacoes_equipamento" rows="4">Equipamento pode possuir associados os seguintes componentes: sensor SpO₂, cabo ECG, manguito NIBP, sensor de temperatura, bateria.</textarea>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="1">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                                </button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="1">
                                    Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASSO 3 -->
                        <div class="tab-pane fade" id="passo-fornecedor">
                            <h5 class="mb-3">
                                <i class="fa-solid fa-truck me-2"></i>
                                Fornecedores Associados
                            </h5>

                            <p class="text-muted">
                                Edite os fornecedores, fabricantes ou prestadores de assistência técnica associados.
                            </p>

                            <div id="fornecedores-container">
                                <div class="fornecedor-bloco border rounded-4 p-3 mb-3">
                                    <div class="row align-items-end mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Fornecedor</label>
                                            <select class="form-select" name="fornecedores[0][id_fornecedor]">
                                                <option value="">Escolha...</option>
                                                <option value="1" selected>Philips Healthcare Portugal</option>
                                                <option value="2">Dräger Portugal</option>
                                                <option value="3">B. Braun Medical</option>
                                                <option value="4">Zoll Medical</option>
                                            </select>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label">Tipo de relação</label>
                                            <select class="form-select" name="fornecedores[0][tipo_relacao]">
                                                <option>Escolha...</option>
                                                <option selected>Fabricante</option>
                                                <option>Distribuidor ou fornecedor comercial</option>
                                                <option>Empresa de assistência técnica</option>
                                                <option>Fornecedor de consumíveis ou acessórios</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <button type="button"
                                                class="btn btn-outline-danger remover-fornecedor w-100 d-none">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="border rounded-4 p-3 bg-light">
                                        <h6 class="mb-3">Dados do fornecedor selecionado</h6>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">NIF</label>
                                                <input type="text" class="form-control"
                                                    name="fornecedores[0][nif_fornecedor]" value="500216843" readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Telefone</label>
                                                <input type="text" class="form-control"
                                                    name="fornecedores[0][telefone_fornecedor]" value="800 201 766"
                                                    readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control"
                                                    name="fornecedores[0][email_fornecedor]"
                                                    value="healthcare.portugal@philips.com" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-fornecedor">
                                <i class="fa-solid fa-plus me-1"></i>
                                Adicionar fornecedor
                            </button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="2">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                                </button>

                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="2">
                                    Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASSO 4 -->
                        <div class="tab-pane fade" id="passo-localizacao">
                            <h5 class="mb-3">
                                <i class="fa-solid fa-location-dot me-2"></i>
                                Localização Associada
                            </h5>

                            <div class="mb-3">
                                <label for="localizacao_associada" class="form-label">Selecionar localização</label>
                                <select class="form-select" id="localizacao_associada" name="localizacao_associada">
                                    <option>Escolha...</option>
                                    <option selected>Unidade de Cuidados Intensivos - Sala UCI 07</option>
                                    <option>Bloco Operatório - Bloco 05</option>
                                    <option>Serviço de Medicina - Sala 03</option>
                                </select>
                            </div>

                            <div class="border rounded-4 p-3 bg-light mb-3">
                                <h6 class="mb-3">Dados da localização selecionada</h6>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Edifício</label>
                                        <input type="text" class="form-control" id="localizacao_edificio"
                                            value="Edifício Principal" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Piso</label>
                                        <input type="text" class="form-control" id="localizacao_piso" value="2º" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Serviço | Departamento</label>
                                        <input type="text" class="form-control" id="localizacao_departamento"
                                            value="Unidade de Cuidados Intensivos" readonly>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Sala</label>
                                        <input type="text" class="form-control" id="localizacao_sala" value="Sala UCI 07"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted mb-0">
                                A localização deve existir previamente no módulo de localizações.
                            </p>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="3">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                                </button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="3">
                                    Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASSO 5 -->
                        <div class="tab-pane fade" id="passo-documentacao">
                            <h5 class="mb-3">
                                <i class="fa-solid fa-file-pdf me-2"></i>
                                Documentação Associada
                            </h5>

                            <p class="text-muted">
                                Edite, substitua ou adicione documentos associados ao equipamento.
                            </p>

                            <div id="documentos-container">
                                <div class="documento-bloco border rounded-4 p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Documento 1</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remover-documento d-none">
                                            <i class="fa-solid fa-trash"></i> Remover
                                        </button>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Código</label>
                                            <input type="text" class="form-control"
                                                name="documentos[0][codigo_documento]" value="MAN-0344">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Tipo de documento</label>
                                            <select class="form-select" name="documentos[0][tipo_documento]">
                                                <option>Escolha...</option>
                                                <option selected>Manual de Utilizador</option>
                                                <option>Manual de Serviço</option>
                                                <option>Certificado de Calibração</option>
                                                <option>Fatura ou Guia de Aquisição</option>
                                                <option>Declaração de Conformidade</option>
                                                <option>Relatório Técnico</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Nome do documento</label>
                                            <input type="text" class="form-control"
                                                name="documentos[0][nome_documento]" value="Manual Técnico IntelliVue MP5">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Data do documento</label>
                                            <input type="date" class="form-control"
                                                name="documentos[0][data_documento]" value="2023-03-27">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Data de validade</label>
                                            <input type="date" class="form-control" name="documentos[0][validade]"
                                                value="2030-03-31">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Estado</label>
                                            <select class="form-select" name="documentos[0][estado_documento]">
                                                <option>Escolha...</option>
                                                <option selected>Ativo</option>
                                                <option>Prestes a Expirar</option>
                                                <option>Expirado</option>
                                                <option>Pendente</option>
                                                <option>Anulado</option>
                                                <option>Estendido</option>
                                                <option>Não disponível</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Substituir ficheiro</label>
                                            <input type="file" class="form-control" name="documentos[0][ficheiro]"
                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Localização / hiperligação alternativa</label>
                                            <input type="text" class="form-control" name="documentos[0][loc_ficheiro]"
                                                value="Docs/MAN/Monitores/Multiparametro">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control"
                                            name="documentos[0][observacoes_documentacao]"
                                            rows="3">Versão mais recente.</textarea>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-secondary" id="adicionar-documento">
                                <i class="fa-solid fa-plus me-1"></i>
                                Adicionar documento
                            </button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="4">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                                </button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="4">
                                    Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASSO 6 -->
                        <div class="tab-pane fade" id="passo-garantia">
                            <h5 class="mb-3">
                                <i class="fa-solid fa-shield-halved me-2"></i>
                                Garantia
                            </h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="codigo_garantia" class="form-label">Código da garantia</label>
                                    <input type="text" class="form-control" id="codigo_garantia" name="codigo_garantia"
                                        value="GC-0773">
                                </div>

                                <div class="col-md-3">
                                    <label for="entidade_garantia" class="form-label">Entidade responsável</label>
                                    <select class="form-select" id="entidade_garantia" name="entidade_garantia">
                                        <option>Escolha...</option>
                                        <option>Dräger</option>
                                        <option>B. Braun</option>
                                        <option>Zoll</option>
                                        <option selected>Philips Healthcare</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="data_inicio_garantia" class="form-label">Data de início</label>
                                    <input type="date" class="form-control" id="data_inicio_garantia"
                                        name="data_inicio_garantia" value="2026-01-31">
                                </div>

                                <div class="col-md-3">
                                    <label for="data_fim_garantia" class="form-label">Data de fim</label>
                                    <input type="date" class="form-control" id="data_fim_garantia"
                                        name="data_fim_garantia" value="2027-01-31">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="estado_garantia" class="form-label">Estado da garantia</label>
                                    <select class="form-select" id="estado_garantia" name="estado_garantia">
                                        <option>Escolha...</option>
                                        <option selected>Ativa</option>
                                        <option>Prestes a Expirar</option>
                                        <option>Expirada</option>
                                        <option>Pendente</option>
                                        <option>Anulada</option>
                                        <option>Estendida</option>
                                        <option>Não disponível</option>
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label for="ficheiro_garantia" class="form-label">Documento da garantia</label>
                                    <input type="file" class="form-control" id="ficheiro_garantia"
                                        name="ficheiro_garantia">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="observacoes_garantia" class="form-label">Observações da garantia</label>
                                <textarea class="form-control" id="observacoes_garantia" name="observacoes_garantia"
                                    rows="4">Garantia associada ao equipamento no momento da aquisição.</textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="5">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                                </button>
                                <button type="button" class="btn btn-pink btn-seguinte" data-passo-atual="5">
                                    Seguinte <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PASSO 7 -->
                        <div class="tab-pane fade" id="passo-contrato">
                            <h5 class="mb-3">
                                <i class="fa-solid fa-file-contract me-2"></i>
                                Contrato de Manutenção
                            </h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="existe_contrato" class="form-label">Existe contrato de manutenção?</label>
                                    <select class="form-select" id="existe_contrato" name="existe_contrato">
                                        <option>Escolha...</option>
                                        <option>Sim</option>
                                        <option selected>Não</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="codigo_contrato" class="form-label">Código do contrato</label>
                                    <input type="text" class="form-control" id="codigo_contrato" name="codigo_contrato" value="-">
                                </div>

                                <div class="col-md-3">
                                    <label for="tipo_contrato" class="form-label">Tipo de contrato</label>
                                    <select class="form-select" id="tipo_contrato" name="tipo_contrato">
                                        <option>Escolha...</option>
                                        <option>Contrato de Manutenção</option>
                                        <option>Manutenção Preventiva</option>
                                        <option>Contrato de Assistência Técnica</option>
                                        <option selected>Sem Contrato</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="entidade_contrato" class="form-label">Entidade responsável</label>
                                    <select class="form-select" id="entidade_contrato" name="entidade_contrato">
                                        <option>Escolha...</option>
                                        <option>Dräger</option>
                                        <option>B. Braun</option>
                                        <option>Zoll</option>
                                        <option selected>Philips Healthcare</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="periodicidade" class="form-label">Periodicidade</label>
                                    <select class="form-select" id="periodicidade" name="periodicidade">
                                        <option>Escolha...</option>
                                        <option>Mensal</option>
                                        <option>Trimestral</option>
                                        <option>Semestral</option>
                                        <option selected>Anual</option>
                                        <option>Bianual</option>
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label for="ficheiro_contrato" class="form-label">Ficheiro do contrato</label>
                                    <input type="file" class="form-control" id="ficheiro_contrato"
                                        name="ficheiro_contrato" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="observacoes_contrato" class="form-label">Observações do contrato</label>
                                <textarea class="form-control" id="observacoes_contrato" name="observacoes_contrato"
                                    rows="4">Sem contrato de manutenção ativo.</textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-anterior" data-passo-atual="6">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Anterior
                                </button>

                                <button type="submit" class="btn btn-pink">
                                    <i class="fa-regular fa-floppy-disk me-1"></i>
                                    Guardar alterações
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>