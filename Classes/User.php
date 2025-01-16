<?php


abstract class User
{
    protected $FirstName;
    protected $LastNname;
    protected $Email;
    protected $Password;
    protected $Role;
    protected $DB;
    protected $ID;
    protected $isActive;

    public function __construct($firstname,$lastnme,$email,$password,$role,$db,$isActive) {
        $this->FirstName = $firstname;
        $this->LastNname = $lastnme;
        $this->Email = $email;
        $this->Password = $password;
        $this->Role = $role;
        $this->DB = $db;
        $this->isActive = $isActive;
    }

    public function setID(int $ID)
    {
        $this->ID = $ID;
    }
    public function getUserId() {
        return $this->ID;
    }

    public function getUserFullName() {
        return $this->FirstName . " " . $this->LastNname;
    }

    public function setRole($role){ 
        $this->Role = $role;
    }

    public function getRole() {
        return $this->Role;
    }

    public function setPassword(string $password)
    {
        $this->Password = password_hash($password,PASSWORD_BCRYPT);
    }

    public static function createUser($firstname,$lastname,$email,$password,$role,$db ,$isActive){
        $hashedPassword = password_hash($password,PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (firstname, lastname, Email, password, role, isActive) values (:FirstName, :LastName, :Email, :Role, :isActive)");
        $stmt->excute([
            'FirstName' => $firstname,
            'LastName' => $lastname,
            'Email' => $email,
            'Role' => $role,
            'isActive' => $isActive
        ]);


    }

    public static function getUserByName($firstname,$lastname,$db){
        $stmt = $db->prepare("SELECT * FROM users WHERE firstName = :firstname and lastName = :lastname");
        $stmt->excute([
            'firstname' => $firstname,
            'lastname' => $lastname 
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




}



?>
