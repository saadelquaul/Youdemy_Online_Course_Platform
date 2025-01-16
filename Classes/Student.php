<?php
require_once 'User.php';
class Studnet extends User {

    public function __construct($firstname,$lastname,$email,$password,$role,$db){
        parent::__construct($firstname,$lastname,$email,$password,$role,$db,true);

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

    

    

}



?>