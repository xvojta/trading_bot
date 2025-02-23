<?php
require_once(__DIR__  . '/header.php');

if($logged_in)
    echo '
        <script>
            window.location.href = "about.php";
        </script>
    ';
?>

<style>
        .mainText {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
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

<div class="mainText">
    <div class="container text-center">
        <h1 class="title">TRADING BOT</h1>
        <div class="button-container">
            <a href="login.php" class="btn btn-primary btn-custom"><?php echo __('login'); ?></a>
            <a href="register.php" class="btn btn-secondary btn-custom"><?php echo __('register'); ?></a>
        </div>
    </div>
</div>

<?php
require_once(__DIR__  . '/footer.php');
?>