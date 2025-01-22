<?php
// require 'includes/session.php';
require '../includes/session.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];

    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);

    if ($stmt->execute()) {
        header('Location: admin-dashboard.php?message=Category added successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to add category');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;
?>