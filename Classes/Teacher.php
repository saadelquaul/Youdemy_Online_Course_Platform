<?php

require_once 'User.php';

class Teacher extends User {

    private $description;
    private $specialty;
    private $isActive = 0;
    private $total_courses ;
    private $image;

    public function __construct($firstname, $lastname, $email,$password,$role,$description,$specialty,$status='inactive'){
        $this->description = $description;
        $this->specialty = $specialty;
        parent::__construct($firstname,$lastname,$email,$password,$role,$status);
    }

    public function __sleep() {
        return array_merge(parent::__sleep(), ['description', 'specialty', 'isActive', 'total_courses', 'image']);
    }
    public function __wakeup() {
        parent::__wakeup();
    }
    public function setStatu($status){
        $this->isActive = $status;
    }
    public function getStatu(){
       return $this->isActive;
    }
    public function addCourse($Course){
        $stmt = $this->DB->prepare("INSERT INTO courses (title, description, content, teacher_id, category_id) values (:title, :description, :content, :teacher_id, :category_id)");
        $stmt->execute([
            'title' => $Course->title,
            'description' => $Course->description,
            'content' =>$Course->content,
            'teacher_id' => $this->ID,
            'category_id' => $Course->category_id
        ]);
        $Course->setID($this->DB->lastInsertId());
        if (!empty($Course->tags)){
            foreach($Course->tags as $tag)
            {
                $this->addTagToCourse($tag,$Course->id);

            }
        } 

        return $Course->getID();
    }


    public function addTagToCourse($tag_id,$course_id)
    {
        $stmt = $this->DB->prepare("INSERT INTO course_tags (course_id, tag_id) values (:course_id, :tag_id)");
        $stmt->execute([
            'course_id' => $course_id,
            'tag_id' => $tag_id
        ]);
    }


    public function setTotal_courses($NumOfCourses){
        $this->total_courses = $NumOfCourses;
    }
    public function getTotal_courses(){
        return $this->total_courses ;
    }

    public function setImage($image){
        $this->image = $image;
    }
    public function getImage(){
    return  $this->image;
    }

    public function manageCourse($course_id,$action,$Course)
    {
        if($action === 'update'){
            
            $stmt = $this->DB->prepare("UPDATE courses set title = :title , description = :description,
             content = :content, teacher_id = :teacher_id,category_id = :category_id
             WHERE course_id = :course_id and teacher_id = :teacher_id");
             $stmt->execute([
                'title' => $Course->getTitle(),
                'description' => $Course->getDescription(),
                'content' => $Course->getContent(),
                'teacher_id' => $this->ID,
                'category_id' => $Course->getCategoryID(),
                'course_id' => $Course->getID()
             ]);

        }
        elseif($action === 'delete'){
            $stmt = $this->DB->prepare("DELETE FROM courses WHERE course_id = :course_id and teacher_id = :teacher_id");
            $stmt->execute([
                'course_id' => $Course->getID(),
                'teacher_id' => $this->ID
            ]);

        }
    }

    public function viewCoursStatistic($course_id){
        $stmt = $this->DB->prepare('SELECT COUNT(*) as enrolled_students from enrollments
        WHERE course_id = :course_id');
        $stmt->execute([
            'course_id' => $course_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewMyCourses(){
        $stmt = $this->DB->prepare("SELECT * from courses WHERE teacher_id = :teacher_id");
        $stmt->execute(['teacher_id' => $this->ID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function create(){
        $hashedPassword = password_hash($this->Password,PASSWORD_BCRYPT);
        $stmt = $this->DB->prepare("INSERT INTO users (firstname, lastname, Email, password, role,status) values (:FirstName, :LastName, :Email, :password, :Role, :status)");
        if($stmt->execute([
            'FirstName' => $this->FirstName,
            'LastName' => $this->LastName,
            'Email' => $this->Email,
            'password' => $hashedPassword,
            'Role' => $this->Role,
            'status' => $this->status
        ])){
            $this->ID =  $this->DB->lastInsertId();
            $stmt = $this->DB->prepare("INSERT INTO teachers (teacherID, description, total_courses, specialtyID, image, isActive) 
            values (:id, :description, :total_courses, :specialty, :image, :isActive)");
            $stmt->execute([
            "id" => $this->ID,
            "description" => $this->description,
            "total_courses" => 0,
            "specialty" => $this->specialty,
            "image" => NULL,
            "isActive" => $this->isActive
            
            ]);

        }
}
}




?>

