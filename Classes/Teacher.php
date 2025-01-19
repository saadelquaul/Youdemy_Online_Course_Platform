<?php

require_once 'User.php';

class Teacher extends User {

    protected $isActive;

    public function __construct($firstname, $lastname, $email,$password,$role,$db){
        parent::__construct($firstname,$lastname,$email,$password,$role,$db,0);
        
    }


    public function addCourse($Course){
        $stmt = $this->DB->prepare("INSERT INTO courses (title, description, content, teacher_id, category_id) values (:title, :description, :content, :teacher_id, :category_id)");
        $stmt->excute([
            'title' => $Course->title,
            'description' => $Course->description,
            'content' =>$Course->content,
            'teacher_id' => $this->ID,
            'category_id' => $Course->category_id
        ]);
        $Course->setID($stmt->db->lastInsertId());
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
        $stmt = $this->DB->prepare("INSERT INTO users (firstname, lastname, Email, password, role, isActive) values (:FirstName, :LastName, :Email, :Role, :isActive)");
        $stmt->excute([
            'FirstName' => $this->FirstName,
            'LastName' => $this->LastName,
            'Email' => $this->Email,
            'Role' => $this->Role,
            'isActive' => $this->isActive
        ]);
}
}




?>

