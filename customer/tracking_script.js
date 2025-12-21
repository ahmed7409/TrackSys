
function trackOrder() {
   
    const trackingId = document.getElementById('trackingId').value;
    const resultDiv = document.getElementById('tracking-result');

    
    if (!trackingId) {
        alert('Please enter a tracking ID.');
        return;
    }

    
    fetch(`../api/track.php?id=${trackingId}`)
      .then(response => {
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); 
      })
      .then(data => {
          
          if(data.success) {
              updateUI(data); 
              resultDiv.style.display = 'block'; 
          } else {
              
              alert(data.message);
              resultDiv.style.display = 'none'; 
          }
      })
      .catch(error => {
          
          console.error('Error fetching tracking data:', error);
          alert('Koi technical masla aaya hai. Thori der baad koshar karein.');
          resultDiv.style.display = 'none';
      });
}

/**
 * 
 * @param {object} data 
 */
function updateUI(data) {
    
    const customerNameEl = document.getElementById('customer-name');
    const orderStatusTextEl = document.getElementById('order-status-text');
    const specialStatusEl = document.getElementById('special-status');

    
    customerNameEl.textContent = `Customer: ${data.customerName}`;
    
    
    const allSteps = document.querySelectorAll('.step');
    allSteps.forEach(step => step.classList.remove('active'));

    
    specialStatusEl.style.display = 'none';
    orderStatusTextEl.style.display = 'block';

    
    const status = data.status.toLowerCase();

    
    if (status === 'canceled') {
        specialStatusEl.textContent = 'Order Canceled';
        specialStatusEl.className = 'special-status canceled';
        specialStatusEl.style.display = 'block';
        orderStatusTextEl.style.display = 'none';
    } 
    
    else if (status === 'returned') {
        specialStatusEl.textContent = 'Order Returned';
        specialStatusEl.className = 'special-status returned';
        specialStatusEl.style.display = 'block';
        orderStatusTextEl.style.display = 'none';
    } 
    
    else {
        
        orderStatusTextEl.textContent = `Current Status: ${data.status}`;
        
        
        const steps = ['processing', 'packed', 'on-the-way', 'delivered'];
        
        
        const currentStepIndex = steps.indexOf(status);

        
        if (currentStepIndex > -1) {
            
            for (let i = 0; i <= currentStepIndex; i++) {
                document.getElementById(`step-${steps[i]}`).classList.add('active');
            }
        }
    }
}