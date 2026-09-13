<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? "clinichub") ?> | clinichub</title>
    <link rel="stylesheet" href="/clinichub/css/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="container nav-inner">
            <a class="logo" href="/clinichub/">Clinic<span>Hub</span></a>
            <div class="nav-links">
                <a href="/clinichub/">Home</a>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <?php if ($_SESSION["role"] === "PATIENT"): ?>
                        <a href="/clinichub/patient/doctors.php">Doctors</a>
                        <a href="/clinichub/patient/appointments.php">Appointments</a>
                        <a href="/clinichub/patient/dashboard.php">Dashboard</a>
                    <?php elseif ($_SESSION["role"] === "DOCTOR"): ?>
                        <a href="/clinichub/doctor/appointments.php">Appointments</a>
                        <a href="/clinichub/doctor/availability.php">Availability</a>
                        <a href="/clinichub/doctor/dashboard.php">Dashboard</a>
                    <?php else: ?>
                        <a href="/clinichub/admin/doctors.php">Doctors</a>
                        <a href="/clinichub/admin/users.php">Users</a>
                        <a href="/clinichub/admin/dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    <a class="btn btn-sm" href="/clinichub/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/clinichub/login.php">Login</a><a class="btn btn-sm" href="/clinichub/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>