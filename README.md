<div align="center">
  <img src="assets/images/banner.png" alt="Airplane flying through sunset clouds" width="160">

  <h1>SkyTravellers</h1>
  <p>Search flights, manage bookings, and keep your travel details in one place.</p>

  <p>
    <img src="https://img.shields.io/badge/PHP-server--side-777BB4?logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/MySQL-database-4479A1?logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/HTML5-markup-E34F26?logo=html5&logoColor=white" alt="HTML5">
    <img src="https://img.shields.io/badge/CSS3-styles-1572B6?logo=css3&logoColor=white" alt="CSS3">
    <img src="https://img.shields.io/badge/JavaScript-interactions-F7DF1E?logo=javascript&logoColor=black" alt="JavaScript">
  </p>
</div>

## About The Project

SkyTravellers is a PHP web application for finding scheduled flights and managing passenger bookings. Travelers can search routes by origin, destination, and departure date; compare available seats and fares across Economy, Business, and First Class; and complete a booking flow that records passenger and payment details.

The project also provides an administrative area for maintaining airlines, flights, and flight schedules, viewing dashboard totals, and searching passenger records. It is intended for both travelers and the staff who manage the flight information displayed by the booking flow.

## Core Features

| Area | Implemented features |
| --- | --- |
| Flight search | Search scheduled flights by source, destination, and departure date; view arrival/departure times, class fares, and available seats. |
| Booking | Select a cabin class and passenger count, review the booking/payment form, save a booking and payment record, and update the remaining seats. |
| Passenger details | Record each passenger's name, contact number, date of birth, gender, and passport number for a booking. |
| My Flights | View a user's bookings, print a ticket from the page, and cancel eligible future flights; cancellation restores the seats. |
| Accounts | Register and log in, upload a profile image, update email/phone details, and change a password. |
| Administration | View dashboard counts and earnings; add, edit, and remove airlines and flights; create, edit, and remove scheduled flights; search passenger records. |
| Information pages | View About, Contact, Privacy, and Terms pages. |

> **Payment scope:** the booking flow records a payment method, transaction ID, amount, and completed status in the database. No external payment gateway integration is present in the repository.

## Project Structure

```text
SkyTravellers/
├── api/
│   ├── add_airline.php
│   ├── add_flight.php
│   ├── auth_api.php
│   ├── cancel_booking.php
│   ├── change_password.php
│   ├── delete_airline.php
│   ├── delete_flight.php
│   ├── edit_airline.php
│   ├── edit_flight.php
│   ├── edit_scheduled_flight.php
│   ├── get_airlines.php
│   ├── get_flights.php
│   ├── get_scheduled_flights.php
│   ├── login_api.php
│   ├── logout.php
│   ├── process_booking.php
│   ├── remove_scheduled_flight.php
│   ├── schedule_flight.php
│   └── update_account.php
├── assets/
│   ├── css/
│   │   ├── aboutUs.css
│   │   ├── account.css
│   │   ├── add_new_airline.css
│   │   ├── add_new_flight.css
│   │   ├── admin_dashboard.css
│   │   ├── admin_passenger_list.css
│   │   ├── admin_styles.css
│   │   ├── airline_management.css
│   │   ├── confirm_booking.css
│   │   ├── contact.css
│   │   ├── edit_airline.css
│   │   ├── edit_flight.css
│   │   ├── flight_management.css
│   │   ├── landing.css
│   │   ├── login.css
│   │   ├── my_flights.css
│   │   ├── passenger_details.css
│   │   ├── register.css
│   │   ├── schedule_flight.css
│   │   ├── search_flights.css
│   │   └── styles.css
│   ├── images/
│   │   ├── AB1.jpg
│   │   ├── AB2.jpg
│   │   ├── a.jpg
│   │   ├── b.jpg
│   │   ├── banner.png
│   │   ├── c.jpg
│   │   ├── i.jpg
│   │   ├── large.png
│   │   ├── slider1.jpeg
│   │   ├── slider2.jpg
│   │   ├── slider3.jpg
│   │   ├── slider20.jpeg
│   │   ├── slider22.jpg
│   │   └── slider30.jpeg
│   └── js/
│       ├── add_new_airline.js
│       ├── add_new_flight.js
│       ├── airline_management.js
│       ├── edit_airline.js
│       ├── edit_flight.js
│       ├── flight_management.js
│       ├── register.js
│       ├── schedule_flight.js
│       └── schedule_new_flight.js
├── includes/
│   ├── admin_footer.php
│   ├── admin_header.php
│   ├── db_connection.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
├── pages/
│   ├── admin/
│   │   ├── add_new_airline.php
│   │   ├── add_new_flight.php
│   │   ├── admin_dashboard.php
│   │   ├── airline_management.php
│   │   ├── edit_airline.php
│   │   ├── edit_flight.php
│   │   ├── flight_management.php
│   │   ├── flight_schedule.php
│   │   ├── index.php
│   │   ├── passenger_list.php
│   │   └── schedule_new_flight.php
│   ├── about.php
│   ├── account.php
│   ├── book_flight.php
│   ├── confirm_booking.php
│   ├── contact.php
│   ├── landing.php
│   ├── login.php
│   ├── my_flights.php
│   ├── passenger_details.php
│   ├── privacy.php
│   ├── register.php
│   ├── search_flights.php
│   └── terms.php
├── config.php
├── index.php
└── README.md
```

## Setup & Installation

### Requirements

- PHP with the `mysqli` extension
- MySQL or MariaDB
- A local PHP web server, such as Apache through XAMPP or WAMP

### Steps

1. **Place the project under the expected URL path.** For XAMPP, copy or clone the project into `htdocs/skytravellers/` so it is available at `/skytravellers`. The application uses this path in its links and asset URLs.
2. **Create the database.** Create a MySQL database named `skytravellers`.
3. **Provide a compatible database schema and data.** This repository does not include a `.sql` schema or seed file. The application expects tables for `users`, `airlines`, `flights`, `scheduled_flights`, `bookings`, `payments`, and `passenger_details`. Create/import a schema compatible with the queries in the source and add the airline/flight data needed to search and book.
4. **Configure database credentials.** Edit `includes/db_connection.php` to set the MySQL host, database name, username, and password. Its current defaults are `localhost`, `skytravellers`, `root`, and an empty password.
5. **Start the web and database servers** in your local server stack, then open [http://localhost/skytravellers/](http://localhost/skytravellers/).
6. **Create a traveler account** through the registration page. To use admin pages, the account must have the `admin` role expected by the application.

If the project is deployed under a different URL path, update the hard-coded `/skytravellers` references in the PHP pages and assets as well as the web-server configuration.
