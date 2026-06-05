<!-- SIDEBAR -->
<aside class="private-sidebar">
    <h5>MENU</h5>

    <a href="<?php echo BASE_URL; ?>/private/index.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'inicio' ? 'active' : ''; ?>">
        <i class="fa-solid fa-house"></i> Início
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/dashboard.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'dashboard' ? 'active' : ''; ?>">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/equipamentos/lista.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'equipamentos' ? 'active' : ''; ?>">
        <i class="fa-solid fa-stethoscope"></i> Equipamentos
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/fornecedores/lista.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'fornecedores' ? 'active' : ''; ?>">
        <i class="fa-solid fa-truck"></i> Fornecedores
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/localizacoes/lista.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'localizacoes' ? 'active' : ''; ?>">
        <i class="fa-solid fa-location-dot"></i> Localizações
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/documentos/lista.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'documentos' ? 'active' : ''; ?>">
        <i class="fa-solid fa-file-pdf"></i> Documentação
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/garantias/lista.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'garantias' ? 'active' : ''; ?>">
        <i class="fa-solid fa-file-contract"></i> Garantias | Contratos
    </a>
    <a href="<?php echo BASE_URL; ?>/private/views/conteudos.php"
       class="sidebar-link <?php echo ($pagina_ativa ?? '') === 'conteudos' ? 'active' : ''; ?>">
        <i class="fa-solid fa-pen-to-square"></i> Conteúdos Públicos
    </a>
</aside>