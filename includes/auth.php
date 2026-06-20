<?php
session_start(); // Start PHP session for login tracking

// Check if a user is logged in
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        // Not logged in, redirect to login page
        header("Location: /expense_app/public/login.php");
        exit();
    }
}

// Check if logged-in user is admin
function requireAdmin() {
    requireLogin(); // First make sure user is logged in

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        // Not an admin, redirect to dashboard or show error
        header("Location: /expense_app/public/dashboard.php");
        exit();
    }
}

// Optional: log user in
function loginUser($userId, $role) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['role'] = $role;
}

// Optional: log user out
function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: /expense_app/public/login.php");
    exit();
}
?>
