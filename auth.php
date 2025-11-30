<?php
// auth.php - simple session-based auth helpers (no backend auth endpoint available)
session_start();

function is_logged_in() {
    return isset($_SESSION['user']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function logout() {
    session_unset();
    session_destroy();
}

if (isset($_GET['logout'])) {
    logout();
    header('Location: index.php');
    exit;
}

?>
