<?php
require_once(__DIR__  . '/header.php');
?>

<div class="col-md-6 mb-3">
    <!-- Table with all trades -->
    <button type="button" class="btn btn-secondary" id="reload">
        <i class="bi bi-arrow-clockwise"></i> <?php echo __('reload'); ?>
    </button>
    <button type="button" class="btn btn-secondary" id="clear_history">
        <?php echo __('clear_history'); ?>
    </button>
    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th><?php echo __('time'); ?></th>
                    <th><?php echo __('action'); ?></th>
                    <th><?php echo __('price'); ?></th>
                    <th><?php echo __('amount'); ?></th>
                </tr>              
            </thead>
            <tbody id="tradeHistory"></tbody>
        </table>
    </div>
</div>


<script src="js/trade_history.js"></script>

<?php
require_once(__DIR__  . '/footer.php');
?>