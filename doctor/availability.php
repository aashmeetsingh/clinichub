<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("DOCTOR");
$uid = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id FROM doctors WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$did = $stmt->get_result()->fetch_assoc()["id"];
$msg = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["available_date"];
    $start = $_POST["start_time"];
    $end = $_POST["end_time"];
    if ($start >= $end)
        $error = "End time must be after start time.";
    else {
        $stmt = $conn->prepare("INSERT INTO doctor_availability(doctor_id,available_date,start_time,end_time) VALUES(?,?,?,?)");
        $stmt->bind_param("isss", $did, $date, $start, $end);
        if ($stmt->execute())
            $msg = "Availability added.";
        else
            $error = "Could not add availability.";
    }
}
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM doctor_availability WHERE id=? AND doctor_id=?");
    $stmt->bind_param("ii", $id, $did);
    $stmt->execute();
    header("Location: availability.php");
    exit;
}
$stmt = $conn->prepare("SELECT * FROM doctor_availability WHERE doctor_id=? AND available_date>=CURDATE() ORDER BY available_date,start_time");
$stmt->bind_param("i", $did);
$stmt->execute();
$slots = $stmt->get_result();
$page_title = "Availability";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">DOCTOR PORTAL</div>
            <h1>Manage Availability</h1>
        </div>
    </div>
    <div class="two-col">
        <div class="card">
            <h2>Add Availability</h2><?php if ($msg): ?>
                <div class="alert success"><?= $msg ?></div><?php endif; ?><?php if ($error): ?>
                <div class="alert error"><?= $error ?></div><?php endif; ?>
            <form method="POST" class="form"><label>Date<input type="date" name="available_date"
                        min="<?= date("Y-m-d") ?>" required></label><label>Start Time<input type="time" name="start_time"
                        required></label><label>End Time<input type="time" name="end_time" required></label><button
                    class="btn">Add Slot</button></form>
        </div>
        <div class="card">
            <h2>Upcoming Availability</h2><?php while ($s = $slots->fetch_assoc()): ?>
                <div class="slot">
                    <div>
                        <b><?= date("d M Y", strtotime($s["available_date"])) ?></b><br><?= date("h:i A", strtotime($s["start_time"])) ?>
                        – <?= date("h:i A", strtotime($s["end_time"])) ?></div><a class="danger-link"
                        href="?delete=<?= $s["id"] ?>">Delete</a>
                </div><?php endwhile; ?>
        </div>
    </div>
</div>
<?php include "../includes/footer.php"; ?>