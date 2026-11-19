document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('flightTableBody');
    const addNewFlightBtn = document.getElementById('addNewFlight');
    const searchInput = document.getElementById('searchFlight');

    // Fetch flights from server
    async function fetchFlights() {
        try {
            const response = await fetch('/skytravellers/api/get_flights.php');
            const data = await response.json();
            if (data.status === 'success') {
                populateTable(data.data);
            } else {
                throw new Error(data.message || 'Failed to fetch flights');
            }
        } catch (error) {
            console.error('Error fetching flights:', error);
            alert('Failed to load flights. Please try refreshing the page.');
        }
    }

    // Populate table
    function populateTable(flights) {
        tableBody.innerHTML = '';
        flights.forEach(flight => {
            const row = `
                <tr>
                    <td>${flight.id}</td>
                    <td>${flight.airline_name}</td>
                    <td>${flight.flight_number}</td>
                    <td>${flight.economy_seats}</td>
                    <td>${flight.first_seats}</td>
                    <td>${flight.business_seats}</td>
                    <td>${parseInt(flight.economy_seats) + parseInt(flight.first_seats) + parseInt(flight.business_seats)}</td>
                    <td>
                        <button class="btn btn-edit" data-id="${flight.id}">EDIT</button>
                        <button class="btn btn-delete" data-id="${flight.id}">DELETE</button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Add New Flight
    addNewFlightBtn.addEventListener('click', function() {
        window.location.href = '/skytravellers/pages/admin/add_new_flight.php';
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

    // Edit and Delete functionality
    tableBody.addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-edit')) {
            const flightId = e.target.getAttribute('data-id');
            window.location.href = `/skytravellers/pages/admin/edit_flight.php?id=${flightId}`;
        } else if (e.target.classList.contains('btn-delete')) {
            const flightId = e.target.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this flight?')) {
                try {
                    const response = await fetch(`/skytravellers/api/delete_flight.php?id=${flightId}`, {
                        method: 'DELETE'
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('Flight deleted successfully');
                        fetchFlights(); // Refresh the table
                    } else {
                        throw new Error(result.message || 'Failed to delete flight');
                    }
                } catch (error) {
                    console.error('Error deleting flight:', error);
                    alert('Failed to delete flight: ' + error.message);
                }
            }
        }
    });

    // Initial fetch
    fetchFlights();
});