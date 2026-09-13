<?php
require_once "config/database.php";
require_once "includes/auth.php";
if (isset($_SESSION["user_id"]))
    redirectByRole();
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $stmt = $conn->prepare("SELECT id,name,email,password,role FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];
        redirectByRole();
    } else
        $error = "Invalid email or password.";
}
$page_title = "Login";
include "includes/header.php";
?>
<div class="container auth-wrap">
    <div class="auth-card">
        <div class="eyebrow">WELCOME BACK</div>
        <h1>Login</h1>
        <p class="muted">Access your ClinicHub account.</p>
        <?php if (isset($_GET["registered"])): ?>
            <div class="alert success">Registration successful. Please login.</div><?php endif; ?>
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST" class="form"><label>Email<input type="email" name="email"
                    required></label><label>Password<input type="password" name="password" required></label><button
                class="btn">Login</button></form>
        <p class="form-foot">New patient? <a href="register.php">Create an account</a></p>
    </div>
</div>
<?php include "includes/footer.php"; ?>