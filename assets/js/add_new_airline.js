document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addAirlineForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const airlineName = document.getElementById('airlineName').value.trim();
        
        if (airlineName === '') {
            alert('Please enter an airline name.');
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('airlineName', airlineName);

            // Log what is being sent
            console.log('Sending FormData:', airlineName);
            
            const response = await fetch('/skytravellers/api/add_airline.php', {
                method: 'POST',
                body: formData // Use FormData directly
            });
            
            const data = await response.json();
            console.log('Server Response:', data);
            
            if (data.success) {
                alert('Airline added successfully!');
                form.reset();
                // Optional: Redirect to airline management page
                // window.location.href = '/skytravellers/pages/admin/airline_management.php';
            } else {
                console.error('Server response:', data);
                alert(`Error: ${data.message || 'Unknown error occurred'}`);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('An error occurred while adding the airline. Check the console for details.');
        }
    });
});
