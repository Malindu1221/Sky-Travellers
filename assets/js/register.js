document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const countrySelect = document.getElementById('country');
    
    // Populate countries
    fetch('https://restcountries.com/v3.1/all')
        .then(response => response.json())
        .then(countries => {
            countries.sort((a, b) => a.name.common.localeCompare(b.name.common));
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.name.common;
                option.textContent = country.name.common;
                countrySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading countries:', error));
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Basic validation
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch('/skytravellers/api/auth_api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Registration successful! Please log in.');
                window.location.href = '/skytravellers/pages/login.php';
            } else {
                alert(`Registration failed: ${data.message}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred during registration.');
        }
    });
});