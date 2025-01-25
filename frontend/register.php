<?php
require_once(__DIR__  . '/header.php');
?>

<div class="col-md-6">
    <h2 class="text-center"><?php echo __('registration'); ?></h2>

    <form id="registerForm" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label"><?php echo __('username_label'); ?></label>
            <input type="string" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"><?php echo __('password_label'); ?></label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><?php echo __('register'); ?></button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        echo __('registering') . "<br>";
        $uname = $_POST['username'];
        $passwd = $_POST['password'];
        echo __('username_colon') . $uname . "<br>" . __('password_colon') . $passwd . "<br>";

        if (isset($uname) && isset($passwd)) 
        {
            $register = register($uname, $passwd);
            echo $register['message'];
            if ($register['succsess']) 
            {
                echo "
                    <script>
                        // Redirect to the main page after 1 second (1000 milliseconds)
                        setTimeout(function() {
                            window.location.href = 'models.php';
                        }, 1000);
                    </script>
                ";
            }
        }
    }
    ?>
</div>

<?php
require_once(__DIR__  . '/footer.php');
?>