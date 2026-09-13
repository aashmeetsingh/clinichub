<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("DOCTOR");
$uid = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id FROM doctors WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$did = $stmt->get_result()->fetch_assoc()["id"];
if (isset($_GET["action"], $_GET["id"])) {
    $id = intval($_GET["id"]);
    $allowed = ["confirm" => "CONFIRMED", "reject" => "REJECTED", "complete" => "COMPLETED"];
    if (isset($allowed[$_GET["action"]])) {
        $status = $allowed[$_GET["action"]];
        $stmt = $conn->prepare("UPDATE appointments SET status=? WHERE id=? AND doctor_id=?");
        $stmt->bind_param("sii", $status, $id, $did);
        $stmt->execute();
    }
    header("Location: appointments.php");
    exit;
}
$stmt = $conn->prepare("SELECT a.*,u.name patient_name,p.phone FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users u ON u.id=p.user_id WHERE a.doctor_id=? ORDER BY a.appointment_date DESC,a.appointment_time DESC");
$stmt->bind_param("i", $did);
$stmt->execute();
$rows = $stmt->get_result();
$page_title = "Appointments";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">DOCTOR PORTAL</div>
            <h1>Appointment Requests</h1>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($a = $rows->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($a["patient_name"]) ?></td>
                        <td><?= htmlspecialchars($a["phone"] ?? "-") ?></td>
                        <td><?= date("d M Y", strtotime($a["appointment_date"])) ?></td>
                        <td><?= date("h:i A", strtotime($a["appointment_time"])) ?></td>
                        <td><?= htmlspecialchars($a["reason"] ?? "-") ?></td>
                        <td><span class="badge <?= strtolower($a["status"]) ?>"><?= $a["status"] ?></span></td>
                        <td class="actions-cell"><?php if ($a["status"] === "PENDING"): ?><a
                                    href="?action=confirm&id=<?= $a["id"] ?>">Confirm</a><a class="danger-link"
                                    href="?action=reject&id=<?= $a["id"] ?>">Reject</a><?php elseif ($a["status"] === "CONFIRMED"): ?><a
                                    href="?action=complete&id=<?= $a["id"] ?>">Complete</a><?php endif; ?></td>
                    </tr><?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "../includes/footer.php"; ?>