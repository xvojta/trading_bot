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

window.addEventListener("load", fetchTradeHistory);
document.getElementById("clear_history").addEventListener("click", clear_trade_history);
document.getElementById("reload").addEventListener("click", fetchTradeHistory);
