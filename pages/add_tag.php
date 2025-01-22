<?php
require_once '../includes/session.php';
// require_once 'Classes/Tag.php';
// require_once 'autoloader.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag'])) {
    $tag = trim($_POST['tag']);

    if (!empty($tag)) {
        $tag = new Tag(null,$tag);
        if ($tag->save()) {
            header('Location: admin-dashboard.php?message=Tag added successfully');
        } else {
            header('Location: admin-dashboard.php?error=Failed to add tag');
        }
    } else {
        header('Location: admin-dashboard.php?error=Tag cannot be empty');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;
?>