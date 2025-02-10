<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Role-based access control
function checkRole($role) {
    if ($_SESSION['role'] !== $role) {
        echo "<script>alert('Access Denied!'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
