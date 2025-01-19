<?php

require_once 'Classes/Admin.php';
require_once 'Classes/Teacher.php';
require_once 'Classes/Student.php';
require_once 'includes/db.php';

$error;
if($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_POST['email']))
{   
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user = User::getUserByEmail($email,$db);
    
    if(isset($user['Email'])){
        if(password_verify($password,$user['password'])){
            session_start();
            if($user['role'] === 'student'){
                $student = new Studnet($user['firstName'],$user['lastName'],$user['Email'],$user['password'],$user['role'],$db);
                $_SESSION['user'] = serialize($student);
                header('Loction: student-dashboard.php');
                exit;

            }elseif($user['role'] === 'teacher')
            {
                $teacher = new Teacher($user['firstName'],$user['lastName'],$user['Email'],$user['password'],$user['role'],$db);
                $_SESSION['user'] = serialize($teacher);
                header('Loction: teacher-dashboard');
                exit;
                
            }elseif($user['role'] === 'Admin'){
                $admin = new Admin($user['firstName'],$user['lastName'],$user['Email'],$user['password'],$user['role'],$db);
                $_SESSION['user'] = serialize($student);
                header('Loction: admin-dashboard.php');
                exit;

            }


        }else{
            echo "<script>validateForm(1,event,0)</script>";

        }
    }
    else{
        echo "<script>validateForm(1,event,0)</script>";
    }

}









?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>YouDemy - Online Courses</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="YouDemy E-Learning Online-Courses" name="keywords">
    <meta content="YouDemy provide Online-Courses from the best" name="description">

    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login&signup.css">
    <script>





    </script>

</head>

<body>
    
 <!-- Topbar Start -->
 <div class="container-fluid d-none d-lg-block">
        <div class="row align-items-center py-4 px-xl-5">
            <div class="col-lg-3">
                <a href="index.php" class="text-decoration-none">
                    <h1 class="m-0"><span class="text-primary">You</span>Demy</h1>
                </a>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-map-marker-alt text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 class="font-weight-semi-bold mb-1">Our Office</h6>
                        <small>123 Street, Safi, Morocco</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-envelope text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 class="font-weight-semi-bold mb-1">Email Us</h6>
                        <small>youdemy@online.org</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-phone text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 class="font-weight-semi-bold mb-1">Call Us</h6>
                        <small>+212 55 654 5589</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
    <div id="overlay"></div>
    <div id="alertBox">
        <p id="alertMessage">Invalid credentials!</p>
        <button onclick="closeAlert()">Close</button>
    </div>

    <div class="container-fluid bg-registration py-5  " style="margin: 90px 0;">
        <div class="container py-5">
            <div class="row align-items-center">
                
                <div class="col-lg-5">
                    <div class="card border-0">
                        <div class="card-header bg-light text-center p-4">
                            <h1 class="m-0">Login</h1>
                        </div>
                        <div class="card-body rounded-bottom bg-primary p-5">
                            <form method='post' action='login.php' onsubmit="validateForm(1,event)" >
                                <div class="form-group">
                                    <input type="email" class="form-control border-0 p-4" id="email" name="email" placeholder="Your email" required />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control border-0 p-4" id="password" placeholder="Your Password" name='password' required/>
                                </div>
                                <div>
                                    <button class="btn btn-dark btn-block border-0 py-3" type="submit">Login</button>
                                </div>
                                <p>Don't have an account?. <a href="register.php" style='color:black;'>Sign up</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php' ?>
    <script src="js/login&signup.js"></script>