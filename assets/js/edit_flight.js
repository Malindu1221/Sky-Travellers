document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editFlightForm');
    const flightId = new URLSearchParams(window.location.search).get('id');

    if (flightId) {
        fetchFlightDetails(flightId);
        fetchAirlines();
    } else {
        alert('No flight ID provided');
        window.location.href = '/skytravellers/pages/admin/flight_management.php';
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateFlight();
    });

    // Function to fetch flight details based on the flight ID
    async function fetchFlightDetails(id) {
        try {
            const response = await fetch(`/skytravellers/api/edit_flight.php?id=${id}`);
            const data = await response.json();
            if (data.status === 'success') {
                populateForm(data.data);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching flight details:', error);
            alert('Failed to load flight details. Redirecting to flight management.');
            window.location.href = '/skytravellers/pages/admin/flight_management.php';
        }
    }

     // Function to fetch available airlines and populate the airline dropdown
    async function fetchAirlines() {
        try {
            const response = await fetch('/skytravellers/api/get_airlines.php');
            const data = await response.json();
            if (data.status === 'success') {
                populateAirlineSelect(data.data);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching airlines:', error);
            alert('Failed to load airlines. Please try again.');
        }
    }

    // Function to populate the form fields with the flight data
    function populateForm(flight) {
        document.getElementById('flightId').value = flight.id;
        document.getElementById('airlineId').value = flight.airline_id;
        document.getElementById('flightNumber').value = flight.flight_number;
        document.getElementById('economySeats').value = flight.economy_seats;
        document.getElementById('firstSeats').value = flight.first_seats;
        document.getElementById('businessSeats').value = flight.business_seats;
    }

     // Function to populate the airline dropdown with airline data
    function populateAirlineSelect(airlines) {
        const select = document.getElementById('airlineId');
        airlines.forEach(airline => {
            const option = document.createElement('option');
            option.value = airline.id;
            option.textContent = airline.name;
            select.appendChild(option);
        });
    }

    // Function to handle the flight update via a POST request
    async function updateFlight() {
        const formData = new FormData(form);
        try {
            const response = await fetch('/skytravellers/api/edit_flight.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.status === 'success') {
                alert('Flight updated successfully');
                window.location.href = '/skytravellers/pages/admin/flight_management.php';
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error updating flight:', error);
            alert('Failed to update flight: ' + error.message);
        }
    }
});