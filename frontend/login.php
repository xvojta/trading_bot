<?php
require_once(__DIR__  . '/header.php');
?>

<div class="col-md-6">
    <h2 class="text-center"><?php echo __('login'); ?></h2>

    <form id="loginForm" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label"><?php echo __('username_label'); ?></label>
            <input type="string" maxlength="32" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"><?php echo __('password_label'); ?></label>
            <input type="password" maxlength="16" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><?php echo __('login'); ?></button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        echo __('logging_in') . "<br>";
        $uname = $_POST['username'];
        $passwd = $_POST['password'];
        if (isset($uname) && isset($passwd)) 
        {
            $login = login($uname, $passwd);
            echo $login['message'];
            if ($login['succsess']) 
            {
                echo "
                    <script>
                        // Redirect to the main page after 1 second (1000 milliseconds)
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 1000);
                    </script>
                ";
            }
        }
    }
    ?>

</div>

<?php
require_once(__DIR__ . '/footer.php');
?>