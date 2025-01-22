<?php
// require_once 'includes/session.php';
// require_once 'Classes/Tag.php';
require '../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_id'])) {
    $tag_id = (int)$_POST['tag_id'];

    if ($tag_id > 0 && Tag::delete($tag_id)) {
        header('Location: admin-dashboard.php?message=Tag deleted successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to delete tag');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;
?>