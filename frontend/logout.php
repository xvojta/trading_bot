<?php
require_once(__DIR__  . '/header.php');

$_SESSION[$INDEX_LOGGED_IN] = false;
$logged_in = false;
?>

<div class="col-md-6">
    <h2 class="text-center"><?php echo __('logging_out'); ?></h2>
</div>

<script>
    // Redirect to the main page after 2 seconds (2000 milliseconds)
    setTimeout(function() {
        window.location.href = 'index.php';
    }, 2000);
</script>

<?php
require_once(__DIR__  . '/footer.php');
?>