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
      <div class="container-fluid bg-registration py-5" style="margin: 90px 0;">
        <div class="container py-5">
            <div class="row align-items-center">
                
                <div class="col-lg-5">
                    <div class="card border-0">
                        <div class="card-header bg-light text-center p-4">
                            <h1 class="m-0">Sign Up Now</h1>
                        </div>
                        <div class="card-body rounded-bottom bg-primary p-5">
                            <form method='post' action='register.php'>
                                <div class="form-group">
                                    <input type="text" class="form-control border-0 p-4" placeholder="First name" name="firstname" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control border-0 p-4" placeholder="Last name" name="lastname" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control border-0 p-4" placeholder="Your email" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control border-0 p-4" placeholder="Your Password" name='password' required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control border-0 p-4" placeholder="Confirm Your Passowrd" name='confirm-password' required="required" />
                                </div>
                                <div class="form-group">
                                    <select class="custom-select border-0 px-4 role" style="height: 47px;">
                                        <option selected>Sign up as a</option>
                                        <option value="1">Student</option>
                                        <option value="2">Teacher</option>
                                    </select>
                                </div>
                                <div class="teacher d-none">
                                <div class="form-group">
                                    <select class="custom-select border-0 px-4" style="height: 47px;">
                                        <option selected>Your Specialty</option>
                                        <option value="1">Programming</option>
                                        <option value="2">Data Science</option>
                                        <option value="3">Digital Marketing</option>
                                        <option value="4">Graphic Design</option>
                                        <option value="5">Music Production</option>
                                        <option value="6">Fitness & Nutrition</option>
                                        <option value="7">Photography</option>
                                        <option value="8">Business Management</option>
                                        <option value="9">Language Learning</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control border-0 p-4" placeholder="Leave a brief description about your self" name='confirm-password' required="required"></textarea>
                                </div>
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