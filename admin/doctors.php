<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("ADMIN");
$error = "";
$edit = null;
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=(SELECT user_id FROM doctors WHERE id=?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: doctors.php");
    exit;
}
if (isset($_GET["edit"])) {
    $id = intval($_GET["edit"]);
    $stmt = $conn->prepare("SELECT d.*,u.name,u.email FROM doctors d JOIN users u ON u.id=d.user_id WHERE d.id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"] ?? 0);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $special = trim($_POST["specialization"]);
    $qual = trim($_POST["qualification"]);
    $exp = intval($_POST["experience"]);
    $fee = floatval($_POST["fee"]);
    $desc = trim($_POST["description"]);
    if ($id) {
        $stmt = $conn->prepare("UPDATE users u JOIN doctors d ON d.user_id=u.id SET u.name=?,u.email=?,d.specialization=?,d.qualification=?,d.experience=?,d.fee=?,d.description=? WHERE d.id=?");
        $stmt->bind_param("ssssidsi", $name, $email, $special, $qual, $exp, $fee, $desc, $id);
        $stmt->execute();
    } else {
        $password = password_hash("Doctor@123", PASSWORD_DEFAULT);
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'DOCTOR')");
            $stmt->bind_param("sss", $name, $email, $password);
            $stmt->execute();
            $uid = $conn->insert_id;
            $stmt = $conn->prepare("INSERT INTO doctors(user_id,specialization,qualification,experience,fee,description) VALUES(?,?,?,?,?,?)");
            $stmt->bind_param("issids", $uid, $special, $qual, $exp, $fee, $desc);
            $stmt->execute();
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Could not add doctor. Email may already exist.";
        }
    }
    header("Location: doctors.php");
    exit;
}
$rows = $conn->query("SELECT d.*,u.name,u.email FROM doctors d JOIN users u ON u.id=d.user_id ORDER BY u.name");
$page_title = "Manage Doctors";
include "../includes/header.php";
?>
<div class="container page">
    <div class="page-head">
        <div>
            <div class="eyebrow">ADMINISTRATION</div>
            <h1>Manage Doctors</h1>
        </div>
    </div>
    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div><?php endif; ?>
    <div class="two-col">
        <div class="card">
            <h2><?= $edit ? "Edit Doctor" : "Add Doctor" ?></h2>
            <form method="POST" class="form"><input type="hidden" name="id" value="<?= $edit["id"] ?? 0 ?>">
                <label>Name<input name="name" required
                        value="<?= htmlspecialchars($edit["name"] ?? "") ?>"></label><label>Email<input type="email"
                        name="email" required
                        value="<?= htmlspecialchars($edit["email"] ?? "") ?>"></label><label>Specialization<input
                        name="specialization" required
                        value="<?= htmlspecialchars($edit["specialization"] ?? "") ?>"></label><label>Qualification<input
                        name="qualification"
                        value="<?= htmlspecialchars($edit["qualification"] ?? "") ?>"></label><label>Experience
                    (years)<input type="number" name="experience" min="0"
                        value="<?= $edit["experience"] ?? 0 ?>"></label><label>Consultation Fee<input type="number"
                        step="0.01" name="fee" value="<?= $edit["fee"] ?? 0 ?>"></label><label>Description<textarea
                        name="description"
                        rows="4"><?= htmlspecialchars($edit["description"] ?? "") ?></textarea></label><button
                    class="btn"><?= $edit ? "Update Doctor" : "Add Doctor" ?></button><?php if ($edit): ?><a
                        href="doctors.php">Cancel</a><?php endif; ?>
            </form><?php if (!$edit): ?>
                <p class="hint">Default doctor password: <b>Doctor@123</b></p><?php endif; ?>
        </div>
        <div class="card">
            <h2>Doctors</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody><?php while ($d = $rows->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($d["name"]) ?></td>
                                <td><?= htmlspecialchars($d["specialization"]) ?></td>
                                <td><?= htmlspecialchars($d["email"]) ?></td>
                                <td><a href="?edit=<?= $d["id"] ?>">Edit</a> · <a class="danger-link"
                                        onclick="return confirm('Delete doctor?')" href="?delete=<?= $d["id"] ?>">Delete</a>
                                </td>
                            </tr><?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "../includes/footer.php"; ?>