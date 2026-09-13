<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
requireRole("ADMIN");
$rows = $conn->query("SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC");
$page_title = "Users";
include "../includes/header.php";
?>
<div class="container page">
    <div class="eyebrow">ADMINISTRATION</div>
    <h1>Registered Users</h1>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody><?php while ($u = $rows->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($u["name"]) ?></td>
                        <td><?= htmlspecialchars($u["email"]) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($u["role"]) ?></span></td>
                        <td><?= date("d M Y", strtotime($u["created_at"])) ?></td>
                    </tr><?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "../includes/footer.php"; ?>