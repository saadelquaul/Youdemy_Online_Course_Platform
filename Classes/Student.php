<?php
require_once 'User.php';
class Student extends User {

    public function __construct($firstname,$lastname,$email,$password,$role,$db){
        parent::__construct($firstname,$lastname,$email,$password,$role,$db);

    }

    public function enrollInCourse($course_id){
        $stmt = $this->DB->prepare("INSERT INTO enrollments (student_id, course_id) values (:studentID,:courseID)");
        if($stmt->excute([
            'studentID' => $this->ID,
            'courseID' => $course_id
        ])) return true;
        
        return false;
    }

    public function viewEnrolledCourses(){
        $stmt = $this->DB->prepare("SELECT c.* from courses c
        JOIN enrollments e ON e.course_id = c.course_id
        WHERE e.studnet_id = :studentID");
        $stmt->execute(['studnetID' => $this->ID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function create(){
        $hashedPassword = password_hash($this->Password,PASSWORD_BCRYPT);
        $stmt = $this->DB->prepare("INSERT INTO users (firstname, lastname, Email, password, role) values (:FirstName, :LastName, :Email, :password, :Role)");
        $stmt->execute([
            'FirstName' => $this->FirstName,
            'LastName' => $this->LastName,
            'Email' => $this->Email,
            'password' => $hashedPassword,
            'Role' => $this->Role
        ]);
}
}



?>