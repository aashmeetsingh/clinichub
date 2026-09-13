<?php
require_once "config/database.php";
require_once "includes/auth.php";
if (isset($_SESSION["user_id"]))
    redirectByRole();
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);
    if (strlen($password) < 6)
        $error = "Password must contain at least 6 characters.";
    else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0)
            $error = "Email is already registered.";
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'PATIENT')");
            $stmt->bind_param("sss", $name, $email, $hash);
            if ($stmt->execute()) {
                $uid = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO patients(user_id,phone) VALUES(?,?)");
                $stmt->bind_param("is", $uid, $phone);
                $stmt->execute();
                header("Location: login.php?registered=1");
                exit;
            } else
                $error = "Registration failed.";
        }
    }
}
$page_title = "Register";
include "includes/header.php";
?>
<div class="container auth-wrap">
    <div class="auth-card">
        <div class="eyebrow">CREATE ACCOUNT</div>
        <h1>Patient Registration</h1>
        <p class="muted">Create your ClinicHub patient account.</p>
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST" class="form"><label>Full Name<input name="name" required
                    value="<?= htmlspecialchars($_POST["name"] ?? "") ?>"></label>
            <label>Email<input type="email" name="email" required
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"></label>
            <label>Phone<input name="phone" value="<?= htmlspecialchars($_POST["phone"] ?? "") ?>"></label>
            <label>Password<input type="password" name="password" required></label>
            <button class="btn">Create Account</button>
        </form>
        <p class="form-foot">Already registered? <a href="login.php">Login</a></p>
    </div>
</div>
<?php include "includes/footer.php"; ?>