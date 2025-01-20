
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
    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="d-flex align-items-center justify-content-between bg-secondary w-100 text-decoration-none" data-toggle="collapse" href="#navbar-vertical" style="height: 67px; padding: 0 30px;">
                    <h5 class="text-primary m-0"><i class="fa fa-book-open mr-2"></i>Subjects</h5>
                    <i class="fa fa-angle-down text-primary"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 9;">
                    <div class="navbar-nav w-100">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link" data-toggle="dropdown">Web Design <i class="fa fa-angle-down float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                                <a href="" class="dropdown-item">HTML</a>
                                <a href="" class="dropdown-item">CSS</a>
                                <a href="" class="dropdown-item">jQuery</a>
                            </div>
                        </div>
                        <a href="" class="nav-item nav-link">Apps Design</a>
                        <a href="" class="nav-item nav-link">Marketing</a>
                        <a href="" class="nav-item nav-link">Research</a>
                        <a href="" class="nav-item nav-link">SEO</a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0"><span class="text-primary">You</span>Demy</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav py-0">
                            <a href="index.php" class="nav-item nav-link">Home</a>
                            <a href="course.php" class="nav-item nav-link active">Courses</a>
                            <a href="teacher.php" class="nav-item nav-link">Teachers</a>
                            <?php
                                if(isLoggedIn()){
                                    switch($user->getRole()) {
                                        case 'Admin' : echo '<a href="admin-dashboard.php" class="nav-item nav-link">Dashboard</a>' ;break;
                                        case 'teacher' : echo '<a href="teacher-dashboard.php" class="nav-item nav-link">Dashboard</a>' ;break;
                                        case 'student' : echo '<a href="teacher-dashboard.php" class="nav-item nav-link">Dashboard</a>' ;break;
                                    }
                                }
                            ?>
                        </div>
                        <?php if (!isLoggedIn()) {
                            echo '<a class="btn btn-secondary py-2 ml-auto px-4 d-none d-lg-block" href="login.php">Login</a>' .
                                '<a class="btn btn-primary py-2 px-4 ml-auto d-none d-lg-block" href="register.php">Join Now</a>';
                        } else {
                            echo '<a class="btn btn-secondary py-2 px-4 ml-auto d-none d-lg-block" href="logout.php">Logout</a>';
                        } ?>

                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->