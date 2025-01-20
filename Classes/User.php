<?php
require 'includes/db.php';
abstract class User
{
    protected $FirstName;
    protected $LastName;
    protected $Email;
    protected $Password;
    protected $Role;
    protected $DB;
    protected $ID;

    public function __construct($firstname,$lastnme,$email,$password,$role) {
        $this->FirstName = $firstname;
        $this->LastName = $lastnme;
        $this->Email = $email;
        $this->Password = $password;
        $this->Role = $role;
        $this->DB = Database::getInstance();
    }
    public function __sleep() {
        return ['FirstName', 'LastName', 'Email', 'Password', 'Role', 'ID'];
    }

    public function __wakeup() {
     $this->DB = Database::getInstance();
    }

    public function setID(int $ID)
    {
        $this->ID = $ID;
    }
    public function getUserId() {
        return $this->ID;
    }

    public function getUserFullName() {
        return $this->FirstName . " " . $this->LastName;
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

    abstract public function create();


    public static function getUserByEmail($email,$DB){
        $stmt = $DB->prepare("SELECT * FROM users WHERE Email = :email");
        $stmt->execute([
            'email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




}



?>
