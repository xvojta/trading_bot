<!-- Navigation Menu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Trading Bot</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse navbar-expand-lg" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="evaluate.php">Evaluate model</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="trade_history.php">Trade History</a>
                </li>
            </ul>
                
            <ul class="navbar-nav ms-auto">
                <?php
                if(!$logged_in)
                echo '
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>';
                else
                echo '
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"> ' . $username . ' </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul> ';                
                ?>
            </ul>
        </div>
    </div>
</nav>
