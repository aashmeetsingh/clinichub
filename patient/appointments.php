<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("PATIENT");
$uid = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id FROM patients WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$pid = $stmt->get_result()->fetch_assoc()["id"];
if (isset($_GET["cancel"])) {
    $id = intval($_GET["cancel"]);
    $stmt = $conn->prepare("UPDATE appointments SET status='CANCELLED' WHERE id=? AND patient_id=? AND status IN ('PENDING','CONFIRMED')");
    $stmt->bind_param("ii", $id, $pid);
    $stmt->execute();
    header("Location: appointments.php");
    exit;
}
$stmt = $conn->prepare("SELECT a.*,u.name doctor_name,d.specialization FROM appointments a JOIN doctors d ON d.id=a.doctor_id JOIN users u ON u.id=d.user_id WHERE a.patient_id=? ORDER BY a.appointment_date DESC,a.appointment_time DESC");
$stmt->bind_param("i", $pid);
$stmt->execute();
$appointments = $stmt->get_result();
$page_title = "My Appointments";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">PATIENT PORTAL</div>
            <h1>My Appointments</h1>
        </div><a class="btn" href="doctors.php">New Appointment</a>
    </div>
    <?php if (isset($_GET["booked"])): ?>
        <div class="alert success">Appointment booked successfully.</div><?php endif; ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($a = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><b><?= htmlspecialchars($a["doctor_name"]) ?></b></td>
                        <td><?= htmlspecialchars($a["specialization"]) ?></td>
                        <td><?= date("d M Y", strtotime($a["appointment_date"])) ?></td>
                        <td><?= date("h:i A", strtotime($a["appointment_time"])) ?></td>
                        <td><?= htmlspecialchars($a["reason"] ?? "-") ?></td>
                        <td><span class="badge <?= strtolower($a["status"]) ?>"><?= $a["status"] ?></span></td>
                        <td><?php if (in_array($a["status"], ["PENDING", "CONFIRMED"])): ?><a class="danger-link"
                                    onclick="return confirm('Cancel this appointment?')"
                                    href="?cancel=<?= $a["id"] ?>">Cancel</a><?php endif; ?></td>
                    </tr><?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "../includes/footer.php"; ?>