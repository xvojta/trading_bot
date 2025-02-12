        <!--Response from backend-->
        <div id="status" class="mt-3"></div>
    </div>
</div>

<?php 
    //Session start
    require_once(__DIR__  . '/../backend/controllers/account_manager.php');
    //Localization load
    require_once(__DIR__ . '/../backend/controllers/localization.php');
?>

<footer class="py-3 my-4 border-top text-light">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <p class="col-md-4 mb-0">&copy; <?php echo date("Y"); ?> Trading Bot. 
        <a href="https://github.com/xvojta/trading_bot" target="_blank" rel="noopener noreferrer">https://github.com/xvojta/trading_bot</a>
    </p>

    <ul class="nav col-md-4 justify-content-center align-items-center text-secondary">
      <?php require(__DIR__ . '/menu_items.php');?>
    </ul>

    <form method="POST">
        <select id="languageSelector" name="language" class="form-select d-inline w-auto" onchange="this.form.submit()">
            <option value="en" <?php if ($_SESSION['lang'] == 'en') echo 'selected'; ?>>English</option>
            <option value="cs" <?php if ($_SESSION['lang'] == 'cs') echo 'selected'; ?>>Čeština</option>
        </select>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["language"])) {
            $selectedLang = $_POST["language"];
            $_SESSION['lang'] = $selectedLang;
            //hard reload
            echo "<script>window.location.href = window.location.href;</script>";
        }
    ?>
  </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

</body>
</html>