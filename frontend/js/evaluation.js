//Graph settings
const ctx = document.getElementById('valueOverTimeChart').getContext('2d');
const valueOverTimeChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [], // Add your dates here
        datasets: [{
            label: 'Value Over Time',
            data: [], // Add your values here
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
                    text: 'Date'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Value'
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
      // Select the container where the data will be displayed
      const container = document.getElementById("evaluation");

      // Clear the container before adding new data
      container.innerHTML = "";

      // Function to create a formatted row
      const createRow = (label, value) => {
        const row = document.createElement("p");
        row.classList.add("fs-5", "text-left", "my-2");
        row.innerHTML = `<strong>${label}:</strong> ${value}`;
        return row;
      };
        
      // Helper function to format float values to 3 decimal places
      const formatFloat = (num) => parseFloat(num).toFixed(3);

      // Append formatted rows to the container
      container.appendChild(createRow("Evaluation", `${formatFloat(data.evaluation)}%`));
      container.appendChild(createRow("USD Wallet", `$${formatFloat(data.usd_wallet)}`));
      container.appendChild(createRow("ETH Wallet", `${formatFloat(data.eth_wallet)} ETH`));
      container.appendChild(createRow("Evaluation Price", `$${formatFloat(data.eval_price)}`));
      container.appendChild(createRow("Final Balance", `$${formatFloat(data.final_balance)}`));
      container.appendChild(createRow("Buys", data.buys));
      container.appendChild(createRow("Sells", data.sells));

      //feed graph the data
      grahpData = data.graphData;
      valueOverTimeChart.data.labels = grahpData.map(item => item.date);
      valueOverTimeChart.data.datasets[0].data = grahpData.map(item => item.value);
      valueOverTimeChart.update();
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