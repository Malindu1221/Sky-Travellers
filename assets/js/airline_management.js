document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('airlineTableBody');
    const searchInput = document.getElementById('searchAirline');
    let allAirlines = [];

    // Fetch airlines from API
    async function fetchAirlines() {
        try {
            const response = await fetch('/skytravellers/api/get_airlines.php');
            const data = await response.json();
            if (data.status === 'success') {
                allAirlines = data.data;
                populateTable(allAirlines);
            } else {
                showError('Failed to load airlines');
            }
        } catch (error) {
            showError('An error occurred while fetching airlines');
        }
    }

    // Populate table
    function populateTable(airlines) {
        tableBody.innerHTML = '';
        airlines.forEach(airline => {
            const row = `
                <tr>
                    <td>${airline.id}</td>
                    <td>${airline.name}</td>
                    <td>${airline.flight_count}</td>
                    <td>
                        <button class="btn btn-add-flight" data-id="${airline.id}">ADD FLIGHT</button>
                        <button class="btn btn-edit" data-id="${airline.id}">EDIT</button>
                        <button class="btn btn-delete" data-id="${airline.id}">DELETE</button>
                    </td>
                    <td>${formatDate(airline.added_date)}</td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // Show error message
    function showError(message) {
        // Implement error display logic here
        console.error(message);
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredAirlines = allAirlines.filter(airline =>
            airline.name.toLowerCase().includes(searchTerm)
        );
        populateTable(filteredAirlines);
    });

    // Button click handlers
    tableBody.addEventListener('click', async function(e) {
        const button = e.target;
        if (!button.classList.contains('btn')) return;
        const airlineId = button.getAttribute('data-id');
        if (button.classList.contains('btn-add-flight')) {
            window.location.href = `/skytravellers/pages/admin/add_new_flight.php?airline=${airlineId}`;
        } else if (button.classList.contains('btn-edit')) {
            window.location.href = `/skytravellers/pages/admin/edit_airline.php?id=${airlineId}`;
        } else if (button.classList.contains('btn-delete')) {
            if (confirm('Are you sure you want to delete this airline?')) {
                // Implement delete functionality
                try {
                    const response = await fetch(`/skytravellers/api/delete_airline.php?id=${airlineId}`, {
                        method: 'DELETE'
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        fetchAirlines(); // Refresh the table
                    } else {
                        showError('Failed to delete airline');
                    }
                } catch (error) {
                    showError('An error occurred while deleting the airline');
                }
            }
        }
    });

    // Initial fetch
    fetchAirlines();
});