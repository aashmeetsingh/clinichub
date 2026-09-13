<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function requireLogin() {
    if (!isset($_SESSION["user_id"])) {
        header("Location: /clinichub/login.php");
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION["role"] !== $role) {
        header("Location: /clinichub/");
        exit;
    }
}

function redirectByRole() {
    if (!isset($_SESSION["role"])) return;
    $base="/clinichub/";
    if ($_SESSION["role"]==="PATIENT") header("Location: ".$base."patient/dashboard.php");
    elseif ($_SESSION["role"]==="DOCTOR") header("Location: ".$base."doctor/dashboard.php");
    else header("Location: ".$base."admin/dashboard.php");
    exit;
}
?>
