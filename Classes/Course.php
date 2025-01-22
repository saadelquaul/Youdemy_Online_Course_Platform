<?php
// require_once '../includes/db.php';
// require 'includes/session.php';
class Course {

    private $id;
    private $title;
    private $description;
    private $content;
    private $category_id;
    private $tags = [];
    private $teacher_id;
    private $db;

    public function __construct($title, $description, $content, $category_id, $teacher_id) {
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->category_id = $category_id;
        $this->db = Database::getInstance();
        $this->teacher_id = $teacher_id;
    }


    public function addTag($tag) {
        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
            return true;
        }
        return false;
    }
    public function addTags(array $tags) {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function getID() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCategoryID() {
        return $this->category_id;
    }
    
    public static function getCourseById($course_id) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM courses WHERE course_id = :course_id");
        $stmt->execute(['course_id' => $course_id]);
        $courseData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($courseData) {
            $course = new Course(
                $courseData['title'],
                $courseData['description'],
                $courseData['content'],
                $courseData['category_id'],
                $courseData['teacher_id']
            );
            $course->setID($courseData['course_id']);
            return $course;
        }

        return null;
    }

    public function getTotalEnrollments() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total_enrollments FROM enrollments WHERE course_id = :course_id");
        $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['total_enrollments'] : 0;
    }

    public function getTags() {
        return $this->tags;
    }
    public function getTeacherNameById() {
        $stmt = $this->db->prepare("SELECT CONCAT(FirstName, ' ', LastName) AS teacher_name FROM users WHERE userid = :teacher_id");
        $stmt->bindParam(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
        $stmt->execute();
       return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getTeacherID() {
        return $this->teacher_id;
    }

    public function save() {
        
            $stmt = $this->db->prepare("INSERT INTO courses (title, description, content, category_id, teacher_id) VALUES (:title, :description, :content, :category_id, :teacher_id)");
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':content', $this->content);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':teacher_id', $this->teacher_id);
            $stmt->execute();
    
            $this->id = $this->db->lastInsertId();
    
            $stmt = $this->db->prepare("INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)");
            foreach ($this->tags as $tag_id) {
                $stmt->bindParam(':course_id', $this->id);
                $stmt->bindParam(':tag_id', $tag_id);
                $stmt->execute();
            }
       
    }

    public function update() {
        $stmt = $this->db->prepare("UPDATE courses SET title = :title, description = :description, content = :content, category_id = :category_id, tags = :tags WHERE id = :id");
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':tags', implode(',', $this->tags));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getAllCourses() {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT c.*, GROUP_CONCAT(t.name) AS tags, u.userid 
            FROM courses c
            LEFT JOIN course_tags ct ON c.course_id = ct.course_id
            LEFT JOIN tags t ON ct.tag_id = t.tag_id
            LEFT JOIN users u ON c.teacher_id = u.userid
            GROUP BY c.course_id
        ");
        $stmt->execute();
        $courses = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $course = new Course(
                $row['title'],
                $row['description'],
                $row['content'],
                $row['category_id'],
                $row['userid'],
                
            );
            $course->setID($row['course_id']);
            $course->tags = $row['tags'] ? explode(',', $row['tags']) : [];
            $courses[] = $course;
        }
        return $courses;
    }
}
?>