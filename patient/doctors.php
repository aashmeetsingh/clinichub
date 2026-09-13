<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("PATIENT");
$q = trim($_GET["q"] ?? "");
$like = "%" . $q . "%";
$stmt = $conn->prepare("SELECT d.id,d.specialization,d.qualification,d.experience,d.fee,d.description,u.name FROM doctors d JOIN users u ON u.id=d.user_id WHERE u.name LIKE ? OR d.specialization LIKE ? ORDER BY u.name");
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$doctors = $stmt->get_result();
$page_title = "Find Doctors";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">MEDICAL DIRECTORY</div>
            <h1>Find a Doctor</h1>
        </div>
    </div>
    <form class="search" method="GET"><input name="q" placeholder="Search by doctor name or specialization..."
            value="<?= htmlspecialchars($q) ?>"><button class="btn">Search</button></form>
    <div class="doctor-grid">
        <?php while ($d = $doctors->fetch_assoc()): ?>
            <div class="doctor-card">
                <div class="avatar"><?= strtoupper(substr($d["name"], 0, 1)) ?></div>
                <h3><?= htmlspecialchars($d["name"]) ?></h3>
                <div class="specialty"><?= htmlspecialchars($d["specialization"]) ?></div>
                <p><?= htmlspecialchars($d["qualification"] ?? "") ?></p>
                <p><?= intval($d["experience"]) ?> years experience · ₹<?= number_format($d["fee"], 2) ?></p><a
                    class="btn btn-block" href="book.php?doctor_id=<?= $d["id"] ?>">Book Appointment</a>
            </div>
        <?php endwhile;
        if ($doctors->num_rows === 0): ?>
            <div class="empty">No doctors found.</div><?php endif; ?>
    </div>
</div>
<?php include "../includes/footer.php"; ?>