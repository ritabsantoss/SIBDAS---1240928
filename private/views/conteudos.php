<?php
require_once __DIR__ . '/../includes/funcoes.php';
redirect_if_not_logged();

if ($_SESSION['perfil'] !== 'administrador') {
    header('Location: ' . BASE_URL . '/private/index.php');
    exit;
}

$erro_sistema = '';
$sucesso = '';
$conteudos = [];

// Carregar todos os conteúdos da BD
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->query("SELECT chave, valor FROM Conteudos");
    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
        $conteudos[$row->chave] = $row->valor;
    }
    $ligacao = null;
} catch (PDOException $err) {
    $erro_sistema = "Erro ao carregar os conteúdos.";
}

$pagina_ativa = 'conteudos';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <div class="mb-4">
            <h2 class="mb-1">
                <i class="fa-solid fa-pen-to-square me-2"></i>
                Gestão da Página Inicial
            </h2>
            <p class="text-muted mb-0">
                Atualização dos conteúdos apresentados na página pública do SIHEM.
            </p>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <form action="#" method="post" enctype="multipart/form-data">

                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#hero"
                                type="button">
                                <i class="fa-solid fa-house-medical me-1"></i>Hero
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#estatisticas"
                                type="button">
                                <i class="fa-solid fa-chart-column me-1"></i>Estatísticas
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#servicos" type="button">
                                <i class="fa-solid fa-layer-group me-1"></i>Serviços
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#faq" type="button">
                                <i class="fa-solid fa-circle-question me-1"></i>FAQ
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contactos" type="button">
                                <i class="fa-solid fa-address-book me-1"></i>Contactos
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <!-- HERO -->
                        <div class="tab-pane fade show active" id="hero">

                            <h5 class="mb-3">Secção Principal</h5>

                            <div class="mb-3">
                                <label for="hero_titulo" class="form-label">Título principal</label>
                                <input type="text" class="form-control" id="hero_titulo" name="hero_titulo"
                                    value="<?= conteudo($conteudos, 'hero_titulo') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="hero_subtitulo" class="form-label">Texto de apresentação</label>
                                <textarea class="form-control" id="hero_subtitulo" name="hero_subtitulo"
                                    rows="4"><?= conteudo($conteudos, 'hero_subtitulo') ?></textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="hero_botao" class="form-label">Texto do botão</label>
                                    <input type="text" class="form-control" id="hero_botao" name="hero_botao"
                                        value="<?= conteudo($conteudos, 'hero_botao') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="hero_link" class="form-label">Destino do botão</label>
                                    <input type="text" class="form-control" id="hero_link" name="hero_link"
                                        value="<?= conteudo($conteudos, 'hero_link') ?>">
                                </div>
                            </div>

                            <div class="alert alert-light border rounded-4">
                                <strong>Imagem atual:</strong> sihem3.jpg
                            </div>

                            <div class="mb-4">
                                <label for="hero_imagem" class="form-label">Substituir imagem principal</label>
                                <input type="file" class="form-control" id="hero_imagem" name="hero_imagem"
                                    accept=".jpg,.jpeg,.png,.webp">
                            </div>

                        </div>

                        <!-- ESTATÍSTICAS -->
                        <div class="tab-pane fade" id="estatisticas">

                            <h5 class="mb-3">Estatísticas da Área Pública</h5>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="estat1_valor" class="form-label">Valor 1</label>
                                    <input type="text" class="form-control" id="estat1_valor"
                                        name="estat1_valor" value="<?= conteudo($conteudos, 'estat1_valor') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="estat2_valor" class="form-label">Valor 2</label>
                                    <input type="text" class="form-control" id="estat2_valor"
                                        name="estat2_valor" value="<?= conteudo($conteudos, 'estat2_valor') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="estat3_valor" class="form-label">Valor 3</label>
                                    <input type="text" class="form-control" id="estat3_valor"
                                        name="estat3_valor" value="<?= conteudo($conteudos, 'estat3_valor') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="estat4_valor" class="form-label">Valor 4</label>
                                    <input type="text" class="form-control" id="estat4_valor"
                                        name="estat4_valor" value="<?= conteudo($conteudos, 'estat4_valor') ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="estat1_label" class="form-label">Legenda 1</label>
                                    <input type="text" class="form-control" id="estat1_label"
                                        name="estat1_label" value="<?= conteudo($conteudos, 'estat1_label') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="estat2_label" class="form-label">Legenda 2</label>
                                    <input type="text" class="form-control" id="estat2_label"
                                        name="estat2_label" value="<?= conteudo($conteudos, 'estat2_label') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="estat3_label" class="form-label">Legenda 3</label>
                                    <input type="text" class="form-control" id="estat3_label"
                                        name="estat3_label" value="<?= conteudo($conteudos, 'estat3_label') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="estat4_label" class="form-label">Legenda 4</label>
                                    <input type="text" class="form-control" id="estat4_label"
                                        name="estat4_label" value="<?= conteudo($conteudos, 'estat4_label') ?>">
                                </div>
                            </div>

                        </div>

                        <!-- SERVIÇOS -->
                        <div class="tab-pane fade" id="servicos">

                            <h5 class="mb-3">Serviços da Plataforma</h5>

                            <div class="mb-3">
                                <label for="servicos_titulo" class="form-label">Título da secção</label>
                                <input type="text" class="form-control" id="servicos_titulo" name="servicos_titulo"
                                    value="<?= conteudo($conteudos, 'servicos_titulo') ?>">
                            </div>

                            <div class="mb-4">
                                <label for="servicos_subtitulo" class="form-label">Texto introdutório</label>
                                <textarea class="form-control" id="servicos_subtitulo" name="servicos_subtitulo"
                                    rows="3"><?= conteudo($conteudos, 'servicos_subtitulo') ?></textarea>
                            </div>

                            <hr>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>Serviço 1</h6>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Ícone</label>
                                        <input type="text" class="form-control" name="servico1_icone"
                                            value="fa-solid fa-laptop-file">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Título</label>
                                        <input type="text" class="form-control" name="serv1_titulo"
                                            value="<?= conteudo($conteudos, 'serv1_titulo') ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Descrição</label>
                                        <textarea class="form-control" name="serv1_texto"
                                            rows="2"><?= conteudo($conteudos, 'serv1_texto') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>Serviço 2</h6>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Ícone</label>
                                        <input type="text" class="form-control" name="servico2_icone"
                                            value="fa-solid fa-file-medical">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Título</label>
                                        <input type="text" class="form-control" name="serv2_titulo"
                                            value="<?= conteudo($conteudos, 'serv2_titulo') ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Descrição</label>
                                        <textarea class="form-control" name="serv2_texto"
                                            rows="2"><?= conteudo($conteudos, 'serv2_texto') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>Serviço 3</h6>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Ícone</label>
                                        <input type="text" class="form-control" name="servico3_icone"
                                            value="fa-solid fa-location-dot">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Título</label>
                                        <input type="text" class="form-control" name="serv3_titulo"
                                            value="<?= conteudo($conteudos, 'serv3_titulo') ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Descrição</label>
                                        <textarea class="form-control" name="serv3_texto"
                                            rows="2"><?= conteudo($conteudos, 'serv3_texto') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-4 p-3 mb-4">
                                <h6>Serviço 4</h6>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Ícone</label>
                                        <input type="text" class="form-control" name="servico4_icone"
                                            value="fa-solid fa-chart-pie">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Título</label>
                                        <input type="text" class="form-control" name="serv4_titulo"
                                            value="<?= conteudo($conteudos, 'serv4_titulo') ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Descrição</label>
                                        <textarea class="form-control" name="serv4_texto"
                                            rows="2"><?= conteudo($conteudos, 'serv4_texto') ?></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- FAQ -->
                        <div class="tab-pane fade" id="faq">

                            <h5 class="mb-3">Perguntas Frequentes</h5>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>FAQ 1</h6>
                                <label class="form-label">Pergunta</label>
                                <input type="text" class="form-control mb-3" name="faq1_pergunta"
                                    value="<?= conteudo($conteudos, 'faq1_pergunta') ?>">

                                <label class="form-label">Resposta</label>
                                <textarea class="form-control" name="faq1_resposta"
                                    rows="3"><?= conteudo($conteudos, 'faq1_resposta') ?></textarea>
                            </div>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>FAQ 2</h6>
                                <label class="form-label">Pergunta</label>
                                <input type="text" class="form-control mb-3" name="faq2_pergunta"
                                    value="<?= conteudo($conteudos, 'faq2_pergunta') ?>">

                                <label class="form-label">Resposta</label>
                                <textarea class="form-control" name="faq2_resposta"
                                    rows="3"><?= conteudo($conteudos, 'faq2_resposta') ?></textarea>
                            </div>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>FAQ 3</h6>
                                <label class="form-label">Pergunta</label>
                                <input type="text" class="form-control mb-3" name="faq3_pergunta"
                                    value="<?= conteudo($conteudos, 'faq3_pergunta') ?>">

                                <label class="form-label">Resposta</label>
                                <textarea class="form-control" name="faq3_resposta"
                                    rows="3"><?= conteudo($conteudos, 'faq3_resposta') ?></textarea>
                            </div>

                            <div class="border rounded-4 p-3 mb-3">
                                <h6>FAQ 4</h6>
                                <label class="form-label">Pergunta</label>
                                <input type="text" class="form-control mb-3" name="faq4_pergunta"
                                    value="<?= conteudo($conteudos, 'faq4_pergunta') ?>">

                                <label class="form-label">Resposta</label>
                                <textarea class="form-control" name="faq4_resposta"
                                    rows="3"><?= conteudo($conteudos, 'faq4_resposta') ?></textarea>
                            </div>

                            <div class="border rounded-4 p-3 mb-4">
                                <h6>FAQ 5</h6>
                                <label class="form-label">Pergunta</label>
                                <input type="text" class="form-control mb-3" name="faq5_pergunta"
                                    value="<?= conteudo($conteudos, 'faq5_pergunta') ?>">

                                <label class="form-label">Resposta</label>
                                <textarea class="form-control" name="faq5_resposta"
                                    rows="3"><?= conteudo($conteudos, 'faq5_resposta') ?></textarea>
                            </div>

                        </div>

                        <!-- CONTACTOS -->
                        <div class="tab-pane fade" id="contactos">

                            <h5 class="mb-3">Contactos e Informação da Plataforma</h5>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="contacto_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contacto_email"
                                        name="contacto_email" value="<?= conteudo($conteudos, 'contacto_email') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="contacto_telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="contacto_telefone"
                                        name="contacto_telefone" value="<?= conteudo($conteudos, 'contacto_telefone') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="contacto_local" class="form-label">Localização</label>
                                    <input type="text" class="form-control" id="contacto_local"
                                        name="contacto_local" value="<?= conteudo($conteudos, 'contacto_local') ?>">
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="plataforma_versao" class="form-label">Versão da plataforma</label>
                                    <input type="text" class="form-control" id="plataforma_versao" name="plataforma_versao" value="<?= conteudo($conteudos, 'plataforma_versao') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="plataforma_atualizacao" class="form-label">Última atualização</label>
                                    <input type="text" class="form-control" id="plataforma_atualizacao"
                                        name="plataforma_atualizacao" value="<?= conteudo($conteudos, 'plataforma_atualizacao') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="plataforma_estado" class="form-label">Estado do sistema</label>
                                    <select class="form-select" id="plataforma_estado" name="plataforma_estado">
                                        <?php foreach (['Sistema Online', 'Sistema em manutenção', 'Sistema indisponível'] as $op) : ?>
                                            <option <?= ($conteudos['plataforma_estado'] ?? '') === $op ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($op) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="../index.php" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-pink">
                            <i class="fa-regular fa-floppy-disk me-1"></i>
                            Guardar Alterações
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </main>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>