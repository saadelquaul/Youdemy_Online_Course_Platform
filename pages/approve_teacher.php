<?php
// require 'includes/session.php';
require '../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacherID'])) {
    $teacherID = $_POST['teacherID'];

    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE teachers SET isActive = 1 WHERE teacherID = :teacherID");
    $stmt->bindParam(':teacherID', $teacherID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: admin-dashboard.php?message=Teacher approved successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to approve teacher');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;
?>