document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editAirlineForm');
    const airlineId = new URLSearchParams(window.location.search).get('id');

    if (airlineId) {
        fetchAirlineDetails(airlineId);
    } else {
        alert('No airline ID provided');
        window.location.href = '/skytravellers/pages/admin/airline_management.php';
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateAirline();
    });

    async function fetchAirlineDetails(id) {
        try {
            const response = await fetch(`/skytravellers/api/edit_airline.php?id=${id}`);
            const data = await response.json();
            if (data.status === 'success') {
                document.getElementById('airlineId').value = data.data.id;
                document.getElementById('airlineName').value = data.data.name;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error fetching airline details:', error);
            alert('Failed to load airline details. Redirecting to airline management.');
            window.location.href = '/skytravellers/pages/admin/airline_management.php';
        }
    }

    async function updateAirline() {
        const formData = new FormData(form);
        try {
            const response = await fetch('/skytravellers/api/edit_airline.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.status === 'success') {
                alert('Airline updated successfully');
                window.location.href = '/skytravellers/pages/admin/airline_management.php';
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error updating airline:', error);
            alert('Failed to update airline: ' + error.message);
        }
    }
});