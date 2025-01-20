<?php
require_once 'User.php';
class Admin extends User {
    
    


    // Manage users (activate, suspend, delete)
    public function manageUser($user_id, $action) {
        switch ($action) {
            case 'activate':
                $stmt = $this->DB->prepare("UPDATE users SET is_active = 1 WHERE user_id = :user_id");
                break;
            case 'suspend':
                $stmt = $this->DB->prepare("UPDATE users SET is_active = 0 WHERE user_id = :user_id");
                break;
            case 'delete':
                $stmt = $this->DB->prepare("DELETE FROM users WHERE user_id = :user_id");
                break;
            default:
                throw new Exception("Invalid action specified.");
        }
        $stmt->execute(['user_id' => $user_id]);
    }

    // Manage content (courses, categories, tags)
    public function manageContent($type, $action, $id, $data = []) {
        switch ($type) {
            case 'course':
                $table = 'courses';
                break;
            case 'category':
                $table = 'categories';
                break;
            case 'tag':
                $table = 'tags';
                break;
            default:
                throw new Exception("Invalid content type specified.");
        }

        switch ($action) {
            case 'update':
                $setClause = [];
                foreach ($data as $key => $value) {
                    $setClause[] = "$key = :$key";
                }
                $setClause = implode(', ', $setClause);
                $stmt = $this->DB->prepare("UPDATE $table SET $setClause WHERE {$type}_id = :id");
                $data['id'] = $id;
                $stmt->execute($data);
                break;
            case 'delete':
                $stmt = $this->DB->prepare("DELETE FROM $table WHERE {$type}_id = :id");
                $stmt->execute(['id' => $id]);
                break;
            default:
                throw new Exception("Invalid action specified.");
        }
    }

    // View global statistics
    public function viewGlobalStatistics() {
        $statistics = [];

        // Total number of users
        $stmt = $this->DB->prepare("SELECT COUNT(*) AS total_users FROM users");
        $stmt->execute();
        $statistics['total_users'] = $stmt->fetchColumn();

        // Total number of courses
        $stmt = $this->DB->prepare("SELECT COUNT(*) AS total_courses FROM courses");
        $stmt->execute();
        $statistics['total_courses'] = $stmt->fetchColumn();

        // Total number of enrollments
        $stmt = $this->DB->prepare("SELECT COUNT(*) AS total_enrollments FROM enrollments");
        $stmt->execute();
        $statistics['total_enrollments'] = $stmt->fetchColumn();

        // Most popular course
        $stmt = $this->DB->prepare("
            SELECT c.title, COUNT(e.student_id) AS enrollments
            FROM courses c
            JOIN enrollments e ON c.course_id = e.course_id
            GROUP BY c.course_id
            ORDER BY enrollments DESC
            LIMIT 1
        ");
        $stmt->execute();
        $statistics['most_popular_course'] = $stmt->fetch(PDO::FETCH_ASSOC);

        return $statistics;
    }

    public function create(){

    }
    public function getUsers() {
        $stmt = $this->DB->prepare("SELECT userID, firstName, lastName, Email, role, status FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersOffSet($offset, $limit) {
        $stmt = $this->DB->prepare("SELECT userID, firstName, lastName, Email, role, status FROM users LIMIT :offset, :limit");
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingTeachers(){

    }
}
?>