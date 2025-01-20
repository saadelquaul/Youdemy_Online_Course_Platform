<?php
require 'Classes/Student.php';
require 'Classes/Teacher.php';
require 'Classes/Admin.php';
if(isset($_SESSION['user'])){
    header('location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['firstname'])) {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm-password']));
    $role = htmlspecialchars(trim($_POST['role']));
    $specialty = isset($_POST['specialty']) ? htmlspecialchars(trim($_POST['specialty'])) : null;
    $description = isset($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : null;

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
        echo "<script>formMessage(event,'All fields are required.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>formMessage(event,'Invalid email format.');</script>";
    } elseif ($password !== $confirmPassword) {
        echo "<script>formMessage(event'Passwords do not match.');</script>";
    }

            if($role == 1) {
                $student = new Student($firstname,$lastname,$email,$password,$role);
                $student->create();
            } elseif ($role == 2) {

                $teacher = new Teacher($firstname, $lastname, $email, $password, $role, $description, $specialty);
                $teacher->create();
            }
    header('location: login.php');
    
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
    <link href="css/login&signup.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
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



    <div class="container-fluid bg-registration py-5" style="margin: 90px 0;">
        <div class="container py-5">
            <div class="row align-items-center">

                <div class="col-lg-5">
                    <div class="card border-0">
                        <div class="card-header bg-light text-center p-4">
                            <h1 class="m-0">Sign Up Now</h1>
                        </div>
                        <div class="card-body rounded-bottom bg-primary p-5">
                            <form method='post' action='register.php' onsubmit="validateForm(0,event)">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0 p-4" placeholder="First name" name="firstname" id='firstName'  />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control border-0 p-4" placeholder="Last name" name="lastname" id='lastName'  />
                                </div>
                                <div class="form-group">
                                    <input type="email" id='s-email' name="email" class="form-control border-0 p-4" placeholder="Your email"  />
                                </div>
                                <div class="form-group">
                                    <input type="password" id='s-password' class="form-control border-0 p-4" placeholder="Your Password" name='password' />
                                </div>
                                <div class="form-group">
                                    <input type="password" id='s-c-password' class="form-control border-0 p-4" placeholder="Confirm Your Passowrd" name='confirm-password'  />
                                </div>
                                <div class="form-group">
                                    <label for="role">Sign up as a</label>
                                    <select class="custom-select border-0 px-4 role" name='role' id="role" style="height: 47px;">
                                        <option selected value="1">Student</option>
                                        <option value="2">Teacher</option>
                                    </select>
                                </div>
                                <div class="teacher">
                                    
                                </div>
                                <div>
                                    <button class="btn btn-dark btn-block border-0 py-3" type="submit">Sign Up Now</button>
                                </div>
                                <p>Have an account?. <a href="login.php" style='color:black;'>Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Registration End -->
    <?php include 'includes/footer.php' ?>
    <script src="js/login&signup.js"></script>