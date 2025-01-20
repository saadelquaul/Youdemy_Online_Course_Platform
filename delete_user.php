<?php
require 'includes/session.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {
    $teacherID = $_POST['userID'];

    $db = Database::getInstance();
    $stmt = $db->prepare("DELETE FROM users WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: admin-dashboard.php?message=Teacher Deleted successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to delete teacher');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;
