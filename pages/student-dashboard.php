<?php
require '../includes/session.php';

if(!isLoggedIn()){
    header('Location: login.php');
    exit;
}
$user = getUser();


if($user->getRole() !== 'student'){
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll_course_id'])) {
    $course_id = $_POST['enroll_course_id'];
    if ($user->enrollInCourse($course_id)) {
        $message = "Successfully enrolled in course!";
    } else {
        $error = "Failed to enroll in course.";
    }
}


$enrolledCourses = $user->viewEnrolledCourses();

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';


$availableCourses = $user->getAvailableCourses($searchQuery, $categoryFilter);

$categories = Category::getAllCategories();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .course-card {
            transition: transform 0.2s;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .dashboard-stats {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .sidebar {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <h4 class="mb-4">Student Portal</h4>
            <div class="d-flex flex-column">
                <a href="#" class="btn btn-primary mb-2 text-start">Dashboard</a>
                <a href="#" class="btn btn-outline-primary mb-2 text-start">My Courses</a>
                <a href="#" class="btn btn-outline-primary mb-2 text-start">Course Catalog</a>
                <a href="logout.php" class="btn btn-outline-danger text-start">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <h2>Welcome, <?php echo htmlspecialchars($user->getFirstName()); ?></h2>
            
            <!-- Dashboard Stats -->
            <div class="row dashboard-stats">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Enrolled Courses</h5>
                            <h2 class="card-text" id="enrolledCount"><?php echo count($enrolledCourses); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Active Courses</h5>
                            <h2 class="card-text" id="activeCount"><?php echo count($enrolledCourses); // Simplified for demo ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Completed Courses</h5>
                            <h2 class="card-text" id="completedCount">0</h2> <!-- Simplified for demo -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Form -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search courses" value="<?php echo htmlspecialchars($searchQuery); ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->getCategoryId(); ?>" <?php echo $category->getCategoryId() == $categoryFilter ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->getName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Enrolled Courses -->
            <h3>My Enrolled Courses</h3>
            <div class="row" id="enrolledCourses">
                <?php foreach($enrolledCourses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card course-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course->getTitle()); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($course->getDescription()); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">In Progress</span>
                                <a href="#" class="btn btn-sm btn-outline-primary">View Course</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Available Courses -->
            <h3>Available Courses</h3>
            <div class="row" id="availableCourses">
                <?php foreach($availableCourses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card course-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course->getTitle()); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($course->getDescription()); ?></p>
                            <form method="POST">
                                <input type="hidden" name="enroll_course_id" value="<?php echo $course->getID(); ?>">
                                <button type="submit" class="btn btn-primary">Enroll Now</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>