<?php

class Teacher extends User {
    private $description;
    private $specialtyID;
    private $isActive = 0;
    private $total_courses;
    private $image;
    private $db;

    public function __construct($firstname, $lastname, $email, $password, $role, $description, $specialtyID, $status = 'inactive') {
        $this->description = $description;
        $this->specialtyID = $specialtyID;
        $this->db = Database::getInstance();
        parent::__construct($firstname, $lastname, $email, $password, $role, $status);
    }

    public function __sleep() {
        return array_merge(parent::__sleep(), ['description', 'specialty', 'isActive', 'total_courses', 'image']);
    }

    public function __wakeup() {
        $this->db = Database::getInstance();
    }

    public function setStatus($status) {
        $this->isActive = $status;
    }

    public function getStatus() {
        return $this->isActive;
    }

    public function setID($ID){

            $this->ID = $ID;
        
    }

    public function getID(){
        if(!$this->ID){
            $stmt = $this->db->prepare("SELECT userID FROM users WHERE email = :email");
            $stmt->execute(['email' => $this->Email]);
            $this->ID = $stmt->fetchColumn();
        }
        return $this->ID;
    }
    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setSpecialtyID($specialtyID) {
        $this->specialtyID = $specialtyID;
    }

    public function getSpecialtyID() {
        return $this->specialtyID;
    }

    public function setTotalCourses($total_courses) {
        $this->total_courses = $total_courses;
    }

    public function getTotalCourses() {
        return $this->total_courses;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }



    public function getName(){
        $stmt = $this->db->prepare("SELECT CONCAT(firstname , ' ' , lastname) as fullName FROM users WHERE userID = :id");
        $stmt->execute(['id' => $this->getID()]);
        return $stmt->fetchColumn();
    }

    public function getSpecialtyName() {
        $stmt = $this->db->prepare("SELECT specialtyName FROM specialties WHERE specialtyID = :specialtyID");
        $stmt->execute(['specialtyID' => $this->specialtyID]);
        return $stmt->fetchColumn();
    }

    public function addCourse($course) {
        $stmt = $this->db->prepare("INSERT INTO courses (title, description, content, teacher_id, category_id) VALUES (:title, :description, :content, :teacher_id, :category_id)");
        $stmt->execute([
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'content' => $course->getContent(),
            'teacher_id' => $this->getID(),
            'category_id' => $course->getCategoryID()
        ]);
        $course->setID($this->db->lastInsertId());
        if (!empty($course->getTags())) {
            foreach ($course->getTags() as $tag) {
                $this->addTagToCourse($tag, $course->getID());
            }
        }
        return $course->getID();
    }

    public function addTagToCourse($tag_id, $course_id) {
        $stmt = $this->db->prepare("INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)");
        $stmt->execute([
            'course_id' => $course_id,
            'tag_id' => $tag_id
        ]);
    }

    public function manageCourse( $action, $course) {
        if ($action === 'update') {
            $stmt = $this->db->prepare("UPDATE courses SET title = :title, description = :description, content = :content, category_id = :category_id WHERE course_id = :course_id AND teacher_id = :teacher_id");
            $stmt->execute([
                'title' => $course->getTitle(),
                'description' => $course->getDescription(),
                'content' => $course->getContent(),
                'teacher_id' => $this->getID(),
                'category_id' => $course->getCategoryID(),
                'course_id' => $course->getID()
            ]);

            $stmt = $this->db->prepare("DELETE FROM course_tags WHERE course_id = :course_id");
            $stmt->execute(['course_id' => $course->getID()]);
            if (!empty($course->getTags())) {
                foreach ($course->getTags() as $tag) {
                    $this->addTagToCourse($tag, $course->getID());
                }
            }
            
        } elseif ($action === 'delete') {
            $stmt = $this->db->prepare("DELETE FROM courses WHERE course_id = :course_id");
         return   $stmt->execute([
                'course_id' => $course->getID(),
            ]);
        }
    }

    public function viewCourseStatistics($course_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS enrolled_students FROM enrollments WHERE course_id = :course_id");
        $stmt->execute(['course_id' => $course_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function viewMyCourses() {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE teacher_id = :teacher_id");
        $stmt->execute(['teacher_id' => $this->getID()]);
        $courses = [];
        
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $course) {
            $coursee = new Course($course['title'], $course['description'], $course['content'], $course['category_id'], $course['teacher_id']);
            $coursee->setID($course['course_id']);
            $courses[] = $coursee;
        }
        return $courses;
    }

    public function getCourseDetails($course_id) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :course_id AND teacher_id = :teacher_id");
        $stmt->execute([
            'course_id' => $course_id,
            'teacher_id' => $this->ID
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($firstname, $lastname, $email, $description, $specialty, $image) {
        $stmt = $this->db->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email WHERE id = :id");
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'id' => $this->ID
        ]);
        

        $stmt = $this->db->prepare("UPDATE teachers SET description = :description, specialtyID = :specialty, image = :image WHERE teacherID = :id");
        $stmt->execute([
            'description' => $description,
            'specialty' => $specialty,
            'image' => $image,
            'id' => $this->ID
        ]);
    }
    public function getTotalStudents() {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT e.student_id) AS total_students
            FROM enrollments e
            JOIN courses c ON e.course_id = c.course_id
            WHERE c.teacher_id = :teacher_id
        ");
        $stmt->execute(['teacher_id' => $this->ID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['total_students'] : 0;
    }
    public static function getTeacherById($teacher_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT u.*,t.* FROM users u 
                              JOIN teachers t ON u.userID = t.teacherID
                              WHERE u.userid = :teacher_id");
        $stmt->execute(['teacher_id' => $teacher_id]);
        $teacherData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($teacherData) {
            $teacher = new Teacher(
                $teacherData['firstName'],
                $teacherData['lastName'],
                $teacherData['Email'],
                $teacherData['password'],
                $teacherData['role'],
                $teacherData['description'],
                $teacherData['specialtyID']
            );
            $teacher->setID($teacherData['teacherID']);
            return $teacher;
        }

        return null;
    }

    public function create() {
        $hashedPassword = password_hash($this->Password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (firstname, lastname, email, password, role, status) VALUES (:firstname, :lastname, :email, :password, :role, :status)");
        if ($stmt->execute([
            'firstname' => $this->FirstName,
            'lastname' => $this->LastName,
            'email' => $this->Email,
            'password' => $hashedPassword,
            'role' => $this->Role,
            'status' => $this->status
        ])) {
            $this->ID = $this->db->lastInsertId();
            $stmt = $this->db->prepare("INSERT INTO teachers (teacherID, description, total_courses, specialtyID, image, isActive) VALUES (:id, :description, :total_courses, :specialty, :image, :isActive)");
            $stmt->execute([
                'id' => $this->ID,
                'description' => $this->description,
                'total_courses' => 0,
                'specialty' => $this->specialtyID,
                'image' => $this->image,
                'isActive' => $this->isActive
            ]);
        }
    }
}
?>
