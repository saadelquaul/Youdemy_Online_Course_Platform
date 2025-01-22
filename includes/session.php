<?php
session_start();
require_once 'autoloader.php';

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getUser() {
    return isLoggedIn() ? unserialize($_SESSION['user']) : null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>