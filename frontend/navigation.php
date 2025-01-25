<!-- Navigation Menu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Trading Bot</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php echo __('toggle_navigation'); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse navbar-expand-lg" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="models.php"><?php echo __('models'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_model.php"><?php echo __('create_model'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="trade_history.php"><?php echo __('trade_history'); ?></a>
                </li>
            </ul>
                
            <ul class="navbar-nav ms-auto">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <?php echo $username; ?> </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="logout.php"><?php echo __('log_out'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </ul>
        </div>
    </div>
</nav>
