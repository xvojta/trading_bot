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

    //fetchModels();
  });