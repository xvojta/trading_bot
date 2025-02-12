<?php
require_once(__DIR__  . '/header.php');
?>

<div class="container py-5">
    <h1 class="text-center"><?php echo __('trading_bot_platform'); ?></h1>
    
    <section class="mt-4">
        <h2><?php echo __('overview'); ?></h2>
        <p><?php echo __('overview_text'); ?></p>
    </section>

    <section class="mt-4">
        <h2><?php echo __('how_it_works'); ?></h2>
        <ul>
            <li><strong><?php echo __('create_account'); ?></strong> – <?php echo __('create_account_text'); ?></li>
            <li><strong><?php echo __('create_model'); ?></strong> – <?php echo __('create_model_text'); ?></li>
            <li><strong><?php echo __('evaluate_model'); ?></strong> – <?php echo __('evaluate_model_text'); ?></li>
        </ul>
    </section>

    <section class="mt-4">
        <h2><?php echo __('model_settings'); ?></h2>
        <ul>
            <li><strong><?php echo __('volume_per_trade'); ?></strong>: <?php echo __('volume_per_trade_info'); ?></li>
            <li><strong><?php echo __('buy_dip'); ?></strong>: <?php echo __('buy_dip_info'); ?></li>
            <li><strong><?php echo __('sell_target'); ?></strong>: <?php echo __('sell_target_info'); ?></li>
        </ul>
    </section>

    <section class="mt-4">
        <h2><?php echo __('evaluation_process'); ?></h2>
        <p><?php echo __('evaluation_process_text'); ?></p>
    </section>

    <section class="mt-4">
        <h2><?php echo __('future_implementations'); ?></h2>
        <p><?php echo __('future_implementations_text'); ?></p>
        <ul>
            <li><?php echo __('future_point_1'); ?> 
                <a href="models.php"><strong><?php echo __('models'); ?></strong></a>).
            </li>
            <li><?php echo __('future_point_2'); ?></li>
            <li><?php echo __('future_point_3'); ?></li>
            <li><?php echo __('future_point_4'); ?>
                <a href="trade_history.php"><strong><?php echo __('trade_history'); ?></strong></a>.
            </li>
            </ul>
    </section>
</div>

<?php
require_once(__DIR__  . '/footer.php');
?>