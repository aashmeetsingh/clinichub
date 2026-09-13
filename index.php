<?php $page_title = "Home";
include "includes/header.php"; ?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <div class="eyebrow">SMART HEALTHCARE MANAGEMENT</div>
            <h1>Healthcare appointments, <span>made simple.</span></h1>
            <p class="lead">Find doctors, check availability and manage hospital appointments from one secure platform.
            </p>
            <div class="actions"><a class="btn btn-lg" href="register.php">Book an Appointment</a><a
                    class="btn btn-outline btn-lg" href="login.php">Login</a></div>
        </div>
        <div class="hero-panel">
            <div class="cross">+</div>
            <h2>Welcome to ClinicHub</h2>
            <p>A role-based appointment system for patients, doctors and administrators.</p>
            <div class="mini-stats">
                <div><b>24/7</b><small>Access</small></div>
                <div><b>Secure</b><small>Login</small></div>
                <div><b>Easy</b><small>Booking</small></div>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-title">
            <div class="eyebrow">CORE FEATURES</div>
            <h2>One system for the entire appointment workflow</h2>
        </div>
        <div class="cards">
            <div class="card">
                <div class="icon"></div>
                <h3>Find Doctors</h3>
                <p>Search doctors by name or specialization and view their details.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Book Appointments</h3>
                <p>Choose an available date and time while preventing double bookings.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Role Based Access</h3>
                <p>Patients, doctors and admins see only the features relevant to them.</p>
            </div>
        </div>
    </div>
</section>
<?php include "includes/footer.php"; ?>