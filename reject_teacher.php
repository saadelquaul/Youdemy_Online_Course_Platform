<?php
require 'includes/session.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacherID'])) {
    $teacherID = $_POST['teacherID'];

    $db = Database::getInstance();
    $stmt = $db->prepare("DELETE FROM teachers WHERE teacherID = :teacherID");
    $stmt->bindParam(':teacherID', $teacherID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: admin-dashboard.php?message=Teacher rejected successfully');
    } else {
        header('Location: admin-dashboard.php?error=Failed to reject teacher');
    }
} else {
    header('Location: admin-dashboard.php?error=Invalid request');
}
exit;