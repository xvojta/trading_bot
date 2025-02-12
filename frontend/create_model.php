<?php
require_once(__DIR__  . '/header.php');
?>

<div class="col-md-6">
    <h2 class="text-center"><?php echo __('create_model_title'); ?></h2>

    <form id="tradeForm">
        <div class="mb-3">
            <label for="name" class="form-label"><?php echo __('model_name'); ?></label>
            <input type="string" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="dip" class="form-label"><?php echo __('buy_dip'); ?>
                <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="<?php echo __('buy_dip_info');?>"></i>
            </label>
            <input type="number" class="form-control" id="dip" required>
        </div>
        <div class="mb-3">
            <label for="sell" class="form-label"><?php echo __('sell_target'); ?>
                <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="<?php echo __('sell_target_info');?>"></i>
            </label>
            <input type="number" class="form-control" id="sell" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label"><?php echo __('volume_per_trade'); ?>
                <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="<?php echo __('volume_per_trade_info');?>"></i>
            </label>
            <input type="number" class="form-control" id="amount" value="500" required>
            <input type="range" class="form-range" id="floatRange" min="0" max="1000" step="1" value="500" oninput="updateFloatValue(this.value)">
        </div>
        <button type="submit" class="btn btn-primary w-100"><?php echo __('save_model'); ?></button>
    </form>
</div>

<script src='js/settings.js'></script>

<?php
require_once(__DIR__  . '/footer.php');
?>
