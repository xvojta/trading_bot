<?php
require_once(__DIR__  . '/header.php');
?>

<div class="col-md-6">
    <h2 class="text-center">Log in</h2>

    <form id="loginForm" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="string" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>


    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        echo "Logging in..."  . "<br>";
        $uname = $_POST['username'];
        $passwd = $_POST['password'];
        echo "Username: " . $uname . "<br> Password: " . $passwd . "<br>";
        if(isset($uname) && isset($passwd))
        {
            $login = login($uname, $passwd);
            echo $login['message'];
            if($login['succsess'])
            {
                echo "
                    <script>
                        // Redirect to the main page after 1 seconds (1000 milliseconds)
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