<?php
session_start();


// ✅ If not logged in → redirect to login
if (!isset($_SESSION['user_id'])) {
    // header("Location: login.php");
    header("Location: auth/login.php");
    exit;
}

// ✅ Logged-in user role
$role = $_SESSION['role'];

// ✅ Admin can access ALL pages
if ($role === 'admin') {
    return; // allow access
}

// ✅ Check allowed roles for this page
if (!in_array($role, $allowed_roles)) {
    die("<h2 style='color:red; text-align:center;'>❌ Access Denied</h2>");
}
?>