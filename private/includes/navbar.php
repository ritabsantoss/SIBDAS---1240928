<?php $nome = $_SESSION['nome'] ?? $_SESSION['email'] ?? 'Utilizador'; ?>
<!-- NAVBAR -->
    <header class="private-navbar">
        <div class="d-flex align-items-center">
            <img src="<?php echo BASE_URL; ?>/assets/img/sihem1.png" class="private-logo" alt="Logo SIHEM">
        </div>

        <div class="dropdown">
            <button class="btn btn-user dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fa-regular fa-user me-2"></i> <?= htmlspecialchars($nome) ?>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/private/views/password.php"><i class="fa-solid fa-key me-2"></i>Alterar password</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/private/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Sair</a>
                </li>
            </ul>
        </div>
    </header>