<?php
require_once(__DIR__  . '/header.php');
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>



<!--Right column with model selection and testing-->
<div class="col-md-6 mb-3">
  <h2 class="text-center">Control Trading Models</h2>

  <label for="modelSelect" class="form-label">Select model</label>
  <select class="form-select mb-3" id="modelSelect" onchange="get_model_status(this.value)"></select>
  <div class="row">
    <div class="col-md-6">
      <button class="btn btn-success w-100" id="startModel">Start Model</button>
    </div>
    <div class="col-md-6">
      <button class="btn btn-danger w-100 mb-3" id="stopModel">Stop Model</button>
    </div>
  </div>

  <div style="height: 70px;"></div>
  <h3 class="text-center">Evaluate Model</h3>

  <div class="row">
    <label for="balance" class="form-label">USD fictional account balance</label>
    <input type="number" class="form-control" id="balance" value="500" required>
    <div style="height: 10px;"></div>
    <button class="btn btn-warning w-100" id="evaluateModel">Evaluate</button>
    <div style="height: 10px;"></div>
    <div id="evaluation"></div>
  </div>
</div>

<!--Graph-->
<div class="mt-4 d-flex justify-content-center">
  <canvas id="valueOverTimeChart" width="800" height="400"></canvas>
</div>

<script>
  function updateFloatValue(val) {
      document.getElementById('amount').value = val;
  }
</script>

<script src="js/evaluation.js"></script>

<?php
require_once(__DIR__  . '/footer.php');
?>