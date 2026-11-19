document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('flightTableBody');
    const scheduleNewFlightBtn = document.getElementById('scheduleNewFlight');
    const searchInput = document.getElementById('searchFlight');
    const editModal = document.getElementById('editFlightModal');
    const editForm = document.getElementById('editFlightForm');

    // Fetch scheduled flights from server
    async function fetchScheduledFlights() {
        try {
            const response = await fetch('/skytravellers/api/get_scheduled_flights.php');
            const data = await response.json();
            if (data.status === 'success') {
                populateTable(data.data);
            } else {
                throw new Error(data.message || 'Failed to fetch scheduled flights');
            }
        } catch (error) {
            console.error('Error fetching scheduled flights:', error);
            alert('Failed to load scheduled flights. Please try refreshing the page.');
        }
    }

    // Populate table
    function populateTable(flights) {
        tableBody.innerHTML = '';
        flights.forEach((flight, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${flight.airline_name}</td>
                    <td>${flight.flight_number}</td>
                    <td>${flight.source}</td>
                    <td>${flight.destination}</td>
                    <td>${flight.arrival_datetime}</td>
                    <td>${flight.departure_datetime}</td>
                    <td>${flight.economy_cost}</td>
                    <td>${flight.business_cost}</td>
                    <td>${flight.first_class_cost}</td>
                    <td>
                        <button class="btn btn-edit" data-id="${flight.id}">Edit</button>
                        <button class="btn btn-remove" data-id="${flight.id}">Remove</button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Schedule New Flight
    scheduleNewFlightBtn.addEventListener('click', function() {
        window.location.href = '/skytravellers/pages/admin/schedule_new_flight.php';
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = tableBody.getElementsByTagName('tr');
        Array.from(rows).forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Edit and Remove functionality
    tableBody.addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-edit')) {
            const flightId = e.target.getAttribute('data-id');
            try {
                const response = await fetch(`/skytravellers/api/edit_scheduled_flight.php?id=${flightId}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const result = await response.json();
                console.log('Fetched flight data:', result); // Add this for debugging
                
                if (result.status === 'success') {
                    populateEditForm(result.data);
                    editModal.style.display = 'block';
                } else {
                    throw new Error(result.message || 'Failed to fetch flight details');
                }
            } catch (error) {
                console.error('Error fetching flight details:', error);
                alert('Failed to fetch flight details: ' + error.message);
            }
        } else if (e.target.classList.contains('btn-remove')) {
            const flightId = e.target.getAttribute('data-id');
            if (confirm('Are you sure you want to remove this scheduled flight?')) {
                try {
                    const formData = new FormData();
                    formData.append('flight_id', flightId);

                    const response = await fetch(`/skytravellers/api/remove_scheduled_flight.php?id=${flightId}`, {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        alert('Scheduled flight removed successfully');
                        window.location.href = '/skytravellers/pages/admin/flight_schedule.php'; // Refresh the table
                    } else {
                        throw new Error(result.message || 'Failed to remove scheduled flight');
                    }
                } catch (error) {
                    console.error('Error removing scheduled flight:', error);
                    alert('Failed to remove scheduled flight: ' + error.message);
                }
            }
        }
    });

    // Populate edit form
    function populateEditForm(flight) {
        console.log('Populating form with flight data:', flight);
        
        document.getElementById('edit_flight_id').value = flight.id;
        document.getElementById('edit_airline_name').value = flight.name;
        document.getElementById('edit_flight_number').value = flight.flight_number;
        document.getElementById('edit_source').value = flight.source;
        document.getElementById('edit_destination').value = flight.destination;
        
        // The dates are already in the correct format (YYYY-MM-DDTHH:mm)
        // thanks to our backend DATE_FORMAT
        document.getElementById('edit_arrival_datetime').value = flight.arrival_datetime;
        document.getElementById('edit_departure_datetime').value = flight.departure_datetime;
        
        document.getElementById('edit_economy_cost').value = flight.economy_cost;
        document.getElementById('edit_business_cost').value = flight.business_cost;
        document.getElementById('edit_first_class_cost').value = flight.first_class_cost;
    }
    
    function formatDateForMySQL(dateTimeLocal) {
        if (!dateTimeLocal) return null;
        
        // Replace 'T' with space for MySQL format
        return dateTimeLocal.replace('T', ' ');
    }
    
    function updateFlight() {
        const form = document.getElementById('editFlightForm');
        const formData = new FormData(form);
        
        // Format date fields before sending to the server
        const departureDateTime = formatDateForMySQL(form.elements['departure_datetime'].value);
        const arrivalDateTime = formatDateForMySQL(form.elements['arrival_datetime'].value);
        
        formData.set('departure_datetime', departureDateTime);
        formData.set('arrival_datetime', arrivalDateTime);
        
        // Add the airline name and flight number from the form
        formData.set('name', form.elements['airline_name'].value);
        formData.set('flight_number', form.elements['flight_number'].value);
    
        fetch('/skytravellers/api/edit_scheduled_flight.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Flight updated successfully!');
                document.getElementById('editFlightModal').style.display = 'none';
                fetchScheduledFlights();
            } else {
                alert('Error updating flight: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }

    // Initial fetch
    fetchScheduledFlights();
});