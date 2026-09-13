<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("PATIENT");
$uid = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id FROM patients WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();
$pid = $patient["id"];
$stmt = $conn->prepare("SELECT COUNT(*) c FROM appointments WHERE patient_id=?");
$stmt->bind_param("i", $pid);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()["c"];
$stmt = $conn->prepare("SELECT COUNT(*) c FROM appointments WHERE patient_id=? AND status='CONFIRMED'");
$stmt->bind_param("i", $pid);
$stmt->execute();
$confirmed = $stmt->get_result()->fetch_assoc()["c"];
$stmt = $conn->prepare("SELECT COUNT(*) c FROM appointments WHERE patient_id=? AND status='PENDING'");
$stmt->bind_param("i", $pid);
$stmt->execute();
$pending = $stmt->get_result()->fetch_assoc()["c"];
$page_title = "Patient Dashboard";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">PATIENT PORTAL</div>
            <h1>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?></h1>
            <p class="muted">Manage your healthcare appointments.</p>
        </div><a class="btn" href="doctors.php">Find a Doctor</a>
    </div>
    <div class="stats">
        <div class="stat"><small>Total Appointments</small><b><?= $total ?></b></div>
        <div class="stat"><small>Confirmed</small><b><?= $confirmed ?></b></div>
        <div class="stat"><small>Pending</small><b><?= $pending ?></b></div>
    </div>
    <div class="card">
        <h2>Quick Actions</h2>
        <div class="quick"><a href="doctors.php">Find Doctors</a><a href="appointments.php"> My Appointments</a>
        </div>
    </div>
</div>
<?php include "../includes/footer.php"; ?>