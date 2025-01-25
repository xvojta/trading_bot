<?php
require_once(__DIR__  . '/header.php');

if($logged_in)
    echo '
        <script>
            window.location.href = "models.php";
        </script>
    ';
?>

<style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .title {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }
        .button-container {
            text-align: center;
        }
        .btn-custom {
            width: 200px;
            margin: 0.5rem;
            font-size: 1.2rem;
            padding: 0.75rem;
        }
</style>

<div class="container text-center">
    <h1 class="title">TRADING BOT</h1>
    <div class="button-container">
        <a href="login.php" class="btn btn-primary btn-custom">Log In</a>
        <a href="register.php" class="btn btn-secondary btn-custom">Register</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
require_once(__DIR__  . '/footer.php');
?>