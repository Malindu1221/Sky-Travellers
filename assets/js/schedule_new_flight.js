document.addEventListener('DOMContentLoaded', function() {
    const scheduleFlightForm = document.getElementById('scheduleFlightForm');

    scheduleFlightForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
            scheduleFlight();
        } else {
            alert('Please fix the errors in the form.');
        }
    });
});

function validateForm() {
    const departureDateTime = document.getElementById('departure_datetime').value;
    const arrivalDateTime = document.getElementById('arrival_datetime').value;

    const dateTimePattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

    if (!dateTimePattern.test(departureDateTime) || !dateTimePattern.test(arrivalDateTime)) {
        alert('Invalid date format. Use YYYY-MM-DDTHH:MM');
        return false;
    }

    // Check if arrival is after departure
    if (new Date(arrivalDateTime) <= new Date(departureDateTime)) {
        alert('Arrival date must be after departure date.');
        return false;
    }

    return true;
}


function formatDateForMySQL(dateTimeLocal) {
    const dateTime = new Date(dateTimeLocal);
    const year = dateTime.getFullYear();
    const month = String(dateTime.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
    const day = String(dateTime.getDate()).padStart(2, '0');
    const hours = String(dateTime.getHours()).padStart(2, '0');
    const minutes = String(dateTime.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day} ${hours}:${minutes}:00`; // Format: YYYY-MM-DD HH:MM:SS
}

function scheduleFlight() {
    const formData = new FormData(document.getElementById('scheduleFlightForm'));
    
    // Format date fields before sending
    const departureDateTime = formatDateForMySQL(formData.get('departure_datetime'));
    const arrivalDateTime = formatDateForMySQL(formData.get('arrival_datetime'));
    
    // Append formatted dates to FormData
    formData.set('departure_datetime', departureDateTime);
    formData.set('arrival_datetime', arrivalDateTime);
    
    console.log('Formatted Departure:', departureDateTime);
    console.log('Formatted Arrival:', arrivalDateTime);
    
    fetch('/skytravellers/api/schedule_flight.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text); // Log the raw response
        try {
            const data = JSON.parse(text);
            if (data.success) {
                alert('Flight scheduled successfully!');
                window.location.href = '/skytravellers/pages/admin/flight_schedule.php';
            } else {
                alert('Error scheduling flight: ' + data.message);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            console.error('Raw response:', text);
            alert('Unexpected server response. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}