<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("DOCTOR");
$uid = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id,specialization,qualification,experience,fee FROM doctors WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$doctor = $stmt->get_result()->fetch_assoc();
$did = $doctor["id"];
function countStatus($conn, $did, $status = null)
{
    if ($status) {
        $s = $conn->prepare("SELECT COUNT(*) c FROM appointments WHERE doctor_id=? AND status=?");
        $s->bind_param("is", $did, $status);
    } else {
        $s = $conn->prepare("SELECT COUNT(*) c FROM appointments WHERE doctor_id=?");
        $s->bind_param("i", $did);
    }
    $s->execute();
    return $s->get_result()->fetch_assoc()["c"];
}
$total = countStatus($conn, $did);
$pending = countStatus($conn, $did, "PENDING");
$confirmed = countStatus($conn, $did, "CONFIRMED");
$completed = countStatus($conn, $did, "COMPLETED");
$page_title = "Doctor Dashboard";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">DOCTOR PORTAL</div>
            <h1>Welcome, Dr. <?= htmlspecialchars($_SESSION["name"]) ?></h1>
            <p class="muted"><?= htmlspecialchars($doctor["specialization"]) ?></p>
        </div>
    </div>
    <div class="stats">
        <div class="stat"><small>Total</small><b><?= $total ?></b></div>
        <div class="stat"><small>Pending</small><b><?= $pending ?></b></div>
        <div class="stat"><small>Confirmed</small><b><?= $confirmed ?></b></div>
        <div class="stat"><small>Completed</small><b><?= $completed ?></b></div>
    </div>
    <div class="quick"><a class="card" href="appointments.php">
            <h3> Manage Appointments</h3>
            <p>Review and update patient requests.</p>
        </a><a class="card" href="availability.php">
            <h3> Set Availability</h3>
            <p>Add dates and time ranges for booking.</p>
        </a></div>
</div>
<?php include "../includes/footer.php"; ?>