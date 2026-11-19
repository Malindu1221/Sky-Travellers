<?php
session_start();
$pageTitle = "Sky Travellers";
$pageName = 'landing';
include '../includes/header.php';
include '../includes/db_connection.php';
?>

<section class="slider">
    <div class="slider-content">
        <img src="/skytravellers/assets/images/slider1.jpeg" alt="Slider Image" id="slider-image">
    </div>
    <div class="slider-dots">
        <span class="active" onclick="changeSlide(0)"></span>
        <span onclick="changeSlide(1)"></span>
        <span onclick="changeSlide(2)"></span>
    </div>
</section>

<section class="account-links">
    <div class="signup">
        <span>Don't You Have An Account ?</span>
        <a href="/skytravellers/pages/register.php">Sign Up</a>
    </div>
    <div class="login">
        <span>If you Have An Account</span>
        <a href="/skytravellers/pages/login.php">Log in</a>
    </div>
</section>

<section class="flight-booking">
    <h2>BOOK FLIGHT</h2>
    <form action="/skytravellers/pages/search_flights.php" method="GET">
        <div class="form-group">
            <i class="fa-solid fa-plane-departure"></i>
            <label for="from">FROM</label>
            <select id="from" name="from" required>
                <?php
                    // Fetch available flights from the database
                    $countries = mysqli_query($mysqli, "SELECT DISTINCT source FROM scheduled_flights");
                    while ($row = mysqli_fetch_assoc($countries)) {
                        echo "<option value='" . $row['source'] . "'>" . $row['source'] . "</option>";
                    }
                ?>
                <!-- add options from the flights table  -->
            </select>
        </div>
        <div class="form-group">
            <i class="fa-solid fa-plane-arrival"></i>   
            <label for="to">TO</label>
            <select id="to" name="to" required>
                <?php
                    // Fetch available flights from the database
                    $countries = mysqli_query($mysqli, "SELECT DISTINCT destination FROM scheduled_flights");
                    while ($row = mysqli_fetch_assoc($countries)) {
                        echo "<option value='" . $row['destination'] . "'>" . $row['destination'] . "</option>";
                    }
                ?>
                <!-- add options from the flights table  -->
            </select>
        </div>
        <div class="form-group">
            <label for="departure-date">DEPARTURE DATE</label>
            <input type="date" id="departure-date" name="departure_date" required>
        </div>
        <div class="form-group">
            <label for="passengers_count">PASSENGERS</label>
            <input type="number" name="passengers_count" id="passengers_count" min="1" required>
        </div>
        <button type="submit">Search Flight(s)</button>
    </form>
</section>

<section class="explore">
    <h2>EXPLORE NEW PLACES</h2>
    <div class="destinations-slider">
        <div class="destinations">
            <div class="destination">
                <img src="/skytravellers/assets/images/c.jpg" alt="Canada">
                <span>Canada</span>
            </div>
            <div class="destination">
                <img src="/skytravellers/assets/images/i.jpg" alt="India">
                <span>India</span>
            </div>
            <div class="destination">
                <img src="/skytravellers/assets/images/b.jpg" alt="Brazil">
                <span>Brazil</span>
            </div>
            <div class="destination">
                <img src="/skytravellers/assets/images/a.jpg" alt="Australia">
                <span>Australia</span>
            </div>
            <!-- Clone the same items for infinite scrolling effect -->
            <div class="destination">
                <img src="/skytravellers/assets/images/c.jpg" alt="Canada">
                <span>Canada</span>
            </div>
            <div class="destination">
                <img src="/skytravellers/assets/images/i.jpg" alt="India">
                <span>India</span>
            </div>
            <div class="destination">
                <img src="/skytravellers/assets/images/b.jpg" alt="Brazil">
                <span>Brazil</span>
            </div>
            <div class="destination">
                <img src="/skytravellers/assets/images/a.jpg" alt="Australia">
                <span>Australia</span>
            </div>
        </div>
    </div>
</section>


<?php include '../includes/footer.php'; ?>

<script>
const slides = [
    { image: "/skytravellers/assets/images/slider1.jpeg" },
    { image: "/skytravellers/assets/images/slider2.jpg" },
    { image: "/skytravellers/assets/images/slider3.jpg" }
];
let currentSlide = 0;

function changeSlide(index) {
    currentSlide = index;
    updateSlider();
}

function updateSlider() {
    const image = document.getElementById('slider-image');
    const dots = document.querySelectorAll('.slider-dots span');
    
    image.src = slides[currentSlide].image;
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
}

// Auto-rotate slides
setInterval(() => {
    currentSlide = (currentSlide + 1) % slides.length;
    updateSlider();
}, 5000);

// explore destination
const destinationsContainer = document.querySelector('.destinations');

// Function to add new destination dynamically
function addNewDestination(imageSrc, name) {
    const newDestination = document.createElement('div');
    newDestination.classList.add('destination');
    
    const img = document.createElement('img');
    img.src = imageSrc;
    img.alt = name;

    const span = document.createElement('span');
    span.textContent = name;

    newDestination.appendChild(img);
    newDestination.appendChild(span);
    
    destinationsContainer.appendChild(newDestination);
}

</script>
