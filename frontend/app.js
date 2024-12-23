document.getElementById("tradeForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    const name = document.getElementById("name").value;
    const dip = document.getElementById("dip").value;
    const sell = document.getElementById("sell").value;
    const amount = document.getElementById("amount").value;
  
    fetch('../backend/controllers/start_bot.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        name: name,
        dip: dip,
        sell: sell,
        amount: amount
      })
    }).then(response => response.json())
    .then(data => {
      document.getElementById("status").innerText = data.message;
    }).catch(error => {
      console.error('Error:', error);
      document.getElementById("status").innerText = 'Error starting bot.';
    });

    fetchModels();
  });
  
// Function to fetch and display trade history
function fetchTradeHistory() {
  fetch('../backend/controllers/get_trade_history.php') // Path to your PHP script
    .then(response => response.json())
    .then(data => {
      const tradeHistory = document.getElementById('tradeHistory');
      tradeHistory.innerHTML = ''; // Clear the table before updating

      if (data.length > 0) {
        // Loop through the trade history and populate the table
        data.forEach(trade => {
          const row = document.createElement('tr');
          
          // Create table cells for each trade attribute
          const timeCell = document.createElement('td');
          timeCell.textContent = trade.timestamp;

          const actionCell = document.createElement('td');
          actionCell.textContent = trade.action;

          const priceCell = document.createElement('td');
          priceCell.textContent = trade.price;

          const amountCell = document.createElement('td');
          amountCell.textContent = trade.amount;

          // Append the cells to the row
          row.appendChild(timeCell);
          row.appendChild(actionCell);
          row.appendChild(priceCell);
          row.appendChild(amountCell);

          // Append the row to the table
          tradeHistory.appendChild(row);
        });
      } else {
        // If no data, show a message in the status div
        document.getElementById('status').textContent = 'No trade history available.';
      }
    })
    .catch(error => {
      console.error('Error fetching trade history:', error);
      document.getElementById('status').textContent = 'Error loading trade history.';
    });
}

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
  fetch('../backend/controllers/evaluate_model.php', {
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
      document.getElementById("evaluation").innerText = data.evaluation;
    }
  })
}

function clear_trade_history()
{
  fetch('../backend/controllers/clear_trade_history.php')
  .then(response => response.json())
  .then(data => {
    if(!data.success)
    {
      console.error("Couldn't clear history");
    }
  })

  fetchTradeHistory();
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
window.addEventListener("load", fetchTradeHistory);
window.addEventListener("load", fetchModels);
document.getElementById("clear_history").addEventListener("click", clear_trade_history);
document.getElementById("startModel").addEventListener("click", start_model);
document.getElementById("stopModel").addEventListener("click", stop_model);
document.getElementById("evaluateModel").addEventListener("click", evaluate_model);
document.getElementById("reload").addEventListener("click", fetchTradeHistory);

// Optionally, you can refresh trade history every few seconds:
// setInterval(fetchTradeHistory, 30000); // Refresh every 30 seconds
