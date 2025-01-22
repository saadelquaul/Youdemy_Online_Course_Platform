<?php
require '../includes/session.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id']) && isset($_POST['name'])) {
    $categoryId = $_POST['category_id'];
    $name = $_POST['name'];


    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE categories SET name = :name WHERE category_id = :category_id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: admin-dashboard.php?message=Category updated successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to update category');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
?>