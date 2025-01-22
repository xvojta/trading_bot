function fetchModels() {
  fetch('../backend/controllers/get_models.php') // Path to your PHP script
    .then(response => response.json())
    .then(data => {
      const modelSelect = document.getElementById('modelSelect');
      modelSelect.innerHTML = ''; // Clear the table before updating

      if (data.length > 0) {
        // Loop through the trade history and populate the table
        data.forEach(model => {
          const option = document.createElement('option');
          option.value = model.id;
          option.innerText = model.name;
          // Append the row to the table
          modelSelect.appendChild(option);
        });
        get_model_status(data[0].id); //get the selected model status
      } else {
        // If no data, show a message in the status div
        document.getElementById('status').textContent = 'No models available.';
      }
    })
    .catch(error => {
      console.error('Error fetching models:', error);
      document.getElementById('status').textContent = 'Error loading models.';
    });
}

function start_model()
{
  fetch('../backend/controllers/start_model.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: document.getElementById("modelSelect").value
    })
  }).then(response => response.json())
  .then(data => {
    if(data.success)
    {
      document.getElementById("startModel").disabled = true;
      document.getElementById("stopModel").disabled = false;
    }
  })
}

function stop_model()
{
  fetch('../backend/controllers/stop_model.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: document.getElementById("modelSelect").value
    })
  }).then(response => response.json())
  .then(data => {
    if(data.success)
    {
      document.getElementById("startModel").disabled = false;
      document.getElementById("stopModel").disabled = true;
    }
  })
}

function evaluate_model()
{
  this.classList.add("btn-disabled");
  document.getElementById("evaluation").innerHTML = 
  '<div class="progress"><div class="progress-bar evaluation-progress" role="progressbar"></div></div>';

  fetch('../backend/controllers/evaluate_model.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: document.getElementById("modelSelect").value,
      balance: document.getElementById("balance").value
    })
  }).then(response => response.json())
  .then(data => {
    if(data.success)
    {
      document.getElementById("evaluation").innerHTML = '<p class="text-center" id="evaluation">'+ data.evaluation +'</p>';
    }
    this.classList.remove("btn-disabled");
  })
}

function get_model_status(modelId)
{
  fetch('../backend/controllers/model_status.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: modelId
    })
  }).then(response => response.json())
  .then(data => {
    if(data.success)
    {
      running = Boolean(data.running[0].running);
      document.getElementById("startModel").disabled = running;
      document.getElementById("stopModel").disabled = !running;
    }
  })
}

// Call the function on page load
window.addEventListener("load", fetchModels);
document.getElementById("startModel").addEventListener("click", start_model);
document.getElementById("stopModel").addEventListener("click", stop_model);
document.getElementById("evaluateModel").addEventListener("click", evaluate_model);

// Optionally, you can refresh trade history every few seconds:
// setInterval(fetchTradeHistory, 30000); // Refresh every 30 seconds
