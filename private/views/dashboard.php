<?php $pagina_ativa = 'dashboard'; ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

    <div class="private-container">

        <?php include '../includes/sidebar.php'; ?>

        <!-- Conteúdo -->
        <main class="private-main">

            <div class="page-header mb-4">
                <h2><i class="fa-solid fa-chart-line me-2"></i>Dashboard</h2>
                <p>Visão geral do parque tecnológico hospitalar.</p>
            </div>

            <!-- Cards principais -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-stethoscope"></i>
                        <h3 id="totalEquipamentos">0</h3>
                        <p>Total</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <h3 id="ativos">0</h3>
                        <p>Ativos</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-screwdriver-wrench text-warning"></i>
                        <h3 id="manutencao">0</h3>
                        <p>Manutenção</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-circle-xmark text-secondary"></i>
                        <h3 id="inativos">0</h3>
                        <p>Inativos</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                        <h3 id="garantiasExpiradas">0</h3>
                        <p>Garantias expiradas</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-file-circle-xmark text-danger"></i>
                        <h3 id="semDocumentacao">0</h3>
                        <p>Sem documentação</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-calendar-days text-warning"></i>
                        <h3 id="garantias30Dias">0</h3>
                        <p>Garantias a expirar</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fa-solid fa-heart-pulse text-danger"></i>
                        <h3 id="criticidadeElevada">0</h3>
                        <p>Criticidade elevada</p>
                    </div>
                </div>

            </div>

            <!-- Gráficos e tabelas -->
            <div class="row g-4 align-items-stretch">

                <!-- Coluna esquerda -->
                <div class="col-lg-6 d-flex flex-column gap-4">
                    <div class="dashboard-box">
                        <h5>Equipamentos por estado</h5>
                        <canvas id="graficoEstado"></canvas>
                    </div>

                    <div class="dashboard-box flex-fill">
                        <h5>Equipamentos por serviço</h5>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Serviço</th>
                                    <th>N.º Equipamentos</th>
                                    <th>Suporte de vida</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaServicos"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Coluna direita -->
                <div class="col-lg-6 d-flex flex-column gap-4">
                    <div class="dashboard-box">
                        <h5>Equipamentos por criticidade</h5>
                        <canvas id="graficoCriticidade"></canvas>
                    </div>

                    <div class="dashboard-box">
                        <h5>Distribuição por categoria</h5>
                        <canvas id="graficoCategoria"></canvas>
                    </div>

                    <div class="dashboard-box flex-fill">
                        <h5>Alertas importantes</h5>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Equipamento</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaAlertas"></tbody>
                        </table>
                    </div>
                </div>

            </div>


        </main>

    </div>

    <!-- Chart.js antes do footer (o 1240928.js precisa dele para desenhar os gráficos) -->
    <script src="<?php echo BASE_URL; ?>/assets/chartjs/chart.umd.min.js"></script>

<?php include '../includes/footer.php'; ?>