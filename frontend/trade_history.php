<?php
require_once(__DIR__  . '/header.php');
?>

<div class="col-md-6 mb-3">
<!--Table with all trades-->
    <button type="button" class="btn btn-secondary" id="reload">
    <i class="bi bi-arrow-clockwise"></i>
    </button>
    <button type="button" class="btn btn-secondary" id="clear_history"> Clear history </button>
    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
            <tr>
                <th>Time</th>
                <th>Action</th>
                <th>Price</th>
                <th>Amount</th>
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