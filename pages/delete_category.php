<?php
// require_once 'includes/session.php';
// require_once 'Classes/Category.php';
require '../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];

    if (Category::delete($category_id)) {
        header('Location: admin-dashboard.php?message=Category deleted successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to delete category');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;