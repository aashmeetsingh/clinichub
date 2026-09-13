<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("ADMIN");
function total($conn, $table)
{
    $r = $conn->query("SELECT COUNT(*) c FROM `$table`")->fetch_assoc();
    return $r["c"];
}
$page_title = "Admin Dashboard";
include "../includes/header.php";
?>
<div class="container page">
    <div class="eyebrow">ADMINISTRATION</div>
    <h1>System Dashboard</h1>
    <p class="muted">Manage users, doctors and appointments.</p>
    <div class="stats">
        <div class="stat"><small>Users</small><b><?= total($conn, "users") ?></b></div>
        <div class="stat"><small>Patients</small><b><?= total($conn, "patients") ?></b></div>
        <div class="stat"><small>Doctors</small><b><?= total($conn, "doctors") ?></b></div>
        <div class="stat"><small>Appointments</small><b><?= total($conn, "appointments") ?></b></div>
    </div>
    <div class="quick"><a class="card" href="doctors.php">
            <h3>Manage Doctors</h3>
            <p>Add, edit and remove doctors.</p>
        </a><a class="card" href="users.php">
            <h3>View Users</h3>
            <p>Review registered accounts.</p>
        </a></div>
</div>
<?php include "../includes/footer.php"; ?>