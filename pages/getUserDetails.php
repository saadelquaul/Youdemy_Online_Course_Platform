<?php
// require 'includes/session.php';
require '../includes/session.php';

if (!isset($_GET['userID'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$userID = $_GET['userID'];
$db = Database::getInstance();

$stmt = $db->prepare("SELECT userID, firstName, lastName, Email, role, status FROM users WHERE userID = :id");
$stmt->execute(['id' => $userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if($user['role']==='teacher'){
        $stmt = $db->prepare("SELECT COUNT(c.*) as uploaded_courses FROM courses c where c.teacher_id = :userID");
        $stmt->execute(['userID' => $userID]);
        $user[] = $stmt->fetch(PDO::FETCH_COLUMN);
        $stmt = $db->prepare("SELECT COUNT(e.*) as total_enrollments FROM enrollments e 
        JOIN courses c ON c.course_id = e.course_id
        where c.teacher_id = :userID");
        $stmt->execute(['userID' => $userID]);
        $user[] = $stmt->fetch(PDO::FETCH_COLUMN);
        
    }
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}
?>