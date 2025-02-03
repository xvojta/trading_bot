// Load translations from PHP backend
fetch('../backend/controllers/get_translations.php')
  .then(response => response.json())
  .then(data => {
    localStorage.setItem("translations", JSON.stringify(data));
  });

// Function to retrieve localized text from `localStorage`
function translate(key) {
  const translations = JSON.parse(localStorage.getItem("translations")) || {};
  return translations[key] || key;
}

// Graph settings
const ctx = document.getElementById('valueOverTimeChart').getContext('2d');
const valueOverTimeChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: translate('value_over_time'),
            data: [],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'day'
                },
                title: {
                    display: true,
                    text: translate('date')
                }
            },
            y: {
                title: {
                    display: true,
                    text: translate('value')
                }
            }
        }
    }
});

function fetchModels() {
  fetch('../backend/controllers/get_models.php') // Path to your PHP script
    .then(response => response.json())
    .then(data => {
      const modelSelect = document.getElementById('modelSelect');
      modelSelect.innerHTML = ''; // Clear the dropdown before updating

      if (data.length > 0) {
        data.forEach(model => {
          const option = document.createElement('option');
          option.value = model.id;
          option.innerText = model.name;
          modelSelect.appendChild(option);
        });
        get_model_status(data[0].id); // Get the selected model status
      } else {
        document.getElementById('status').textContent = translate('no_models_available');
      }
    })
    .catch(error => {
      console.error('Error fetching models:', error);
      document.getElementById('status').textContent = translate('error_loading_models');
    });
}

function start_model() {
  fetch('../backend/controllers/start_model.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: document.getElementById("modelSelect").value })
  }).then(response => response.json())
  .then(data => {
    if(data.success) {
      document.getElementById("startModel").disabled = true;
      document.getElementById("stopModel").disabled = false;
    }
  });
}

function stop_model() {
  fetch('../backend/controllers/stop_model.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: document.getElementById("modelSelect").value })
  }).then(response => response.json())
  .then(data => {
    if(data.success) {
      document.getElementById("startModel").disabled = false;
      document.getElementById("stopModel").disabled = true;
    }
  });
}

function evaluate_model() {
  this.classList.add("btn-disabled");
  document.getElementById("evaluation").innerHTML = 
    '<div class="progress"><div class="progress-bar evaluation-progress" role="progressbar"></div></div>';

  fetch('../backend/controllers/evaluate_model.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      id: document.getElementById("modelSelect").value,
      balance: document.getElementById("balance").value
    })
  }).then(response => response.json())
  .then(data => {
    if(data.success) {
      const container = document.getElementById("evaluation");
      container.innerHTML = "";

      const createRow = (label, value) => {
        const row = document.createElement("p");
        row.classList.add("fs-5", "text-left", "my-2");
        row.innerHTML = `<strong>${translate(label)}:</strong> ${value}`;
        return row;
      };
        
      const formatFloat = (num) => parseFloat(num).toFixed(3);

      container.appendChild(createRow("evaluation", `${formatFloat(data.evaluation)}%`));
      container.appendChild(createRow("usd_wallet", `$${formatFloat(data.usd_wallet)}`));
      container.appendChild(createRow("eth_wallet", `${formatFloat(data.eth_wallet)} ETH`));
      container.appendChild(createRow("evaluation_price", `$${formatFloat(data.eval_price)}`));
      container.appendChild(createRow("final_balance", `$${formatFloat(data.final_balance)}`));
      container.appendChild(createRow("buys", data.buys));
      container.appendChild(createRow("sells", data.sells));

      // Update the chart
      graphData = data.graphData;
      valueOverTimeChart.data.labels = graphData.map(item => item.date);
      valueOverTimeChart.data.datasets[0].data = graphData.map(item => item.value);
      valueOverTimeChart.update();
    }
    this.classList.remove("btn-disabled");
  });
}

function get_model_status(modelId) {
  fetch('../backend/controllers/model_status.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: modelId })
  }).then(response => response.json())
  .then(data => {
    if(data.success) {
      running = Boolean(data.running[0].running);
      document.getElementById("startModel").disabled = running;
      document.getElementById("stopModel").disabled = !running;
    }
  });
}

// Call functions on page load
window.addEventListener("load", fetchModels);
document.getElementById("startModel").addEventListener("click", start_model);
document.getElementById("stopModel").addEventListener("click", stop_model);
document.getElementById("evaluateModel").addEventListener("click", evaluate_model);
