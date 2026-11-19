document.addEventListener('DOMContentLoaded', function() {
    // Fetch airlines for the dropdown
    fetchAirlines();

    // Add event listeners for seat inputs
    const seatInputs = ['economySeats', 'businessSeats', 'firstSeats'];
    seatInputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', calculateTotalSeats);
    });

    // Form submission handler
    document.getElementById('addFlightForm').addEventListener('submit', handleFormSubmit);
});

async function fetchAirlines() {
    try {
        const response = await fetch('/skytravellers/api/get_airlines.php');
        const data = await response.json();
        
        if (data.status === 'success') {
            const airlineSelect = document.getElementById('airline');
            data.data.forEach(airline => {
                const option = document.createElement('option');
                option.value = airline.id;
                option.textContent = airline.name;
                airlineSelect.appendChild(option);
            });
        } else {
            throw new Error(data.message || 'Failed to fetch airlines');
        }
    } catch (error) {
        console.error('Error fetching airlines:', error);
        alert('Failed to load airlines. Please try refreshing the page.');
    }
}
// Function to calculate the total number of seats
function calculateTotalSeats() {
    const economySeats = parseInt(document.getElementById('economySeats').value) || 0;
    const businessSeats = parseInt(document.getElementById('businessSeats').value) || 0;
    const firstSeats = parseInt(document.getElementById('firstSeats').value) || 0;
    const totalSeats = economySeats + businessSeats + firstSeats;
    document.getElementById('totalSeats').value = totalSeats;
}
// Function to handle form submission via AJAX
async function handleFormSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    try {
        const response = await fetch('/skytravellers/api/add_flight.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            alert('Flight added successfully!');
            event.target.reset();
            calculateTotalSeats(); // Reset total seats display
        } else {
            throw new Error(result.message || 'Unknown error occurred');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        alert('Error adding flight: ' + error.message);
    }
}