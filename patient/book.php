<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("PATIENT");
$doctor_id = intval($_GET["doctor_id"] ?? $_POST["doctor_id"] ?? 0);
$error = "";
$stmt = $conn->prepare("SELECT d.*,u.name FROM doctors d JOIN users u ON u.id=d.user_id WHERE d.id=?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$doctor = $stmt->get_result()->fetch_assoc();
if (!$doctor)
    die("Doctor not found.");
$uid = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id FROM patients WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["appointment_date"];
    $time = $_POST["appointment_time"];
    $reason = trim($_POST["reason"]);
    if (strtotime($date . " " . $time) < time())
        $error = "Please select a future appointment time.";
    else {
        $stmt = $conn->prepare("SELECT id FROM appointments WHERE doctor_id=? AND appointment_date=? AND appointment_time=? AND status<>'CANCELLED'");
        $stmt->bind_param("iss", $doctor_id, $date, $time);
        $stmt->execute();
        if ($stmt->get_result()->num_rows)
            $error = "That time slot is already booked.";
        else {
            $stmt = $conn->prepare("INSERT INTO appointments(patient_id,doctor_id,appointment_date,appointment_time,reason) VALUES(?,?,?,?,?)");
            $stmt->bind_param("iisss", $patient["id"], $doctor_id, $date, $time, $reason);
            if ($stmt->execute()) {
                header("Location: appointments.php?booked=1");
                exit;
            }
            $error = "Could not book appointment.";
        }
    }
}
$page_title = "Book Appointment";
include "../includes/header.php";
?>
<div class="container page">
    <div class="booking">
        <div class="card">
            <div class="avatar"><?= strtoupper(substr($doctor["name"], 0, 1)) ?></div>
            <h1><?= htmlspecialchars($doctor["name"]) ?></h1>
            <div class="specialty"><?= htmlspecialchars($doctor["specialization"]) ?></div>
            <p><?= htmlspecialchars($doctor["qualification"]) ?></p>
            <p><?= $doctor["experience"] ?> years experience · ₹<?= number_format($doctor["fee"], 2) ?></p>
        </div>
        <div class="card">
            <div class="eyebrow">APPOINTMENT</div>
            <h2>Choose a slot</h2><?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="POST" class="form"><input type="hidden" name="doctor_id"
                    value="<?= $doctor_id ?>"><label>Date<input type="date" name="appointment_date"
                        min="<?= date("Y-m-d") ?>" required></label><label>Time<input type="time" name="appointment_time"
                        required></label><label>Reason / Notes<textarea name="reason" rows="4"
                        placeholder="Briefly describe your reason for visit"></textarea></label><button class="btn">Book
                    Appointment</button></form>
        </div>
    </div>
</div>
<?php include "../includes/footer.php"; ?>