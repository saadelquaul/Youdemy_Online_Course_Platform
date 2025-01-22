<?php

class Student extends User
{


    public function __construct($firstname, $lastname, $email, $password, $role)
    {
        parent::__construct($firstname, $lastname, $email, $password, $role);
    }

    public function enrollInCourse($course_id)
    {
        $stmt = $this->DB->prepare("INSERT INTO enrollments (student_id, course_id) values (:studentID,:courseID)");
        if ($stmt->execute([
            'studentID' => $this->getID(),
            'courseID' => $course_id
        ])) return true;

        return false;
    }

    public function viewEnrolledCourses()
    {
        $stmt = $this->DB->prepare("SELECT c.* from courses c
        JOIN enrollments e ON e.course_id = c.course_id
        WHERE e.student_id = :studentID");
        $stmt->execute(['studentID' => $this->getID()]);
        $courses = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $course) {
            $coursee = new Course($course['title'], $course['description'], $course['content'], $course['category_id'], $course['teacher_id']);
            $coursee->setID($course['course_id']);
            $courses[] = $coursee;
        }
        return $courses;
    }

    public function getAvailableCourses($searchQuery = '', $categoryFilter = '') {

        $query = "SELECT * FROM courses WHERE course_id NOT IN (SELECT course_id FROM enrollments WHERE student_id = :studentID)";
        $params = ['studentID' => $this->ID];

        if ($searchQuery) {
            $query .= " AND (title LIKE :searchQuery OR description LIKE :searchQuery)";
            $params['searchQuery'] = '%' . $searchQuery . '%';
        }

        if ($categoryFilter) {
            $query .= " AND category_id = :categoryFilter";
            $params['categoryFilter'] = $categoryFilter;
        }

        $stmt = $this->DB->prepare($query);
        $stmt->execute($params);
        $courses = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $course){
            $coursee = new Course($course['title'], $course['description'], $course['content'], $course['category_id'], $course['teacher_id']);
            $coursee->setID($course['course_id']);
            $courses[] = $coursee;
        }
        return $courses;
    }

    public function create()
    {
        $hashedPassword = password_hash($this->Password, PASSWORD_BCRYPT);
        $stmt = $this->DB->prepare("INSERT INTO users (firstname, lastname, Email, password, role, status) values (:FirstName, :LastName, :Email, :password, :Role, :status)");
        $stmt->execute([
            'FirstName' => $this->FirstName,
            'LastName' => $this->LastName,
            'Email' => $this->Email,
            'password' => $hashedPassword,
            'Role' => $this->Role,
            'status' => $this->status
        ]);
    }

    public function getID() {
        if(!$this->ID){
            $stmt = $this->DB->prepare("SELECT userID FROM users WHERE Email = :Email");
            $stmt->execute(['Email' => $this->Email]);
            $this->ID = $stmt->fetchColumn();
         }
        return $this->ID;
    }

    public function setID($id) {
        $this->ID = $id;
    }

    public function getFirstName() {
        return $this->FirstName;
    }

    public function setFirstName($firstname) {
        $this->FirstName = $firstname;
    }

    public function getLastName() {
        return $this->LastName;
    }

    public function setLastName($lastname) {
        $this->LastName = $lastname;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setEmail($email) {
        $this->Email = $email;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setPassword($password) {
        $this->Password = $password;
    }

    public function getRole() {
        return $this->Role;
    }

    public function setRole($role) {
        $this->Role = $role;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function updateProfile($firstname, $lastname, $email, $password = null) {
        $this->FirstName = $firstname;
        $this->LastName = $lastname;
        $this->Email = $email;
        if ($password) {
            $this->Password = password_hash($password, PASSWORD_BCRYPT);
        }

        $stmt = $this->DB->prepare("UPDATE users SET firstname = :FirstName, lastname = :LastName, email = :Email, password = :Password WHERE id = :ID");
        return $stmt->execute([
            'FirstName' => $this->FirstName,
            'LastName' => $this->LastName,
            'Email' => $this->Email,
            'Password' => $this->Password,
            'ID' => $this->getID()
        ]);
    }

    public function deleteAccount() {
        $stmt = $this->DB->prepare("DELETE FROM users WHERE id = :ID");
        return $stmt->execute(['ID' => $this->getID(    )]);
    }


}
